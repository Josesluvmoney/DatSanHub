<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

function writeLog($message) {
    $logFile = 'debug.log';
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[$timestamp] $message\n";
    file_put_contents($logFile, $logMessage, FILE_APPEND);
}

session_start();
require_once 'config.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit();
}

// Khởi tạo giỏ hàng nếu chưa có
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Xử lý thêm vào giỏ hàng
if (isset($_POST['add_to_cart'])) {
    $user_id = $_SESSION['user_id'];
    $item_id = $_POST['id'];
    $type = $_POST['type'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $image = $_POST['image'];
    $action_type = isset($_POST['action_type']) ? $_POST['action_type'] : null;

    $conn = new mysqli($servername, $username, $password, $dbname);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Kiểm tra xem item đã có trong giỏ hàng chưa
    $check_sql = "SELECT id, quantity FROM tbl_giohang_temp 
                 WHERE id_TK = ? AND type = ? AND 
                 " . ($type === 'court' ? "id_San = ?" : "id_DungCu = ?") . "
                 " . ($action_type ? "AND action_type = ?" : "");

    $check_stmt = $conn->prepare($check_sql);
    
    if ($action_type) {
        $check_stmt->bind_param("isss", $user_id, $type, $item_id, $action_type);
    } else {
        $check_stmt->bind_param("isi", $user_id, $type, $item_id);
    }
    
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        // Cập nhật số lượng nếu đã tồn tại
        $row = $result->fetch_assoc();
        $new_quantity = $row['quantity'] + 1;
        
        $update_sql = "UPDATE tbl_giohang_temp SET quantity = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ii", $new_quantity, $row['id']);
        $update_stmt->execute();
    } else {
        // Thêm mới nếu chưa tồn tại
        $insert_sql = "INSERT INTO tbl_giohang_temp 
                      (id_TK, id_San, id_DungCu, type, action_type, name, price) 
                      VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_sql);
        
        if ($type === 'court') {
            $null_value = null;
            $stmt->bind_param("iiisssd", $user_id, $item_id, $null_value, $type, $action_type, $name, $price);
        } else {
            $null_value = null;
            $stmt->bind_param("iiisssd", $user_id, $null_value, $item_id, $type, $action_type, $name, $price);
        }
        
        $stmt->execute();
    }

    $conn->close();
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}

// Xử lý cập nhật số lượng
if (isset($_POST['update_quantity'])) {
    $user_id = $_SESSION['user_id'];
    $item_id = $_POST['item_id'];
    $quantity = max(1, intval($_POST['quantity'])); // Đảm bảo số lượng tối thiểu là 1
    
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Cập nhật số lượng trong giỏ hàng
    $sql = "UPDATE tbl_giohang_temp SET quantity = ? WHERE id = ? AND id_TK = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $quantity, $item_id, $user_id);
    $stmt->execute();

    $conn->close();
    header('Location: giohang.php');
    exit();
}

// Xử lý xóa item khỏi giỏ hàng
if (isset($_POST['remove_item'])) {
    $user_id = $_SESSION['user_id'];
    $item_id = $_POST['remove_item'];
    
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Xóa item từ giỏ hàng tạm
    $sql = "DELETE FROM tbl_giohang_temp WHERE id = ? AND id_TK = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $item_id, $user_id);
    $stmt->execute();

    $conn->close();
    header('Location: giohang.php');
    exit();
}

// Xử lý xóa toàn bộ giỏ hàng
if (isset($_POST['clear_cart'])) {
    $_SESSION['cart'] = array();
    header('Location: giohang.php');
    exit();
}

// Xử lý đặt hàng
if (isset($_POST['checkout'])) {
    writeLog("=== Start Checkout Process ===");
    writeLog("POST data: " . print_r($_POST, true));
    
    $user_id = $_SESSION['user_id'];
    $payment_method = $_POST['payment_method'];
    $receiver_name = $_POST['receiver_name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $note = $_POST['note'] ?? '';

    writeLog("User ID: $user_id");
    writeLog("Payment Method: $payment_method");
    
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    if ($conn->connect_error) {
        writeLog("Database Connection Error: " . $conn->connect_error);
        die("Connection failed: " . $conn->connect_error);
    }

    try {
        $conn->begin_transaction();
        writeLog("Transaction started");

        // Lấy items từ giỏ hàng
        $sql = "SELECT * FROM tbl_giohang_temp WHERE id_TK = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $cart_items = $stmt->get_result();
        
        $cart_data = $cart_items->fetch_all(MYSQLI_ASSOC);
        writeLog("Cart items found: " . count($cart_data));
        writeLog("Cart data: " . print_r($cart_data, true));

        if (count($cart_data) > 0) {
            foreach ($cart_data as $item) {
                writeLog("Processing item: " . print_r($item, true));
                
                $total_price = $item['price'] * $item['quantity'];
                
                $sql = "INSERT INTO tbl_donhang (
                    id_TK, 
                    id_San, 
                    id_DungCu, 
                    type, 
                    action_type,
                    name,
                    quantity, 
                    price, 
                    total_price, 
                    payment_method,
                    payment_status,
                    order_status,
                    receiver_name,
                    phone,
                    address,
                    note
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                
                $stmt = $conn->prepare($sql);
                if (!$stmt) {
                    writeLog("Prepare Error: " . $conn->error);
                    throw new Exception("Lỗi chuẩn bị câu lệnh SQL");
                }
                
                // Đặt trạng thái thanh toán và đơn hàng
                if ($payment_method === 'banking') {
                    $payment_status = 'pending'; // Chờ xác nhận thanh toán
                    $order_status = 'pending';   // Chờ xác nhận đơn hàng
                } else {
                    $payment_status = 'pending'; // COD
                    $order_status = 'success';   // Đơn hàng được chấp nhận ngay
                }
                
                $stmt->bind_param("iiisssiidsssssss", 
                    $user_id,
                    $item['id_San'],
                    $item['id_DungCu'],
                    $item['type'],
                    $item['action_type'],
                    $item['name'],
                    $item['quantity'],
                    $item['price'],
                    $total_price,
                    $payment_method,
                    $payment_status,
                    $order_status,
                    $receiver_name,
                    $phone,
                    $address,
                    $note
                );
                
                if (!$stmt->execute()) {
                    writeLog("Execute Error: " . $stmt->error);
                    throw new Exception("Không thể thêm đơn hàng: " . $stmt->error);
                }
                
                writeLog("Order inserted successfully. ID: " . $conn->insert_id);
            }

            // Xóa giỏ hàng sau khi đã xử lý thành công
            $sql = "DELETE FROM tbl_giohang_temp WHERE id_TK = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
            
            if (!$stmt->execute()) {
                writeLog("Clear Cart Error: " . $stmt->error);
                throw new Exception("Không thể xóa giỏ hàng tạm");
            }
            
            writeLog("Cart cleared successfully");
            
            $conn->commit();
            writeLog("Transaction committed successfully");
            
            if ($payment_method === 'banking') {
                // Chuyển đến trang chờ xác nhận thanh toán
                header("Location: giohang.php?order_pending=1");
            } else {
                // COD - chuyển đến trang thành công
                header("Location: giohang.php?order_success=1");
            }
            exit();
        } else {
            writeLog("No items found in cart");
            throw new Exception("Giỏ hàng trống");
        }

    } catch (Exception $e) {
        $conn->rollback();
        writeLog("Error occurred: " . $e->getMessage());
        writeLog("Transaction rolled back");
        header("Location: giohang.php?error=" . urlencode($e->getMessage()));
        exit();
    }

    $conn->close();
}

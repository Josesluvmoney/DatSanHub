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

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

if (isset($_POST['add_to_cart'])) {
    $response = array('success' => false);
    
    if (!isset($_SESSION['logged_in'])) {
        $response['message'] = 'Vui lòng đăng nhập để thêm vào giỏ hàng';
        echo json_encode($response);
        exit;
    }
    
    $user_id = $_SESSION['user_id'];
    $item_id = $_POST['id'];
    $type = $_POST['type'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $image_status = $_POST['image_status'];
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
        if ($type === 'court') {
            $booking_date = $_POST['booking_date'];
            $start_time = $_POST['start_time'];
            $duration = (int)$_POST['duration'];
            $end_time = date('H:i:s', strtotime($start_time . ' + ' . $duration . ' hours'));
            
            // Kiểm tra thời gian hoạt động của sân
            $sql = "SELECT Opening_time, Closing_time FROM tbl_san WHERE id_San = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $item_id);
            $stmt->execute();
            $court_hours = $stmt->get_result()->fetch_assoc();
            
            if (strtotime($start_time) < strtotime($court_hours['Opening_time']) || 
                strtotime($end_time) > strtotime($court_hours['Closing_time'])) {
                $response['success'] = false;
                $response['message'] = 'Thời gian đặt sân nằm ngoài giờ hoạt động';
                echo json_encode($response);
                exit;
            }

            // Kiểm tra xem sân đã được đặt chưa
            $sql = "SELECT id_LichDatSan FROM tbl_chitietlichdatsan 
                    WHERE id_San = ? AND booking_date = ? 
                    AND ((start_time <= ? AND end_time > ?) 
                    OR (start_time < ? AND end_time >= ?)
                    OR (start_time >= ? AND end_time <= ?))
                    AND payment_status != 'cancelled'";
            
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isssssss", 
                $item_id, 
                $booking_date, 
                $start_time, 
                $start_time,
                $end_time, 
                $end_time,
                $start_time,
                $end_time
            );
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $response['success'] = false;
                $response['message'] = 'Sân đã được đặt trong khoảng thời gian này';
                echo json_encode($response);
                exit;
            }

            // Thêm vào giỏ hàng tạm
            $insert_sql = "INSERT INTO tbl_giohang_temp 
                          (id_TK, id_San, type, name, price, booking_date, start_time, duration, end_time) 
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insert_sql);
            $stmt->bind_param("iissdssss", 
                $user_id, 
                $item_id, 
                $type, 
                $name, 
                $price,
                $booking_date,
                $start_time,
                $duration,
                $end_time
            );
        } else {
            $insert_sql = "INSERT INTO tbl_giohang_temp 
                          (id_TK, id_San, id_DungCu, type, action_type, name, price) 
                          VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insert_sql);
            
            $null_value = null;
            $stmt->bind_param("iiisssd", $user_id, $null_value, $item_id, $type, $action_type, $name, $price);
        }
        
        $stmt->execute();
    }

    $conn->close();
    $response['success'] = true;
    $response['cartCount'] = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
    echo json_encode($response);
    exit;
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
            // Thiết lập giá trị mặc định cho trạng thái
            $order_status = 'pending';
            $payment_status = $payment_method === 'banking' ? 'pending' : 'paid';

            foreach ($cart_data as $item) {
                writeLog("Processing item: " . print_r($item, true));
                
                $total_price = $item['price'] * $item['quantity'];
                
                if ($item['type'] === 'court') {
                    $insert_sql = "INSERT INTO tbl_donhang 
                                  (id_TK, id_San, type, name, price, quantity, total_price, 
                                   booking_date, start_time, end_time, duration,
                                   payment_method, payment_status, order_status, created_at) 
                                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
                    $stmt = $conn->prepare($insert_sql);
                    $stmt->bind_param("iissdddssssss", 
                        $user_id,
                        $item['id_San'],
                        $item['type'],
                        $item['name'],
                        $item['price'],
                        $item['quantity'],
                        $total_price,
                        $item['booking_date'],
                        $item['start_time'],
                        $item['end_time'],
                        $item['duration'],
                        $payment_method,
                        $payment_status,
                        $order_status
                    );
                } else {
                    $insert_sql = "INSERT INTO tbl_donhang 
                                  (id_TK, id_San, id_DungCu, type, action_type, name, price, quantity, total_price, 
                                   payment_method, payment_status, order_status, created_at) 
                                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
                    $stmt = $conn->prepare($insert_sql);
                    $stmt->bind_param("iiisssddssss", 
                        $user_id,
                        $item['id_San'],
                        $item['id_DungCu'],
                        $item['type'],
                        $item['action_type'],
                        $item['name'],
                        $item['price'],
                        $item['quantity'],
                        $total_price,
                        $payment_method,
                        $payment_status,
                        $order_status
                    );
                }
                
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

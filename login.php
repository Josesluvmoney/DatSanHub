<?php
session_start();
require 'config.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $phone = trim($_POST['phone']);
    $password = trim($_POST['password']);

    // Debug log
    error_log("Starting login process...");

    // Validate input
    if(empty($phone) || empty($password)) {
        echo json_encode([
            'success' => false,
            'message' => 'Vui lòng điền đầy đủ thông tin'
        ]);
        exit;
    }
    
    try {
        // Test database connection
        if (!$conn) {
            throw new Exception("Database connection failed");
        }

        $sql = "SELECT * FROM tbl_user WHERE Phone = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare statement failed: " . $conn->error);
        }

        $stmt->bind_param("s", $phone);
        if (!$stmt->execute()) {
            throw new Exception("Query execution failed: " . $stmt->error);
        }

        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if(md5($password) === $user['Password']) {
                // Tạo session token ngẫu nhiên
                $token = bin2hex(random_bytes(32));
                $_SESSION['token'] = $token;
                $_SESSION['logged_in'] = true;
                $_SESSION['user_id'] = $user['id_TK'];
                $_SESSION['fullname'] = $user['Fullname'];
                $_SESSION['phone'] = $user['Phone'];
                
                echo json_encode([
                    'success' => true,
                    'message' => 'Đăng nhập thành công',
                    'token' => $token
                ]);
                exit;
            }
        }
        
        // Trường hợp đăng nhập thất bại
        echo json_encode([
            'success' => false,
            'message' => 'Số điện thoại hoặc mật khẩu không chính xác'
        ]);
        
    } catch (Exception $e) {
        error_log("Login error: " . $e->getMessage());
        echo json_encode([
            'success' => false,
            'message' => 'Đăng nhập thất bại, vui lòng thử lại sau'
        ]);
    }
    exit;
}

// Nếu không phải POST request
http_response_code(403);
echo json_encode([
    'success' => false,
    'message' => 'Phương thức không được phép'
]);
exit;
?>

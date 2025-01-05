<?php
session_start();
require 'config.php';

header('Content-Type: application/json');

if(isset($_POST['register'])) {
    $fullname = trim($_POST['fullname']);
    $phone = trim($_POST['phone']);
    $password = trim($_POST['password']);

    // Debug log
    error_log("Starting registration process...");
    error_log("Input data - Name: $fullname, Phone: $phone");

    // Validate input
    if(empty($fullname) || empty($phone) || empty($password)) {
        echo json_encode([
            'success' => false,
            'message' => 'Vui lòng điền đầy đủ thông tin'
        ]);
        error_log("Empty fields detected");
        exit();
    }

    try {
        // Test database connection
        if (!$conn) {
            throw new Exception("Database connection failed");
        }
        error_log("Database connection successful");

        // Kiểm tra số điện thoại đã tồn tại chưa
        $check_phone = "SELECT * FROM tbl_user WHERE Phone=?";
        $stmt = $conn->prepare($check_phone);
        if (!$stmt) {
            throw new Exception("Prepare statement failed: " . $conn->error);
        }
        
        $stmt->bind_param("s", $phone);
        if (!$stmt->execute()) {
            throw new Exception("Check phone execution failed: " . $stmt->error);
        }
        
        $result = $stmt->get_result();
        error_log("Phone check query executed successfully");
        
        if($result->num_rows > 0) {
            echo json_encode([
                'success' => false,
                'message' => 'Số điện thoại đã được đăng ký'
            ]);
            error_log("Phone number already exists: $phone");
            exit();
        }

        // Thêm người dùng mới
        $sql = "INSERT INTO tbl_user (Fullname, Phone, Password) VALUES (?, ?, ?)";
        error_log("Preparing insert query: $sql");
        
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare insert statement failed: " . $conn->error);
        }
        
        $hashed_password = md5($password);
        $stmt->bind_param("sss", $fullname, $phone, $hashed_password);
        
        error_log("Executing insert query...");
        if($stmt->execute()) {
            $user_id = $conn->insert_id;
            error_log("Insert successful. New user ID: $user_id");
            
            $_SESSION['logged_in'] = true;
            $_SESSION['user_id'] = $user_id;
            $_SESSION['fullname'] = $fullname;
            $_SESSION['phone'] = $phone;
            
            echo json_encode([
                'success' => true,
                'message' => 'Đăng ký thành công',
                'debug_info' => "User ID: $user_id"
            ]);
        } else {
            throw new Exception("Insert execution failed: " . $stmt->error);
        }
    } catch (Exception $e) {
        error_log("Registration error: " . $e->getMessage());
        echo json_encode([
            'success' => false,
            'message' => 'Đăng ký thất bại: ' . $e->getMessage(),
            'debug_info' => $e->getTraceAsString()
        ]);
    }
    exit();
}

echo json_encode([
    'success' => false,
    'message' => 'Invalid request method'
]);
exit();
?>

<?php
session_start();
require 'config.php';

header('Content-Type: application/json');

if(isset($_POST['register'])) {
    $fullname = $_POST['fullname'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];

    // Kiểm tra số điện thoại đã tồn tại chưa
    $check_phone = "SELECT * FROM tbl_user WHERE phone=?";
    $stmt = $conn->prepare($check_phone);
    $stmt->bind_param("s", $phone);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result->num_rows > 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Số điện thoại đã được đăng ký'
        ]);
        exit();
    }

    // Thêm người dùng mới với đầy đủ các trường bắt buộc
    $sql = "INSERT INTO `tbl_user` (`fullname`, `phone`, `password`, `Role`, `Create_at`) 
            VALUES (?, ?, ?, 0, NOW())";
    $stmt = $conn->prepare($sql);
    $hashed_password = md5($password);
    $stmt->bind_param("sss", $fullname, $phone, $hashed_password);
            
    if($stmt->execute()) {
        // Lấy ID của user vừa đăng ký
        $user_id = $conn->insert_id;
        
        // Tự động đăng nhập sau khi đăng ký thành công
        $_SESSION['logged_in'] = true;
        $_SESSION['user_id'] = $user_id;
        $_SESSION['fullname'] = $fullname;
        $_SESSION['phone'] = $phone;
        
        echo json_encode([
            'success' => true,
            'message' => 'Đăng ký thành công'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Đăng ký thất bại: ' . $conn->error
        ]);
    }
    exit();
}

echo json_encode([
    'success' => false,
    'message' => 'Invalid request'
]);
exit();
?>

<?php
session_start();
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $phone = $_POST['phone'];
    $password = md5($_POST['password']);
    
    $sql = "SELECT * FROM reg WHERE phone = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $phone, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['logged_in'] = true;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['fullname'] = $user['fullname'];
        $_SESSION['phone'] = $user['phone'];
        
        // Trả về JSON response khi đăng nhập thành công
        echo json_encode([
            'success' => true,
            'message' => 'Đăng nhập thành công'
        ]);
        exit;
    }
    
    // Trả về JSON response khi đăng nhập thất bại
    echo json_encode([
        'success' => false,
        'message' => 'Số điện thoại hoặc mật khẩu không chính xác'
    ]);
    exit;
}
?>

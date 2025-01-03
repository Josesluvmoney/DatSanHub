<?php
session_start();
require 'config.php';

if(isset($_POST['login'])) {
    $phone = $_POST['phone'];
    $password = md5($_POST['password']);
    
    $sql = "SELECT * FROM reg WHERE phone='$phone' AND password='$password'";
    $result = $conn->query($sql);
    
    if($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Lưu thông tin vào session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['fullname'] = $user['fullname'];
        $_SESSION['phone'] = $user['phone'];
        $_SESSION['logged_in'] = true;
        
        header("Location: trangchu.php?login=success");
        exit();
    } else {
        // Thêm debug
        error_log("Login failed for phone: $phone");
        error_log("SQL query: $sql");
        header("Location: trangchu.php?login=failed");
        exit();
    }
}
?>

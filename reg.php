<?php
session_start();
require 'config.php';

if(isset($_POST['register'])) {
    $fullname = $_POST['fullname'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];

    // Kiểm tra số điện thoại đã tồn tại chưa
    $check_phone = "SELECT * FROM reg WHERE phone='$phone'";
    $result = $conn->query($check_phone);
    
    if($result->num_rows > 0) {
        header("Location: trangchu.php?register=phone_exists");
        exit();
    }

    // Thêm người dùng mới
    $sql = "INSERT INTO `reg` (`fullname`, `phone`, `password`) 
            VALUES('$fullname', '$phone', md5('$password'))";
            
    if($conn->query($sql) === TRUE) {
        header("Location: trangchu.php?register=success");
        exit();
    } else {
        header("Location: trangchu.php?register=failed");
        exit();
    }
}
?>
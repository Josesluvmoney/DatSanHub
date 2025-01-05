<?php
session_start();

// Lưu URL trang trước khi logout
$previous_page = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'trangchu.php';

session_destroy();
header('Content-Type: application/json');

echo json_encode([
    'success' => true,
    'message' => 'Đăng xuất thành công',
    'redirect_url' => $previous_page
]);
exit;
?> 

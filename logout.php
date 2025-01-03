<?php
session_start();
session_destroy();

$previous_page = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'trangchu.php';

// Chuyển hướng về trang trước đó
header("Location: " . $previous_page);
exit();
?> 

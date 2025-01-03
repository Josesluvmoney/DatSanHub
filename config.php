<?php
 // Thiết lập thông tin kết nối
 $servername = "localhost"; //Địa chỉ máy chủ
 $username = "root"; //Tên đăng nhập
 $password = ""; //Mật khẩu
 $dbname="testdb01";//Tên cơ sở dữ liệu cần kết nối
 // Tạo kết nối
 $conn = new mysqli($servername, $username, $password, $dbname);
 // Kiểm tra kết nối
 if ($conn->connect_error) {
 die("Kết nối thất bại: " . $conn->connect_error);
 }
 // Sử dụng bộ mã UTF8 khi thực hiện các thao tác dữ liệu
 $conn->set_charset("utf8");
 ?>
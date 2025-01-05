<?php
require_once 'config.php';

function uploadCourtImage($conn, $courtName, $imagePath) {
    if (!file_exists($imagePath)) {
        return "File không tồn tại: $imagePath";
    }

    $imageData = file_get_contents($imagePath);
    if (!$imageData) {
        return "Không thể đọc file: $imagePath";
    }

    $stmt = $conn->prepare("CALL InsertCourtImage(?, ?)");
    if (!$stmt) {
        return "Lỗi prepare statement: " . $conn->error;
    }

    $null = NULL;
    $stmt->bind_param("sb", $courtName, $null);
    $stmt->send_long_data(1, $imageData);
    $result = $stmt->execute();
    
    if($result) {
        $message = "Upload ảnh thành công cho sân $courtName";
    } else {
        $message = "Lỗi upload ảnh: " . $stmt->error;
    }
    $stmt->close();
    return $message;
}

// Đường dẫn tới thư mục courts của bạn
$imageFolder = "images/courts/";

// Danh sách ảnh cho từng sân
$images = [
    'Sân Cầu Lông 1' => $imageFolder . 'badminton1.jpg',
    'Sân Cầu Lông 2' => $imageFolder . 'badminton2.jpg',
    'Sân Cầu Lông 3' => $imageFolder . 'badminton3.jpg',
    'Sân Cầu Lông 4' => $imageFolder . 'badminton4.jpg',
    'Sân Cầu Lông 5' => $imageFolder . 'badminton5.jpg',
    'Sân Cầu Lông 6' => $imageFolder . 'badminton6.jpg',
    'Sân Cầu Lông 7' => $imageFolder . 'badminton7.jpg'
];

// Upload từng ảnh
foreach($images as $courtName => $imagePath) {
    $result = uploadCourtImage($conn, $courtName, $imagePath);
    echo $result . "<br>";
}
?> 
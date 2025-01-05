<?php
require_once 'config.php';

function uploadImage($conn, $name, $imagePath, $type = 'court') {
    if (!file_exists($imagePath)) {
        return "File không tồn tại: $imagePath";
    }

    $imageData = file_get_contents($imagePath);
    if (!$imageData) {
        return "Không thể đọc file: $imagePath";
    }

    if ($type == 'court') {
        $stmt = $conn->prepare("CALL InsertCourtImage(?, ?)");
    } else {
        $stmt = $conn->prepare("CALL InsertEquipmentImage(?, ?)");
    }

    if (!$stmt) {
        return "Lỗi prepare statement: " . $conn->error;
    }

    $null = NULL;
    $stmt->bind_param("sb", $name, $null);
    $stmt->send_long_data(1, $imageData);
    $result = $stmt->execute();
    
    if($result) {
        $message = "Upload ảnh thành công cho " . ($type == 'court' ? 'sân' : 'dụng cụ') . " $name";
    } else {
        $message = "Lỗi upload ảnh: " . $stmt->error;
    }
    $stmt->close();
    return $message;
}

// Đường dẫn tới thư mục chứa ảnh
$imageFolder = "images/";

// Danh sách ảnh cho sân và dụng cụ
$images = [
    'courts' => [
        // Sân cầu lông
        'Sân Cầu Lông 1' => $imageFolder . 'courts/badminton1.jpg',
        'Sân Cầu Lông 2' => $imageFolder . 'courts/badminton2.jpg',
        // ... thêm các sân khác
    ],
    'equipment' => [
        // Bóng đá
        'Bóng đá Nike Strike' => $imageFolder . 'equipment/football1.jpg',
        'Giày đá bóng Nike Tempo' => $imageFolder . 'equipment/football2.jpg',
        // ... thêm các dụng cụ khác
    ]
];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload Ảnh</title>
    <style>
        .upload-section { margin: 20px; }
        .type-select { margin-bottom: 20px; }
        .content-section { display: none; }
        .content-section.active { display: block; }
        .court-type, .equipment-type { margin: 10px 0; }
        .success { color: green; }
        .error { color: red; }
        .tab-buttons {
            margin-bottom: 20px;
        }
        .tab-button {
            padding: 10px 20px;
            margin-right: 10px;
            border: none;
            background: #f0f0f0;
            cursor: pointer;
        }
        .tab-button.active {
            background: #004d40;
            color: white;
        }
    </style>
</head>
<body>
    <div class="upload-section">
        <h2>Upload Ảnh</h2>
        
        <div class="tab-buttons">
            <button class="tab-button active" data-type="court">Upload ảnh sân</button>
            <button class="tab-button" data-type="equipment">Upload ảnh dụng cụ</button>
        </div>

        <div class="content-section active" id="court-section">
            <form method="post">
                <div class="court-type">
                    <h3>Chọn loại sân:</h3>
                    <label><input type="checkbox" name="types[]" value="badminton"> Sân cầu lông</label><br>
                    <label><input type="checkbox" name="types[]" value="football"> Sân bóng đá</label><br>
                    <label><input type="checkbox" name="types[]" value="tennis"> Sân tennis</label><br>
                    <label><input type="checkbox" name="types[]" value="pickleball"> Sân pickleball</label><br>
                    <label><input type="checkbox" name="types[]" value="volleyball"> Sân bóng chuyền</label><br>
                    <label><input type="checkbox" name="types[]" value="basketball"> Sân bóng rổ</label><br>
                </div>
                <button type="submit" name="upload" value="court">Upload ảnh cho các sân đã chọn</button>
            </form>
        </div>

        <div class="content-section" id="equipment-section">
            <form method="post">
                <div class="equipment-type">
                    <h3>Chọn loại dụng cụ:</h3>
                    <label><input type="checkbox" name="types[]" value="football"> Dụng cụ bóng đá</label><br>
                    <label><input type="checkbox" name="types[]" value="badminton"> Dụng cụ cầu lông</label><br>
                    <label><input type="checkbox" name="types[]" value="tennis"> Dụng cụ tennis</label><br>
                    <label><input type="checkbox" name="types[]" value="pickleball"> Dụng cụ pickleball</label><br>
                    <label><input type="checkbox" name="types[]" value="volleyball"> Dụng cụ bóng chuyền</label><br>
                    <label><input type="checkbox" name="types[]" value="basketball"> Dụng cụ bóng rổ</label><br>
                </div>
                <button type="submit" name="upload" value="equipment">Upload ảnh cho các dụng cụ đã chọn</button>
            </form>
        </div>

        <div class="results">
            <?php
            if (isset($_POST['upload'])) {
                if (!isset($_POST['types'])) {
                    echo "<p class='error'>Vui lòng chọn ít nhất một loại!</p>";
                } else {
                    $selectedTypes = $_POST['types'];
                    $uploadType = $_POST['upload']; // 'court' hoặc 'equipment'
                    $imageList = $images[$uploadType.'s'];
                    $uploadCount = 0;
                    $errorCount = 0;
                    
                    foreach ($imageList as $name => $imagePath) {
                        $itemType = '';
                        foreach ($selectedTypes as $type) {
                            if (stripos($name, $type) !== false) {
                                $itemType = $type;
                                break;
                            }
                        }
                        
                        if ($itemType) {
                            $result = uploadImage($conn, $name, $imagePath, $uploadType);
                            if (strpos($result, 'thành công') !== false) {
                                $uploadCount++;
                            } else {
                                $errorCount++;
                            }
                            echo "<p class='" . (strpos($result, 'thành công') !== false ? 'success' : 'error') . "'>";
                            echo $result . "</p>";
                        }
                    }
                    
                    echo "<p>Tổng kết: $uploadCount thành công, $errorCount lỗi</p>";
                }
            }
            ?>
        </div>
    </div>

    <script>
        // Xử lý chuyển tab
        document.querySelectorAll('.tab-button').forEach(button => {
            button.addEventListener('click', function() {
                // Xóa active class từ tất cả các buttons và sections
                document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
                document.querySelectorAll('.content-section').forEach(section => section.classList.remove('active'));
                
                // Thêm active class cho button được click và section tương ứng
                this.classList.add('active');
                document.getElementById(this.dataset.type + '-section').classList.add('active');
            });
        });
    </script>
</body>
</html> 
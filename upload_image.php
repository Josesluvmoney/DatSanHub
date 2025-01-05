<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config.php';

function uploadImage($conn, $id, $imagePath, $type = 'court') {
    error_log("Attempting to upload image: $imagePath");
    error_log("ID: $id, Type: $type");
    
    if (!file_exists($imagePath)) {
        error_log("File không tồn tại: $imagePath");
        return "File không tồn tại: $imagePath";
    }

    $imageData = file_get_contents($imagePath);
    if (!$imageData) {
        error_log("Không thể đọc file: $imagePath");
        return "Không thể đọc file: $imagePath";
    }

    // Kiểm tra xem ID có tồn tại trong database không
    if ($type == 'court') {
        $checkSql = "SELECT COUNT(*) as count FROM tbl_san WHERE id_San = ?";
    } else {
        $checkSql = "SELECT COUNT(*) as count FROM tbl_dungcu WHERE id_DungCu = ?";
    }
    
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("i", $id);
    $checkStmt->execute();
    $result = $checkStmt->get_result();
    $row = $result->fetch_assoc();
    
    if ($row['count'] == 0) {
        error_log("Không tìm thấy " . ($type == 'court' ? 'sân' : 'dụng cụ') . " với ID: $id");
        return "Không tìm thấy " . ($type == 'court' ? 'sân' : 'dụng cụ') . " với ID: $id";
    }

    if ($type == 'court') {
        $stmt = $conn->prepare("UPDATE tbl_san SET Image = ? WHERE id_San = ?");
    } else {
        $stmt = $conn->prepare("UPDATE tbl_dungcu SET Image = ? WHERE id_DungCu = ?");
    }

    if (!$stmt) {
        error_log("Lỗi prepare statement: " . $conn->error);
        return "Lỗi prepare statement: " . $conn->error;
    }

    $stmt->bind_param("bi", $null, $id);
    $stmt->send_long_data(0, $imageData);
    $result = $stmt->execute();
    
    if($result) {
        $message = "Upload ảnh thành công cho " . ($type == 'court' ? 'sân' : 'dụng cụ') . " ID: $id";
        error_log($message);
    } else {
        $message = "Lỗi upload ảnh: " . $stmt->error;
        error_log($message);
    }
    $stmt->close();
    return $message;
}

// Đường dẫn tới thư mục chứa ảnh
$imageFolder = __DIR__ . "/images/";

// Hàm để lấy danh sách ID theo type
function getIdsByType($conn, $type, $isEquipment = false) {
    $table = $isEquipment ? 'tbl_dungcu' : 'tbl_san';
    $idField = $isEquipment ? 'id_DungCu' : 'id_San';
    
    $sql = "SELECT $idField as id FROM $table WHERE Type = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $type);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $ids = [];
    while ($row = $result->fetch_assoc()) {
        $ids[] = $row['id'];
    }
    return $ids;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload Ảnh</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }

        .upload-section {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        h2 {
            color: #004d40;
            margin-bottom: 25px;
            text-align: center;
        }

        .tab-buttons {
            display: flex;
            gap: 10px;
            margin-bottom: 25px;
            justify-content: center;
        }

        .tab-button {
            padding: 12px 24px;
            border: none;
            border-radius: 5px;
            background: #e0e0e0;
            cursor: pointer;
            font-size: 15px;
            transition: all 0.3s ease;
        }

        .tab-button:hover {
            background: #00796b;
            color: white;
        }

        .tab-button.active {
            background: #004d40;
            color: white;
        }

        .content-section {
            display: none;
            background: #fff;
            padding: 20px;
            border-radius: 5px;
        }

        .content-section.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .court-type, .equipment-type {
            margin: 20px 0;
        }

        .court-type h3, .equipment-type h3 {
            color: #00796b;
            margin-bottom: 15px;
        }

        label {
            display: block;
            padding: 10px;
            margin: 5px 0;
            cursor: pointer;
            transition: background-color 0.2s;
            border-radius: 4px;
        }

        label:hover {
            background-color: #e0f2f1;
        }

        input[type="checkbox"] {
            margin-right: 10px;
        }

        button[type="submit"] {
            width: 100%;
            padding: 12px;
            background: #00796b;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
            transition: background-color 0.3s;
        }

        button[type="submit"]:hover {
            background: #004d40;
        }

        .results {
            margin-top: 20px;
            padding: 15px;
            border-radius: 5px;
        }

        .success {
            color: #2e7d32;
            background: #e8f5e9;
            padding: 10px;
            border-radius: 4px;
            margin: 5px 0;
        }

        .error {
            color: #c62828;
            background: #ffebee;
            padding: 10px;
            border-radius: 4px;
            margin: 5px 0;
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
            if (isset($_POST['upload']) && isset($_POST['types'])) {
                $selectedTypes = $_POST['types'];
                $uploadType = $_POST['upload'];
                $isEquipment = ($uploadType === 'equipment');
                
                // Thêm script để giữ tab hiện tại
                echo "<script>
                    window.onload = function() {
                        document.querySelector('[data-type=\"{$uploadType}\"]').click();
                    }
                </script>";
                
                foreach ($selectedTypes as $type) {
                    $ids = getIdsByType($conn, $type, $isEquipment);
                    foreach ($ids as $id) {
                        // Tạo đường dẫn ảnh dựa theo type và id
                        $imagePath = $imageFolder . ($isEquipment ? 'equipment/' : 'courts/') . 
                                    $type . $id . '.jpg';
                        
                        if (file_exists($imagePath)) {
                            $result = uploadImage($conn, $id, $imagePath, $uploadType);
                            echo "<p class='" . (strpos($result, 'thành công') !== false ? 'success' : 'error') . "'>";
                            echo $result . "</p>";
                        } else {
                            echo "<p class='error'>Không tìm thấy ảnh cho " . 
                                 ($isEquipment ? 'dụng cụ' : 'sân') . 
                                 " $type với ID: $id (Đường dẫn: $imagePath)</p>";
                        }
                    }
                }
            }
            ?>
        </div>
    </div>

    <script>
        // Lưu trạng thái tab đang chọn vào localStorage
        document.querySelectorAll('.tab-button').forEach(button => {
            button.addEventListener('click', function() {
                document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
                document.querySelectorAll('.content-section').forEach(section => section.classList.remove('active'));
                
                this.classList.add('active');
                document.getElementById(this.dataset.type + '-section').classList.add('active');
                
                // Lưu loại tab đang chọn
                localStorage.setItem('activeTab', this.dataset.type);
            });
        });

        // Khôi phục tab đã chọn khi tải lại trang
        window.onload = function() {
            const activeTab = localStorage.getItem('activeTab');
            if (activeTab) {
                document.querySelector(`[data-type="${activeTab}"]`).click();
            }
        }
    </script>
</body>
</html> 

<?php
session_start();
require_once 'config.php';

// Hàm lấy danh sách dụng cụ theo loại
function getEquipmentByType($conn, $type) {
    $sql = "SELECT id_DungCu, Name, Type, Description, Price, RentPrice, Deposit, 
            Quantity, ForRent, ForSale, Status,
            CASE 
                WHEN Image IS NOT NULL THEN CONCAT('data:image/jpeg;base64,', TO_BASE64(Image))
                ELSE NULL 
            END as ImageData 
            FROM tbl_dungcu WHERE Type = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $type);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Lấy danh sách dụng cụ cho từng loại
$footballEquipment = getEquipmentByType($conn, 'football');
$badmintonEquipment = getEquipmentByType($conn, 'badminton');
$tennisEquipment = getEquipmentByType($conn, 'tennis');
$basketballEquipment = getEquipmentByType($conn, 'basketball');
$volleyballEquipment = getEquipmentByType($conn, 'volleyball');
$pickleballEquipment = getEquipmentByType($conn, 'pickleball');
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dụng Cụ Thể Thao</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
<?php 
        include 'assets/CSS/navbar.css';
        include 'assets/CSS/footer.css';
?>
        .container {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            gap: 20px;
            min-height: calc(100vh - 100px);
            height: 100%;
            position: relative;
            z-index: 1;
        }

        .sidebar {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 250px;
            padding: 20px;
            position: sticky;
            top: 20px;
            height: fit-content;
            align-self: flex-start;
        }

        .main-content {
            flex: 1;
            min-width: 800px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
            min-height: 800px;
            height: 100%;
            position: relative;
            overflow-x: hidden;
        }

        .equipment-type {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0;
            visibility: hidden;
            transform: translateX(100%);
            transition: transform 0.3s ease, opacity 0.3s ease;
            padding: 20px;
        }

        .equipment-type.active {
            opacity: 1;
            visibility: visible;
            position: relative;
            transform: translateX(0);
        }

        .equipment-grid {
            min-height: 600px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            align-content: flex-start;
            padding-bottom: 20px;
        }

        .equipment-card {
            width: 100%;
            height: auto;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            overflow: hidden;
            transition: all 0.3s ease;
            border: none;
        }

        .equipment-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.12);
        }

        .equipment-image-container {
            height: 220px;
            position: relative;
            width: 100%;
            overflow: hidden;
        }

        .equipment-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .image-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.3);
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: 1;
        }

        .equipment-card:hover .image-overlay {
            opacity: 1;
        }

        .equipment-card:hover .equipment-image {
            transform: scale(1.1);
        }

        .equipment-content {
            padding: 15px;
        }

        .equipment-header {
            font-size: 1.1em;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
        }

        .equipment-description {
            font-size: 0.9em;
            color: #666;
            margin-bottom: 15px;
            line-height: 1.4;
        }

        .equipment-info-grid {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 15px;
            background: #f8f9fa;
            padding: 12px;
            border-radius: 8px;
        }

        .equipment-quantity {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #2c3e50;
            font-size: 0.9em;
        }

        .equipment-quantity i {
            color: #00796b;
        }

        .equipment-price {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            font-size: 1em;
            color: #f57c00;
        }

        .equipment-price i {
            color: #f57c00;
        }

        .equipment-button-container {
            padding: 0 15px 15px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            height: 45px;
        }

        .buy-button, .rent-button {
            width: 100%;
            height: 100%;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            font-size: 0.95em;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .buy-button {
            background-color: #004d40;
            color: white;
        }

        .rent-button {
            background-color: white;
            color: #004d40;
            border: 1px solid #004d40;
        }

        .buy-button:hover {
            background-color: #00695c;
        }

        .rent-button:hover {
            background-color: #e0f2f1;
        }

        .button-placeholder {
            visibility: hidden;
        }

        .button-disabled {
            background-color: #e0e0e0 !important;
            color: #9e9e9e !important;
            cursor: not-allowed !important;
            border: none !important;
            opacity: 0.5;
        }

        @media (max-width: 1200px) {
            .equipment-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .equipment-grid {
                grid-template-columns: 1fr;
            }
            
            .equipment-card {
                height: auto;
                min-height: 450px;
            }
        }

        .category-list {
            list-style: none;
        }

        .category-item {
            padding: 10px;
            cursor: pointer;
            border-bottom: 1px solid #eee;
        }

        .category-item:hover {
            background-color: #f5f5f5;
        }

        .category-item.active {
            background-color: #e0f2f1;
            color: #00796b;
        }

        .category-header {
            font-weight: bold;
            color: #00796b;
            padding: 15px 10px 5px;
            border-bottom: 2px solid #00796b;
            margin-top: 10px;
        }

        .equipment-rent-status {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9em;
            padding: 8px 0;
        }

        /* Style cho trạng thái có thể thuê */
        .equipment-rent-status.available {
            color: #00796b;
        }

        /* Style cho trạng thái đang được sử dụng */
        .equipment-rent-status.unavailable {
            color: #f57c00;
        }

        /* Style cho trạng thái không cho thuê */
        .equipment-rent-status.not-for-rent {
            color: #9e9e9e;
            font-style: italic;
        }

        /* Style cho nút thuê bị vô hiệu hóa */
        .rent-button.not-for-rent {
            background-color: #f5f5f5 !important;
            color: #9e9e9e !important;
            border: 1px dashed #9e9e9e !important;
            cursor: not-allowed !important;
            opacity: 0.7;
        }

        .rent-button.not-for-rent:hover {
            background-color: #f5f5f5 !important;
            transform: none;
        }

        .equipment-rent-status i {
            font-size: 1em;
        }

        .button-disabled {
            background-color: rgba(158, 158, 158, 0.2) !important;
            color: #9e9e9e !important;
            cursor: not-allowed !important;
            border: none !important;
            position: relative;
        }

        .button-disabled::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.5);
            cursor: not-allowed;
        }

        /* Thêm CSS cho notification */
        .notification-popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            z-index: 9999;
            width: 90%;
            max-width: 400px;
            text-align: center;
            animation: fadeIn 0.3s ease;
        }

        .notification-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 9998;
            animation: fadeIn 0.2s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .notification-message {
            margin: 15px 0;
        }

        .notification-buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 20px;
        }

        .login-btn {
            background: #004d40;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .login-btn:hover {
            background: #00695c;
        }

        .warning-icon {
            color: #FF9800;
            font-size: 50px;
            margin-bottom: 15px;
        }

        .notification-title {
            color: #FF9800;
            font-size: 1.5em;
            margin-bottom: 10px;
        }

        .close-notification {
            position: absolute;
            top: 10px;
            right: 10px;
            background: none;
            border: none;
            font-size: 20px;
            color: #666;
            cursor: pointer;
            padding: 5px;
            line-height: 1;
            transition: color 0.3s ease;
        }

        .close-notification:hover {
            color: #333;
        }

        body.popup-open {
            overflow: hidden;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="container">
        <div class="sidebar">
            <h2>Danh mục</h2>
            <ul class="category-list">
                <li class="category-item active" data-type="football">Bóng đá</li>
                <li class="category-item" data-type="badminton">Cầu lông</li>
                <li class="category-item" data-type="tennis">Tennis</li>
                <li class="category-item" data-type="basketball">Bóng rổ</li>
                <li class="category-item" data-type="volleyball">Bóng chuyền</li>
                <li class="category-item" data-type="pickleball">Pickleball</li>
            </ul>
        </div>

        <div class="main-content">
            <div class="sections-container">
                <?php
                $types = ['football', 'badminton', 'tennis', 'basketball', 'volleyball', 'pickleball'];
                foreach ($types as $type) {
                    $varname = $type . "Equipment";
                    $equipment = $$varname;
                    ?>
                    <section class="equipment-type <?php echo $type === 'football' ? 'active' : ''; ?>" id="<?php echo $type; ?>">
                        <h2><?php echo ucfirst($type); ?></h2>
                        <div class="equipment-grid">
                            <?php if (!empty($equipment)): ?>
                                <?php foreach ($equipment as $item): ?>
                                    <div class="equipment-card">
                                        <div class="equipment-image-container">
                                            <?php if ($item['ImageData']): ?>
                                                <img src="<?php echo $item['ImageData']; ?>" 
                                                     alt="<?php echo htmlspecialchars($item['Name']); ?>" 
                                                     class="equipment-image">
                                                <div class="image-overlay"></div>
                                            <?php else: ?>
                                                <img src="images/equipment/default.jpg" 
                                                     alt="Default Image" 
                                                     class="equipment-image">
                                                <div class="image-overlay"></div>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <div class="equipment-content">
                                            <div class="equipment-header">
                                                <?php echo htmlspecialchars($item['Name']); ?>
                                            </div>
                                            
                                            <div class="equipment-description">
                                                <?php echo htmlspecialchars($item['Description']); ?>
                                            </div>
                                            
                                            <div class="equipment-info-grid">
                                                <div class="equipment-quantity">
                                                    <i class="fas fa-boxes"></i>
                                                    Còn <?php echo $item['Quantity']; ?> sản phẩm
                                                </div>
                                                <?php if ($item['ForSale']): ?>
                                                    <div class="equipment-price">
                                                        <i class="fas fa-tag"></i>
                                                        <?php echo number_format($item['Price'], 0, ',', '.'); ?> VNĐ
                                                    </div>
                                                <?php endif; ?>
                                                <div class="equipment-rent-status 
                                                    <?php 
                                                    if (!$item['ForRent']) {
                                                        echo 'not-for-rent';
                                                    } elseif (!$item['Status']) {
                                                        echo 'unavailable';
                                                    } else {
                                                        echo 'available';
                                                    }
                                                    ?>">
                                                    <i class="fas fa-clock"></i>
                                                    <?php 
                                                    if (!$item['ForRent']) {
                                                        echo 'Không cho thuê';
                                                    } elseif (!$item['Status']) {
                                                        echo 'Đang được sử dụng';
                                                    } else {
                                                        echo 'Có thể thuê';
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="equipment-button-container">
                                            <?php if ($item['ForSale']): ?>
                                                <button class="buy-button" onclick="handleEquipment(<?php echo $item['id_DungCu']; ?>, 'buy')">
                                                    <i class="fas fa-shopping-cart"></i> Mua ngay
                                                </button>
                                            <?php else: ?>
                                                <div class="button-placeholder"></div>
                                            <?php endif; ?>

                                            <?php if ($item['ForRent']): ?>
                                                <button class="rent-button <?php echo $item['Status'] ? '' : 'button-disabled'; ?>" 
                                                        <?php echo $item['Status'] ? 'onclick="handleEquipment('.$item['id_DungCu'].', \'rent\')"' : 'disabled'; ?>>
                                                    <i class="fas fa-clock"></i> Thuê ngay
                                                </button>
                                            <?php else: ?>
                                                <button class="rent-button not-for-rent" disabled>
                                                    <i class="fas fa-ban"></i> Không cho thuê
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>Không có dụng cụ nào.</p>
                            <?php endif; ?>
                        </div>
                    </section>
                <?php } ?>
            </div>
        </div>
    </div>

    <div class="notification-overlay" id="notificationOverlay"></div>
    <div class="notification-popup" id="notificationPopup">
        <button class="close-notification" onclick="closePopup('notificationPopup')">
            <i class="fas fa-times"></i>
        </button>
        <div id="notificationMessage"></div>
    </div>

    <?php include 'footer.php'; ?>

    <script>
        function handleEquipment(id, type) {
            <?php if (!isset($_SESSION['logged_in'])): ?>
                showNotification(`
                    <div class="warning-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h3 class="notification-title">Yêu cầu đăng nhập</h3>
                    <p class="notification-message">Vui lòng đăng nhập để ${type === 'buy' ? 'mua' : 'thuê'} dụng cụ</p>
                    <div class="notification-buttons">
                        <button class="login-btn" onclick="closePopup('notificationPopup'); showPopup('loginPopup');">
                            <i class="fas fa-sign-in-alt"></i> Đăng nhập ngay
                        </button>
                    </div>
                `);
            <?php else: ?>
                if (type === 'buy') {
                    window.location.href = `muadungcu.php?equipment_id=${id}`;
                } else {
                    window.location.href = `thuedungcu.php?equipment_id=${id}`;
                }
            <?php endif; ?>
        }
    </script>
</body>
</html>

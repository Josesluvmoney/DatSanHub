<?php
session_start();
require_once 'config.php';
// lấy danh sách dụng cụ theo loại
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
        include 'assets/CSS/dungcuthethao.css';
?>
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
                                            <?php if ($item['ForSale'] && $item['Quantity'] > 0): ?>
                                                <button class="buy-button" onclick="handleEquipment(
                                                    <?php echo $item['id_DungCu']; ?>, 
                                                    'buy',
                                                    '<?php echo htmlspecialchars($item['Name']); ?>', 
                                                    <?php echo $item['Price']; ?>,
                                                    '<?php echo $item['ImageData'] ? 'has_image' : 'default'; ?>'
                                                )">
                                                    <i class="fas fa-shopping-cart"></i> Mua ngay
                                                </button>
                                            <?php else: ?>
                                                <button class="buy-button button-disabled" disabled>
                                                    <i class="fas fa-shopping-cart"></i> Hết hàng
                                                </button>
                                            <?php endif; ?>
                                            <?php if ($item['ForRent'] && $item['Status'] && $item['Quantity'] > 0): ?>
                                                <button class="rent-button" onclick="handleEquipment(
                                                    <?php echo $item['id_DungCu']; ?>, 
                                                    'rent',
                                                    '<?php echo htmlspecialchars($item['Name']); ?>', 
                                                    <?php echo $item['RentPrice']; ?>,
                                                    '<?php echo $item['ImageData'] ? 'has_image' : 'default'; ?>'
                                                )">
                                                    <i class="fas fa-clock"></i> Thuê ngay
                                                </button>
                                            <?php else: ?>
                                                <button class="rent-button button-disabled" disabled>
                                                    <i class="fas fa-ban"></i> 
                                                    <?php 
                                                    if (!$item['ForRent']) {
                                                        echo 'Không cho thuê';
                                                    } elseif (!$item['Status']) {
                                                        echo 'Đang được thuê';
                                                    } else {
                                                        echo 'Hết hàng';
                                                    }
                                                    ?>
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
<?php include 'assets/js/dungcuthethao.js'; ?>
    </script>
</body>
</html>

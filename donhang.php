<?php
include 'config.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: trangchu.php");
    exit();
}

$where_clause = "id_TK = ?";
$params = array($_SESSION['user_id']);
$types = "i";

if (isset($_GET['from_date']) && !empty($_GET['from_date']) && isset($_GET['to_date']) && !empty($_GET['to_date'])) {
    $from_date = $_GET['from_date'];
    $to_date = $_GET['to_date'];
    $where_clause .= " AND DATE(created_at) BETWEEN ? AND ?";
    $params[] = $from_date;
    $params[] = $to_date;
    $types .= "ss";
}

// Lấy danh sách đơn hàng với điều kiện tìm kiếm
$sql = "SELECT dh.*, 
        CASE 
            WHEN dh.type = 'equipment' THEN dc.Image
            WHEN dh.type = 'court' THEN s.Image
        END AS Image,
        dh.booking_date,
        dh.start_time,
        dh.end_time,
        dh.duration
        FROM tbl_donhang dh
        LEFT JOIN tbl_dungcu dc ON dh.type = 'equipment' AND dh.id_DungCu = dc.id_DungCu
        LEFT JOIN tbl_san s ON dh.type = 'court' AND dh.id_San = s.id_San
        WHERE " . $where_clause . " ORDER BY dh.created_at DESC";
$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đơn Hàng - DatSanHub</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
<?php include 'assets/CSS/donhang.css'; ?>
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container">
        <div class="search-form">
            <form method="GET" action="">
                <div class="date-range">
                    <div class="date-input">
                        <span class="date-label">Từ ngày:</span>
                        <input type="date" name="from_date" 
                               value="<?php echo isset($_GET['from_date']) ? $_GET['from_date'] : ''; ?>"
                               required>
                    </div>
                    <div class="date-input">
                        <span class="date-label">Đến ngày:</span>
                        <input type="date" name="to_date" 
                               value="<?php echo isset($_GET['to_date']) ? $_GET['to_date'] : ''; ?>"
                               required>
                    </div>
                </div>
                <button type="submit">Tìm kiếm</button>
                <?php if (isset($_GET['from_date']) || isset($_GET['to_date'])) { ?>
                    <a href="donhang.php" class="shop-now-btn reset-btn">Đặt lại</a>
                <?php } ?>
            </form>
            </div>

        <?php if ($result->num_rows > 0) { ?>
            <div class="order-list">
                <?php while ($order = $result->fetch_assoc()) { ?>
            <div class="order-card">
                <div class="order-header">
                    <span class="order-id">Đơn hàng #<?php echo $order['id_DonHang']; ?></span>
                    <span class="order-status status-<?php echo $order['order_status'] ? strtolower($order['order_status']) : 'pending'; ?>">
                        <?php 
                        if ($order['order_status']) {
                            switch($order['order_status']) {
                                case 'pending': echo 'Đang xử lý'; break;
                                case 'success': echo 'Đã hoàn thành'; break;
                                case 'cancelled': echo 'Đã hủy'; break;
                                default: echo 'Đang xử lý';
                            }
                        } else {
                            echo 'Đang xử lý';
                        }
                        ?>
                    </span>
                </div>
                <div class="order-items">
                    <div class="order-item">
                        <div class="item-details">
                            <div class="item-info">
                                        <h4><?php echo htmlspecialchars($order['name']); ?></h4>
                                        <p>Loại: <?php echo $order['type'] == 'equipment' ? 
                                            ($order['action_type'] == 'buy' ? 'Mua dụng cụ' : 'Thuê dụng cụ') : 
                                            'Thuê sân'; ?></p>
                                        <p>Số lượng: <?php echo $order['quantity']; ?></p>
                                        <p>Ngày đặt: <?php echo date('d/m/Y', strtotime($order['created_at'])); ?></p>
                                        <p>Phương thức thanh toán: <?php echo $order['payment_method'] == 'cod' ? 'Thanh toán khi nhận hàng' : 'Chuyển khoản'; ?></p>
                            </div>
                        </div>
                                <div class="item-price"><?php echo number_format($order['price'], 0, ',', '.'); ?>đ</div>
                            </div>
                        </div>
                        <div class="order-total">
                            Tổng tiền: <?php echo number_format($order['total_price'], 0, ',', '.'); ?>đ
                        </div>
                    </div>
                <?php if ($order['type'] === 'court' && 
                          isset($order['booking_date']) && 
                          isset($order['start_time']) && 
                          isset($order['duration'])): ?>
                    <div class="booking-details">
                        <p>Ngày đặt: <?php echo date('d/m/Y', strtotime($order['booking_date'])); ?></p>
                        <p>Giờ bắt đầu: <?php echo date('H:i', strtotime($order['start_time'])); ?></p>
                        <?php if (isset($order['end_time'])): ?>
                            <p>Giờ kết thúc: <?php echo date('H:i', strtotime($order['end_time'])); ?></p>
                        <?php endif; ?>
                        <p>Thời gian: <?php echo $order['duration']; ?> giờ</p>
                        <p>Trạng thái thanh toán: <?php 
                            switch($order['payment_status']) {
                                case 'pending': echo 'Chờ thanh toán'; break;
                                case 'paid': echo 'Đã thanh toán'; break;
                                case 'cancelled': echo 'Đã hủy'; break;
                            }
                        ?></p>
                    </div>
                <?php endif; ?>
                <?php } ?>
            </div>
        <?php } else { ?>
            <div class="no-orders">
                <?php if (isset($_GET['from_date']) && isset($_GET['to_date'])) { ?>
                    <h2>Không tìm thấy đơn hàng nào từ ngày 
                        <?php echo date('d/m/Y', strtotime($_GET['from_date'])); ?> 
                        đến ngày 
                        <?php echo date('d/m/Y', strtotime($_GET['to_date'])); ?>
                    </h2>
                    <a href="donhang.php" class="shop-now-btn">Xem tất cả đơn hàng</a>
                <?php } else { ?>
                    <h2>Bạn chưa có đơn hàng nào</h2>
                    <a href="danhsachsanbai.php" class="shop-now-btn">Mua sắm, đặt sân ngay</a>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html> 

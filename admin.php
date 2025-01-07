<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['logged_in']) || !$_SESSION['is_admin']) {
    header('Location: login.php');
    exit;
}

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

// Đường dẫn tới thư mục chứa ảnh
$imageFolder = __DIR__ . "/images/";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Xử lý tìm kiếm đơn hàng theo số điện thoại
$orders = [];
$search_phone = '';
$search_date = '';
$search_month = '';

if (isset($_GET['search'])) {
    $search_phone = isset($_GET['search_phone']) ? $_GET['search_phone'] : '';
    $search_date = isset($_GET['search_date']) ? $_GET['search_date'] : '';
    $search_month = isset($_GET['search_month']) ? $_GET['search_month'] : '';

    $sql = "SELECT dh.*, u.Phone, u.Fullname 
            FROM tbl_donhang dh 
            JOIN tbl_user u ON dh.id_TK = u.id_TK 
            WHERE 1=1";
    $params = [];
    $types = "";

    if (!empty($search_phone)) {
        $sql .= " AND u.Phone LIKE ?";
        $search_term = "%$search_phone%";
        $params[] = $search_term;
        $types .= "s";
    }

    if (!empty($search_date)) {
        $sql .= " AND DATE(dh.created_at) = ?";
        $params[] = $search_date;
        $types .= "s";
    }

    if (!empty($search_month)) {
        $sql .= " AND DATE_FORMAT(dh.created_at, '%Y-%m') = ?";
        $params[] = $search_month;
        $types .= "s";
    }

    $sql .= " ORDER BY dh.created_at DESC";
    
    $stmt = $conn->prepare($sql);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $orders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// Lấy thống kê doanh thu
function getRevenue($conn, $period = 'day') {
    $sql = "SELECT 
                SUM(total_price) as total,
                COUNT(*) as order_count
            FROM tbl_donhang 
            WHERE payment_status = 'paid'";
    
    switch ($period) {
        case 'day':
            $sql .= " AND DATE(created_at) = CURDATE()";
            break;
        case 'month':
            $sql .= " AND MONTH(created_at) = MONTH(CURDATE()) 
                     AND YEAR(created_at) = YEAR(CURDATE())";
            break;
        case 'year':
            $sql .= " AND YEAR(created_at) = YEAR(CURDATE())";
            break;
    }
    
    $result = $conn->query($sql);
    return $result->fetch_assoc();
}

$daily_revenue = getRevenue($conn, 'day');
$monthly_revenue = getRevenue($conn, 'month');
$yearly_revenue = getRevenue($conn, 'year');

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Quản Trị</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
<?php 
include 'assets/CSS/navbar.css';
include 'assets/CSS/footer.css';
include 'assets/CSS/admin.css';
?>
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="admin-container">
        <h1>Trang Quản Trị</h1>

        <!-- Thống kê doanh thu -->
        <div class="revenue-stats">
            <div class="stat-card">
                <h3>Doanh Thu Hôm Nay</h3>
                <p class="amount"><?php echo number_format($daily_revenue['total'] ?? 0); ?> VNĐ</p>
                <p class="order-count"><?php echo $daily_revenue['order_count'] ?? 0; ?> đơn hàng</p>
            </div>
            <div class="stat-card">
                <h3>Doanh Thu Tháng Này</h3>
                <p class="amount"><?php echo number_format($monthly_revenue['total'] ?? 0); ?> VNĐ</p>
                <p class="order-count"><?php echo $monthly_revenue['order_count'] ?? 0; ?> đơn hàng</p>
            </div>
            <div class="stat-card">
                <h3>Doanh Thu Năm Nay</h3>
                <p class="amount"><?php echo number_format($yearly_revenue['total'] ?? 0); ?> VNĐ</p>
                <p class="order-count"><?php echo $yearly_revenue['order_count'] ?? 0; ?> đơn hàng</p>
            </div>
        </div>
        <!-- Tìm kiếm đơn hàng -->
        <div class="search-section">
            <h2>Tìm Kiếm Đơn Hàng</h2>
            <form method="GET" class="search-form">
                <div class="search-inputs">
                    <div class="input-group">
                        <label for="search_phone">Số điện thoại:</label>
                        <input type="text" id="search_phone" name="search_phone" 
                               placeholder="Nhập số điện thoại..." 
                               value="<?php echo htmlspecialchars($search_phone); ?>">
                    </div>
                    
                    <div class="input-group">
                        <label for="search_date">Theo ngày:</label>
                        <input type="date" id="search_date" name="search_date" 
                               value="<?php echo htmlspecialchars($search_date); ?>">
                    </div>
                    
                    <div class="input-group">
                        <label for="search_month">Theo tháng:</label>
                        <input type="month" id="search_month" name="search_month" 
                               value="<?php echo htmlspecialchars($search_month); ?>">
                    </div>
                </div>

                <div class="button-group">
                    <button type="submit" name="search" value="1">
                        <i class="fas fa-search"></i> Tìm kiếm
                    </button>
                    <button type="reset" class="reset-btn" onclick="resetSearch()">
                        <i class="fas fa-undo"></i> Đặt lại
                    </button>
                </div>
            </form>

            <?php if (!empty($orders)): ?>
                <div class="orders-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Mã ĐH</th>
                                <th>Khách hàng</th>
                                <th>SĐT</th>
                                <th>Sản phẩm</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                                <th>Ngày đặt</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td>#<?php echo $order['id_DonHang']; ?></td>
                                    <td><?php echo htmlspecialchars($order['Fullname']); ?></td>
                                    <td><?php echo htmlspecialchars($order['Phone']); ?></td>
                                    <td><?php echo htmlspecialchars($order['name']); ?></td>
                                    <td><?php echo number_format($order['total_price']); ?> VNĐ</td>
                                    <td>
                                        <span class="status-<?php echo $order['payment_status']; ?>">
                                            <?php
                                            switch($order['payment_status']) {
                                                case 'pending': echo 'Chờ thanh toán'; break;
                                                case 'paid': echo 'Đã thanh toán'; break;
                                                case 'cancelled': echo 'Đã hủy'; break;
                                            }
                                            ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php elseif (isset($_GET['search'])): ?>
                <p class="no-results">Không tìm thấy đơn hàng nào.</p>
            <?php endif; ?>
        </div>
        <div class="upload-section">
            <h2>Quản Lý Hình Ảnh</h2>
            
            <div class="tab-buttons">
                <button class="tab-button active" data-type="court">Upload ảnh sân</button>
                <button class="tab-button" data-type="equipment">Upload ảnh dụng cụ</button>
            </div>

            <div class="content-section active" id="court-section">
                <form method="post">
                    <div class="court-type">
                        <h3>Chọn loại sân:</h3>
                        <label><input type="checkbox" name="types[]" value="badminton"> Sân cầu lông</label>
                        <label><input type="checkbox" name="types[]" value="football"> Sân bóng đá</label>
                        <label><input type="checkbox" name="types[]" value="tennis"> Sân tennis</label>
                        <label><input type="checkbox" name="types[]" value="pickleball"> Sân pickleball</label>
                        <label><input type="checkbox" name="types[]" value="volleyball"> Sân bóng chuyền</label>
                        <label><input type="checkbox" name="types[]" value="basketball"> Sân bóng rổ</label>
                    </div>
                    <button type="submit" name="upload" value="court">Upload ảnh cho các sân đã chọn</button>
                </form>
            </div>

            <div class="content-section" id="equipment-section">
                <form method="post">
                    <div class="equipment-type">
                        <h3>Chọn loại dụng cụ:</h3>
                        <label><input type="checkbox" name="types[]" value="football"> Dụng cụ bóng đá</label>
                        <label><input type="checkbox" name="types[]" value="badminton"> Dụng cụ cầu lông</label>
                        <label><input type="checkbox" name="types[]" value="tennis"> Dụng cụ tennis</label>
                        <label><input type="checkbox" name="types[]" value="pickleball"> Dụng cụ pickleball</label>
                        <label><input type="checkbox" name="types[]" value="volleyball"> Dụng cụ bóng chuyền</label>
                        <label><input type="checkbox" name="types[]" value="basketball"> Dụng cụ bóng rổ</label>
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
                    
                    foreach ($selectedTypes as $type) {
                        $ids = getIdsByType($conn, $type, $isEquipment);
                        foreach ($ids as $id) {
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
    </div>
    <?php include 'footer.php'; ?>
    <script>
        document.querySelectorAll('.tab-button').forEach(button => {
            button.addEventListener('click', function() {
                document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
                document.querySelectorAll('.content-section').forEach(section => section.classList.remove('active'));
                
                this.classList.add('active');
                document.getElementById(this.dataset.type + '-section').classList.add('active');
                
                localStorage.setItem('activeTab', this.dataset.type);
            });
        });

        window.onload = function() {
            const activeTab = localStorage.getItem('activeTab');
            if (activeTab) {
                document.querySelector(`[data-type="${activeTab}"]`).click();
            }
        }

        function resetSearch() {
            document.getElementById('search_phone').value = '';
            document.getElementById('search_date').value = '';
            document.getElementById('search_month').value = '';
            window.location.href = 'admin.php';
        }

        // Đảm bảo chỉ một trong hai trường ngày hoặc tháng được chọn
        document.getElementById('search_date').addEventListener('change', function() {
            if(this.value) document.getElementById('search_month').value = '';
        });

        document.getElementById('search_month').addEventListener('change', function() {
            if(this.value) document.getElementById('search_date').value = '';
        });
    </script>
</body>
</html> 
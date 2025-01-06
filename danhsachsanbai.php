<?php
session_start();
require_once 'config.php';
// Lấy danh sách sân theo từng loại
function getCourtsByType($conn, $type) {
    $sql = "SELECT id_San, Name, Description, Price, Status, Opening_time, Closing_time, 
            CASE 
                WHEN Image IS NOT NULL THEN CONCAT('data:image/jpeg;base64,', TO_BASE64(Image))
                ELSE NULL 
            END as ImageData 
            FROM tbl_san WHERE Type = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $type);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}
// Lấy danh sách sân cầu lông
$badmintonCourts = getCourtsByType($conn, 'badminton');
$footballCourts = getCourtsByType($conn, 'football');
$tennisCourts = getCourtsByType($conn, 'tennis');
$pickleballCourts = getCourtsByType($conn, 'pickleball');
$volleyballCourts = getCourtsByType($conn, 'volleyball');
$basketballCourts = getCourtsByType($conn, 'basketball');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Sân Bãi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
 <?php 
include 'assets/CSS/navbar.css';
include 'assets/CSS/footer.css';
include 'assets/CSS/danhsachsanbai.css';
?>
    </style>
</head>
<body>
<?php
    include 'navbar.php';
    ?>
    <div class="container">
        <div class="sidebar">
            <h1>Danh Sách Sân Bãi</h1>
            <div class="sport-select">
                <div class="sport-option" data-sport="football">
                    Bóng Đá <span><?php echo count($footballCourts); ?></span>
                </div>
                <div class="sport-option" data-sport="tennis">
                    Tennis <span><?php echo count($tennisCourts); ?></span>
                </div>
                <div class="sport-option" data-sport="pickleball">
                    Pickleball <span><?php echo count($pickleballCourts); ?></span>
                </div>
                <div class="sport-option" data-sport="volleyball">
                    Bóng Chuyền <span><?php echo count($volleyballCourts); ?></span>
                </div>
                <div class="sport-option" data-sport="basketball">
                    Bóng Rổ <span><?php echo count($basketballCourts); ?></span>
                </div>
                <div class="sport-option" data-sport="badminton">
                    Cầu Lông <span><?php echo count($badmintonCourts); ?></span>
                </div>
            </div>
        </div>
        <div class="main-content">
            <div class="sections-container">
            <section class="sport-type active" id="football">
                <h2>Bóng Đá</h2>
                <div class="courts">
                        <?php if (!empty($footballCourts)): ?>
                            <?php foreach ($footballCourts as $court): ?>
                                <div class="court" data-id="<?php echo $court['id_San']; ?>">
                                    <div class="court-content">
                                        <div class="court-image-container">
                                            <?php if ($court['ImageData']): ?>
                                                <img src="<?php echo $court['ImageData']; ?>" 
                                                     alt="<?php echo htmlspecialchars($court['Name']); ?>" 
                                                     class="court-image">
                                                <div class="image-overlay"></div>
                                            <?php else: ?>
                                                <img src="assets/images/courts/default.jpg" 
                                                     alt="Default Image" 
                                                     class="court-image">
                                                <div class="image-overlay"></div>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <div class="court-header">
                                            <?php echo htmlspecialchars($court['Name']); ?>
                                        </div>
                                        
                                        <div class="court-description">
                                            <?php echo htmlspecialchars($court['Description']); ?>
                                        </div>
                                        
                                        <div class="court-info-grid">
                                            <div class="court-time">
                                                <i class="far fa-clock"></i> 
                                                <?php echo htmlspecialchars($court['Opening_time']); ?> - 
                                                <?php echo htmlspecialchars($court['Closing_time']); ?>
                                            </div>
                                            <div class="court-price">
                                                <i class="fas fa-tag"></i>
                                                <?php echo number_format($court['Price'], 0, ',', '.'); ?> VNĐ/giờ
                                            </div>
                                        </div>
                                    </div>

                                    <div class="court-button-container">
                                        <?php if ($court['Status']): ?>
                                            <button class="book-button" onclick="handleBooking(
                                                <?php echo $court['id_San']; ?>,
                                                '<?php echo htmlspecialchars($court['Name']); ?>',
                                                <?php echo $court['Price']; ?>,
                                                '<?php echo $court['ImageData'] ? 'has_image' : 'default'; ?>',
                                                '<?php echo $court['Opening_time']; ?>',
                                                '<?php echo $court['Closing_time']; ?>'
                                            )">
                                                <i class="fas fa-calendar-plus"></i> Đặt sân
                                            </button>
                                        <?php else: ?>
                                            <button class="book-button" disabled style="background-color: #ccc;">
                                                <i class="fas fa-ban"></i> Đang được sử dụng
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Không có sân bóng đá nào.</p>
                        <?php endif; ?>
                </div>
            </section>
            <section class="sport-type" id="tennis">
                <h2>Tennis</h2>
                <div class="courts">
                        <?php if (!empty($tennisCourts)): ?>
                            <?php foreach ($tennisCourts as $court): ?>
                                <div class="court" data-id="<?php echo $court['id_San']; ?>">
                                    <div class="court-content">
                                        <div class="court-image-container">
                                            <?php if ($court['ImageData']): ?>
                                                <img src="<?php echo $court['ImageData']; ?>" 
                                                     alt="<?php echo htmlspecialchars($court['Name']); ?>" 
                                                     class="court-image">
                                                <div class="image-overlay"></div>
                                            <?php else: ?>
                                                <img src="assets/images/courts/default.jpg" 
                                                     alt="Default Image" 
                                                     class="court-image">
                                                <div class="image-overlay"></div>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <div class="court-header">
                                            <?php echo htmlspecialchars($court['Name']); ?>
                                        </div>
                                        
                                        <div class="court-description">
                                            <?php echo htmlspecialchars($court['Description']); ?>
                                        </div>
                                        
                                        <div class="court-info-grid">
                                            <div class="court-time">
                                                <i class="far fa-clock"></i> 
                                                <?php echo htmlspecialchars($court['Opening_time']); ?> - 
                                                <?php echo htmlspecialchars($court['Closing_time']); ?>
                                            </div>
                                            <div class="court-price">
                                                <i class="fas fa-tag"></i>
                                                <?php echo number_format($court['Price'], 0, ',', '.'); ?> VNĐ/giờ
                                            </div>
                                        </div>
                                    </div>

                                    <div class="court-button-container">
                                        <?php if ($court['Status']): ?>
                                            <button class="book-button" onclick="handleBooking(
                                                <?php echo $court['id_San']; ?>,
                                                '<?php echo htmlspecialchars($court['Name']); ?>',
                                                <?php echo $court['Price']; ?>,
                                                '<?php echo $court['ImageData'] ? 'has_image' : 'default'; ?>',
                                                '<?php echo $court['Opening_time']; ?>',
                                                '<?php echo $court['Closing_time']; ?>'
                                            )">
                                                <i class="fas fa-calendar-plus"></i> Đặt sân
                                            </button>
                                        <?php else: ?>
                                            <button class="book-button" disabled style="background-color: #ccc;">
                                                <i class="fas fa-ban"></i> Đang được sử dụng
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Không có sân tennis nào.</p>
                        <?php endif; ?>
                </div>
            </section>
            <section class="sport-type" id="pickleball">
                <h2>Pickleball</h2>
                <div class="courts">
                        <?php if (!empty($pickleballCourts)): ?>
                            <?php foreach ($pickleballCourts as $court): ?>
                                <div class="court" data-id="<?php echo $court['id_San']; ?>">
                                    <div class="court-content">
                                        <div class="court-image-container">
                                            <?php if ($court['ImageData']): ?>
                                                <img src="<?php echo $court['ImageData']; ?>" 
                                                     alt="<?php echo htmlspecialchars($court['Name']); ?>" 
                                                     class="court-image">
                                                <div class="image-overlay"></div>
                                            <?php else: ?>
                                                <img src="assets/images/courts/default.jpg" 
                                                     alt="Default Image" 
                                                     class="court-image">
                                                <div class="image-overlay"></div>
                                            <?php endif; ?>
                                        </div>        
                                        <div class="court-header">
                                            <?php echo htmlspecialchars($court['Name']); ?>
                                        </div>
                                        
                                        <div class="court-description">
                                            <?php echo htmlspecialchars($court['Description']); ?>
                                        </div>
                                        
                                        <div class="court-info-grid">
                                            <div class="court-time">
                                                <i class="far fa-clock"></i> 
                                                <?php echo htmlspecialchars($court['Opening_time']); ?> - 
                                                <?php echo htmlspecialchars($court['Closing_time']); ?>
                                            </div>
                                            <div class="court-price">
                                                <i class="fas fa-tag"></i>
                                                <?php echo number_format($court['Price'], 0, ',', '.'); ?> VNĐ/giờ
                                            </div>
                                        </div>
                                    </div>
                                    <div class="court-button-container">
                                        <?php if ($court['Status']): ?>
                                            <button class="book-button" onclick="handleBooking(
                                                <?php echo $court['id_San']; ?>,
                                                '<?php echo htmlspecialchars($court['Name']); ?>',
                                                <?php echo $court['Price']; ?>,
                                                '<?php echo $court['ImageData'] ? 'has_image' : 'default'; ?>',
                                                '<?php echo $court['Opening_time']; ?>',
                                                '<?php echo $court['Closing_time']; ?>'
                                            )">
                                                <i class="fas fa-calendar-plus"></i> Đặt sân
                                            </button>
                                        <?php else: ?>
                                            <button class="book-button" disabled style="background-color: #ccc;">
                                                <i class="fas fa-ban"></i> Đang được sử dụng
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Không có sân pickleball nào.</p>
                        <?php endif; ?>
                </div>
            </section>
            <section class="sport-type" id="volleyball">
                <h2>Bóng Chuyền</h2>
                <div class="courts">
                        <?php if (!empty($volleyballCourts)): ?>
                            <?php foreach ($volleyballCourts as $court): ?>
                                <div class="court" data-id="<?php echo $court['id_San']; ?>">
                                    <div class="court-content">
                                        <div class="court-image-container">
                                            <?php if ($court['ImageData']): ?>
                                                <img src="<?php echo $court['ImageData']; ?>" 
                                                     alt="<?php echo htmlspecialchars($court['Name']); ?>" 
                                                     class="court-image">
                                                <div class="image-overlay"></div>
                                            <?php else: ?>
                                                <img src="assets/images/courts/default.jpg" 
                                                     alt="Default Image" 
                                                     class="court-image">
                                                <div class="image-overlay"></div>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <div class="court-header">
                                            <?php echo htmlspecialchars($court['Name']); ?>
                                        </div>
                                        
                                        <div class="court-description">
                                            <?php echo htmlspecialchars($court['Description']); ?>
                                        </div>
                                        <div class="court-info-grid">
                                            <div class="court-time">
                                                <i class="far fa-clock"></i> 
                                                <?php echo htmlspecialchars($court['Opening_time']); ?> - 
                                                <?php echo htmlspecialchars($court['Closing_time']); ?>
                                            </div>
                                            <div class="court-price">
                                                <i class="fas fa-tag"></i>
                                                <?php echo number_format($court['Price'], 0, ',', '.'); ?> VNĐ/giờ
                                            </div>
                                        </div>
                                    </div>

                                    <div class="court-button-container">
                                        <?php if ($court['Status']): ?>
                                            <button class="book-button" onclick="handleBooking(
                                                <?php echo $court['id_San']; ?>,
                                                '<?php echo htmlspecialchars($court['Name']); ?>',
                                                <?php echo $court['Price']; ?>,
                                                '<?php echo $court['ImageData'] ? 'has_image' : 'default'; ?>',
                                                '<?php echo $court['Opening_time']; ?>',
                                                '<?php echo $court['Closing_time']; ?>'
                                            )">
                                                <i class="fas fa-calendar-plus"></i> Đặt sân
                                            </button>
                                        <?php else: ?>
                                            <button class="book-button" disabled style="background-color: #ccc;">
                                                <i class="fas fa-ban"></i> Đang được sử dụng
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Không có sân bóng chuyền nào.</p>
                        <?php endif; ?>
                </div>
            </section>
            <section class="sport-type" id="basketball">
                <h2>Bóng Rổ</h2>
                <div class="courts">
                        <?php if (!empty($basketballCourts)): ?>
                            <?php foreach ($basketballCourts as $court): ?>
                                <div class="court" data-id="<?php echo $court['id_San']; ?>">
                                    <div class="court-content">
                                        <div class="court-image-container">
                                            <?php if ($court['ImageData']): ?>
                                                <img src="<?php echo $court['ImageData']; ?>" 
                                                     alt="<?php echo htmlspecialchars($court['Name']); ?>" 
                                                     class="court-image">
                                                <div class="image-overlay"></div>
                                            <?php else: ?>
                                                <img src="assets/images/courts/default.jpg" 
                                                     alt="Default Image" 
                                                     class="court-image">
                                                <div class="image-overlay"></div>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <div class="court-header">
                                            <?php echo htmlspecialchars($court['Name']); ?>
                                        </div>
                                        
                                        <div class="court-description">
                                            <?php echo htmlspecialchars($court['Description']); ?>
                                        </div>
                                        
                                        <div class="court-info-grid">
                                            <div class="court-time">
                                                <i class="far fa-clock"></i> 
                                                <?php echo htmlspecialchars($court['Opening_time']); ?> - 
                                                <?php echo htmlspecialchars($court['Closing_time']); ?>
                                            </div>
                                            <div class="court-price">
                                                <i class="fas fa-tag"></i>
                                                <?php echo number_format($court['Price'], 0, ',', '.'); ?> VNĐ/giờ
                                            </div>
                                        </div>
                                    </div>

                                    <div class="court-button-container">
                                        <?php if ($court['Status']): ?>
                                            <button class="book-button" onclick="handleBooking(
                                                <?php echo $court['id_San']; ?>,
                                                '<?php echo htmlspecialchars($court['Name']); ?>',
                                                <?php echo $court['Price']; ?>,
                                                '<?php echo $court['ImageData'] ? 'has_image' : 'default'; ?>',
                                                '<?php echo $court['Opening_time']; ?>',
                                                '<?php echo $court['Closing_time']; ?>'
                                            )">
                                                <i class="fas fa-calendar-plus"></i> Đặt sân
                                            </button>
                                        <?php else: ?>
                                            <button class="book-button" disabled style="background-color: #ccc;">
                                                <i class="fas fa-ban"></i> Đang được sử dụng
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Không có sân bóng rổ nào.</p>
                        <?php endif; ?>
                </div>
            </section>
            <section class="sport-type" id="badminton">
                <h2>Cầu Lông</h2>
                <div class="courts">
                        <?php if (!empty($badmintonCourts)): ?>
                            <?php foreach ($badmintonCourts as $court): ?>
                                <div class="court" data-id="<?php echo $court['id_San']; ?>">
                                    <div class="court-content">
                                        <div class="court-image-container">
                                            <?php if ($court['ImageData']): ?>
                                                <img src="<?php echo $court['ImageData']; ?>" 
                                                     alt="<?php echo htmlspecialchars($court['Name']); ?>" 
                                                     class="court-image">
                                                <div class="image-overlay"></div>
                                            <?php else: ?>
                                                <img src="assets/images/courts/default.jpg" 
                                                     alt="Default Image" 
                                                     class="court-image">
                                                <div class="image-overlay"></div>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <div class="court-header">
                                            <?php echo htmlspecialchars($court['Name']); ?>
                                        </div>
                                        
                                        <div class="court-description">
                                            <?php echo htmlspecialchars($court['Description']); ?>
                                        </div>
                                        
                                        <div class="court-info-grid">
                                            <div class="court-time">
                                                <i class="far fa-clock"></i> 
                                                <?php echo htmlspecialchars($court['Opening_time']); ?> - 
                                                <?php echo htmlspecialchars($court['Closing_time']); ?>
                                            </div>
                                            <div class="court-price">
                                                <i class="fas fa-tag"></i>
                                                <?php echo number_format($court['Price'], 0, ',', '.'); ?> VNĐ/giờ
                                            </div>
                                        </div>
                                    </div>

                                    <div class="court-button-container">
                                        <?php if ($court['Status']): ?>
                                            <button class="book-button" onclick="handleBooking(
                                                <?php echo $court['id_San']; ?>,
                                                '<?php echo htmlspecialchars($court['Name']); ?>',
                                                <?php echo $court['Price']; ?>,
                                                '<?php echo $court['ImageData'] ? 'has_image' : 'default'; ?>',
                                                '<?php echo $court['Opening_time']; ?>',
                                                '<?php echo $court['Closing_time']; ?>'
                                            )">
                                                <i class="fas fa-calendar-plus"></i> Đặt sân
                                            </button>
                                        <?php else: ?>
                                            <button class="book-button" disabled style="background-color: #ccc;">
                                                <i class="fas fa-ban"></i> Đang được sử dụng
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Không có sân cầu lông nào.</p>
                        <?php endif; ?>
                </div>
            </section>
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

    <!-- Thêm modal chọn giờ -->
    <div class="booking-modal" id="bookingModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Chọn giờ đặt sân</h3>
                <button class="close-modal" onclick="closeBookingModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="court-info-preview">
                    <h4 id="selectedCourtName"></h4>
                    <p id="selectedCourtPrice"></p>
                </div>
                <form id="bookingForm">
                    <div class="form-group">
                        <label>Ngày đặt:</label>
                        <input type="date" id="bookingDate" required 
                               min="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <div class="form-group">
                        <label>Giờ bắt đầu:</label>
                        <input type="time" id="startTime" required>
                    </div>
                    <div class="form-group">
                        <label>Số giờ thuê:</label>
                        <select id="duration" required>
                            <option value="1">1 giờ</option>
                            <option value="2">2 giờ</option>
                            <option value="3">3 giờ</option>
                            <option value="4">4 giờ</option>
                        </select>
                    </div>
                    <div class="total-preview">
                        <span>Tổng tiền:</span>
                        <span id="totalPrice">0 VNĐ</span>
                    </div>
                    <div class="button-group">
                        <button type="button" onclick="closeBookingModal()">Hủy</button>
                        <button type="submit">Xác nhận</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php include 'footer.php'; ?>
<script>
<?php include 'assets/js/danhsachsanbai.js'; ?>
</script>
</body>
</html>

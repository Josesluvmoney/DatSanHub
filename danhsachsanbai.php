<?php
session_start();
require_once 'config.php';
// Lấy danh sách sân theo từng loại
function getCourtsByType($conn, $type) {
    $sql = "SELECT id_San, Name, Description, Price, Status, Opening_hours, 
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
                                                <?php echo htmlspecialchars($court['Opening_hours']); ?>
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
                                                '<?php echo $court['ImageData']; ?>'
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
                                                <?php echo htmlspecialchars($court['Opening_hours']); ?>
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
                                                '<?php echo $court['ImageData']; ?>'
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
                                                <?php echo htmlspecialchars($court['Opening_hours']); ?>
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
                                                '<?php echo $court['ImageData']; ?>'
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
                                                <?php echo htmlspecialchars($court['Opening_hours']); ?>
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
                                                '<?php echo $court['ImageData']; ?>'
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
                                                <?php echo htmlspecialchars($court['Opening_hours']); ?>
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
                                                '<?php echo $court['ImageData']; ?>'
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
                                                <?php echo htmlspecialchars($court['Opening_hours']); ?>
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
                                                '<?php echo $court['ImageData']; ?>'
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
    <script>
        document.querySelectorAll('.sport-option').forEach(option => {
            option.addEventListener('click', function() {
                document.querySelectorAll('.sport-option').forEach(o => {
                    o.classList.remove('active');
                });
                
                this.classList.add('active');      
                const selectedSport = this.getAttribute('data-sport');
                const sections = document.querySelectorAll('.sport-type');
                
                sections.forEach(section => {
                    if (section.id === selectedSport) {
                        section.classList.add('active');
                    } else {
                        section.classList.remove('active');
                    }
                });
            });
        });
        function handleBooking(courtId, courtName, price, image) {
            <?php if (!isset($_SESSION['logged_in'])): ?>
                showNotification(`
                    <div class="warning-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h3>Yêu cầu đăng nhập</h3>
                    <p>Vui lòng đăng nhập để đặt sân</p>
                    <div class="notification-buttons">
                        <button class="login-btn" onclick="closePopup('notificationPopup'); showPopup('loginPopup');">
                            <i class="fas fa-sign-in-alt"></i> Đăng nhập ngay
                        </button>
                    </div>
                `);
            <?php else: ?>
                // Tạo form ẩn để submit
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'xulygiohang.php';
                
                const fields = {
                    'add_to_cart': '1',
                    'id': courtId,
                    'type': 'court',
                    'name': courtName,
                    'price': price,
                    'image': image
                };        
                for (const [key, value] of Object.entries(fields)) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = key;
                    input.value = value;
                    form.appendChild(input);
                }
                
                document.body.appendChild(form);
                form.submit();
            <?php endif; ?>
        }
        document.head.insertAdjacentHTML('beforeend', `
            <style>
                #notificationMessage .warning-icon {
                    color: #FF9800;
                    font-size: 50px;
                    margin-bottom: 15px;
                }
            </style>
        `);
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const sport = urlParams.get('sport');
            
            if (sport) {
                const sportOption = document.querySelector(`.sport-option[data-sport="${sport}"]`);
                if (sportOption) {
                    sportOption.click();
                }
            }
        });
    </script>
<?php
 include 'footer.php';
 ?>
</body>
</html>

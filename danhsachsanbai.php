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
        /* Reset CSS */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        .container {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            gap: 20px;
            min-height: calc(100vh - 100px);
            height: 100%;
            position: relative;
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

        .sidebar h1 {
            font-size: 20px;
            color: #004d40;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #004d40;
            text-align: left;
        }

        .sport-select {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sport-option {
            padding: 12px 15px;
            cursor: pointer;
            border-radius: 6px;
            margin-bottom: 5px;
            transition: all 0.3s ease;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #004d40;
            border: 1px solid #e0f2f1;
            font-size: 1em;
        }

        .sport-option:hover {
            background-color: #e0f2f1;
        }

        .sport-option.active {
            background-color: #004d40;
            color: white;
            border-color: #004d40;
        }

        .sport-option span {
            color: #f9a825;
            font-weight: 500;
        }

        .sport-option span {
            background-color: #e0f2f1;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 0.9em;
        }

        .sport-option.active span {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
        }

        .main-content {
            flex: 1;
            min-width: 800px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
            min-height: 900px;
            position: relative;
            overflow-x: hidden;
        }

        .sport-type {
            position: absolute;
            width: 100%;
            min-height: 900px;
            opacity: 0;
            visibility: hidden;
            display: none;
            padding: 20px;
        }

        .sport-type.active {
            opacity: 1;
            visibility: visible;
            display: block;
            position: relative;
        }

        .courts {
            min-height: 800px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            align-content: start;
            padding-bottom: 20px;
            padding-right: 10px;
            margin-bottom: 20px;
        }

        .courts::-webkit-scrollbar {
            width: 8px;
        }

        .courts::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .courts::-webkit-scrollbar-thumb {
            background: #004d40;
            border-radius: 4px;
        }

        .courts::-webkit-scrollbar-thumb:hover {
            background: #00695c;
        }

        .court {
            width: 100%;
            height: 400px;
            margin: 0;
            padding: 10px;
            background-color: #ffffff;
            border: 1px solid #b2dfdb;
            display: flex;
            flex-direction: column;
            gap: 5px;
            transition: transform 0.2s;
            position: relative;
            box-sizing: border-box;
        }

        .court:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .court-header {
            font-weight: bold;
            color: #004d40;
            font-size: 1em;
            margin: 0;
        }

        .court-info {
            font-size: 0.9em;
            color: #666;
        }

        .court-price {
            color: #f9a825;
            font-weight: bold;
        }

        .book-button {
            width: 100%;
            padding: 8px;
            border-radius: 0 0 8px 8px;
            margin: 0 -10px -10px -10px;
            width: calc(100% + 20px);
            background-color: #004d40;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 0.9em;
        }

        .book-button:hover {
            background-color: #00695c;
        }

        .book-button i {
            font-size: 16px;
        }

        .court-image {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-radius: 4px;
        }

        .court-image-container {
            position: relative;
            width: 100%;
            height: 200px;
            overflow: hidden;
            border-radius: 8px;
            margin-bottom: 5px;
            z-index: 1;
        }

        .court-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .court:hover .court-image {
            transform: scale(1.1);
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

        .court:hover .image-overlay {
            opacity: 1;
        }

        <?php 
        include 'assets/CSS/navbar.css';
        include 'assets/CSS/footer.css';
        ?>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            margin: 0;
        }

        .container {
            flex: 1;
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            gap: 20px;
        }

        footer {
            margin-top: auto;
        }

        .court-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .court-button-container {
            width: 100%;
            margin-top: auto;
            padding: 0;
        }

        .court-info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 5px;
            margin: 0;
        }

        .court-time, .court-price {
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 4px 8px;
            background-color: #f5f5f5;
            border-radius: 4px;
            font-size: 0.85em;
        }

        .court-time {
            color: #004d40;
        }

        .court-price {
            color: #f9a825;
            font-weight: bold;
            justify-content: flex-end;
        }

        .court-time i {
            color: #00796b;
        }

        .court-description {
            font-size: 0.85em;
            color: #666;
            height: 35px;
            margin: 0;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .sport-type {
            position: absolute;
            width: 100%;
            min-height: 900px;
            opacity: 0;
            visibility: hidden;
            display: none;
            padding: 20px;
        }

        .sport-type.active {
            opacity: 1;
            visibility: visible;
            display: block;
            position: relative;
        }

        .sidebar {
            position: sticky;
            top: 20px;
            height: fit-content;
            align-self: flex-start;
        }

        .sections-container {
            position: relative;
            min-height: 900px;
            height: 100%;
            width: 100%;
        }

        .navbar {
            position: relative;
            z-index: 9999;
        }

        .notification-popup {
            z-index: 9998;
        }

        .notification-overlay {
            z-index: 9997;
        }
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
                                            <button class="book-button" onclick="handleBooking(<?php echo $court['id_San']; ?>, '<?php echo htmlspecialchars($court['Name']); ?>')">
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
                                            <button class="book-button" onclick="handleBooking(<?php echo $court['id_San']; ?>, '<?php echo htmlspecialchars($court['Name']); ?>')">
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
                                            <button class="book-button" onclick="handleBooking(<?php echo $court['id_San']; ?>, '<?php echo htmlspecialchars($court['Name']); ?>')">
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
                                            <button class="book-button" onclick="handleBooking(<?php echo $court['id_San']; ?>, '<?php echo htmlspecialchars($court['Name']); ?>')">
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
                                            <button class="book-button" onclick="handleBooking(<?php echo $court['id_San']; ?>, '<?php echo htmlspecialchars($court['Name']); ?>')">
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
                                            <button class="book-button" onclick="handleBooking(<?php echo $court['id_San']; ?>, '<?php echo htmlspecialchars($court['Name']); ?>')">
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

        function handleBooking(courtId, courtName) {
            <?php if (!isset($_SESSION['logged_in'])): ?>
                showNotification(`
                    <div class="warning-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h3 style="color: #FF9800;">Yêu cầu đăng nhập</h3>
                    <p>Vui lòng đăng nhập để đặt sân</p>
                    <div class="notification-buttons">
                        <button class="login-btn" onclick="closePopup('notificationPopup'); showPopup('loginPopup');">
                            <i class="fas fa-sign-in-alt"></i> Đăng nhập ngay
                        </button>
                    </div>
                `);
            <?php else: ?>
                window.location.href = `datsan.php?court_id=${courtId}`;
            <?php endif; ?>
        }

        // Thêm style cho warning icon trong notification
        document.head.insertAdjacentHTML('beforeend', `
            <style>
                #notificationMessage .warning-icon {
                    color: #FF9800;
                    font-size: 50px;
                    margin-bottom: 15px;
                }
            </style>
        `);
    </script>
<?php
 include 'footer.php';
 ?>
</body>
</html>

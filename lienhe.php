<?php
session_start();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Liên hệ với DatSanHub để đặt sân thể thao, giải đáp thắc mắc hoặc hợp tác kinh doanh. Chúng tôi luôn sẵn sàng hỗ trợ bạn!">
    <title>Liên hệ - DatSanHub</title>
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4CAF50;
            --primary-dark: #388E3C;
            --text-dark: #333;
            --text-light: #666;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            background-color: #f5f5f5;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }
        h1 {
            text-align: center;
            color: #4CAF50;
        }
        .map {
            margin-bottom: 30px;
        }
        .content {
        display: flex;
        gap: 40px;
        padding: 20px 0;
    }
    .left-column,
    .right-column {
        flex: 1;
        width: 50%;
    }
        .contact-info, .working-hours, .contact-form {
            margin-bottom: 30px;
        }
        .contact-info h2, 
    .working-hours h2, 
    .contact-form h2 {
        color: var(--text-dark);
        border-bottom: 2px solid #4CAF50;
        padding-bottom: 10px;
        margin-bottom: 20px;
        font-size: 1.5em;
    }
        .contact-info p, .working-hours p {
            margin: 15px 0;
            font-size: 16px;
            line-height: 1.6;
        }
        .contact-info a {
        text-decoration: none;
        color: var(--primary-color);
    }

        #leaflet-map {
            width: 100%;
            height: 450px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .social-links a {
            margin-right: 15px;
            color: #4CAF50;
            text-decoration: none;
            font-size: 24px;
            transition: color 0.3s ease;
        }
        .social-links a:hover {
            color: #388E3C;
        }
        .form-description {
            margin-bottom: 20px;
            font-style: italic;
            color: #555;
        }
        .content {
            display: flex;
            gap: 30px;
            padding: 0;
            margin-top: 20px;
        }
        .map {
            margin-bottom: 20px;
        }
        .header-section {
            position: relative;
            text-align: center;
            padding: 50px 0;
            background-image: url('images/photo_6314310101258846449_w.jpg');
            background-size: cover;
            background-position: center;
            margin-bottom: 30px;
        }

        .header-section h1 {
            position: relative;
            z-index: 2;
            color: #FFD700;
            text-transform: uppercase;
            font-size: 2.5em;
            margin: 0;
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
            letter-spacing: 2px;
        }

        /* Popup styles */
        .popup-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            justify-content: center;
            align-items: center;
            z-index: 99999;
        }

        .popup-content {
            background: white;
            padding: 40px;
            border-radius: 15px;
            text-align: center;
            max-width: 450px;
            width: 90%;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
            position: relative;
            z-index: 100000;
        }

        .success-icon {
            color: #4CAF50;
            font-size: 70px;
            margin-bottom: 25px;
        }

        .popup-content h2 {
            color: #333;
            margin-bottom: 20px;
            font-size: 26px;
            font-weight: 600;
        }

        .popup-content p {
            color: #666;
            margin-bottom: 30px;
            font-size: 16px;
            line-height: 1.6;
            padding: 0 20px;
        }

        .close-btn {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 15px 40px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .close-btn:hover {
            background-color: #388E3C;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(76, 175, 80, 0.3);
        }

        <?php 
            include 'assets/CSS/navbar.css';
            include 'assets/CSS/footer.css';
        ?>
        .contact-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .contact-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .contact-header h1 {
            color: var(--text-dark);
            margin-bottom: 15px;
        }

        .contact-header p {
            color: var(--text-light);
            font-size: 16px;
            line-height: 1.6;
        }

        .contact-info-container {
            display: flex;
            gap: 30px;
            margin-bottom: 40px;
        }

        .contact-info-item {
            flex: 1;
            text-align: center;
            padding: 30px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .contact-info-item i {
            color: #4CAF50;
            font-size: 32px;
            margin-bottom: 15px;
        }

        .contact-info-item h3 {
            color: var(--text-dark);
            margin-bottom: 10px;
        }

        .contact-info-item p, 
        .contact-info-item a {
            color: var(--text-light);
            text-decoration: none;
        }

        .contact-info-item a:hover {
            color: #388E3C;
        }

        .contact-form {
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-dark);
            font-weight: 500;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            color: var(--text-dark);
        }

        .form-group input:focus,
        .form-group textarea:focus {
            border-color: #4CAF50;
            outline: none;
        }

        .submit-btn {
            background-color: #4CAF50;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            transition: background-color 0.3s;
        }

        .submit-btn:hover {
            background-color: #388E3C;
        }

        /* Map container */
        .map-container {
            margin-top: 40px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
<?php
    include 'navbar.php';
    ?>
    <main class="container contact-section">
        <div class="header-section">
            <h1>liên hệ</h1>
        </div>

        <!-- Bản đồ -->
        <div class="map">
            <div id="leaflet-map"></div>
        </div>
    
        <!-- Nội dung chính -->
        <div class="content">
            <!-- Cột trái: Thông tin liên hệ và giờ làm việc -->
            <div class="left-column">
                <div class="contact-info">
                    <h2>Thông tin liên hệ</h2>
                    <p>
                        <i class="fas fa-map-marker-alt" style="width: 20px; color: #4CAF50;"></i>
                        <strong>Địa chỉ:</strong> 56 Đ. Hoàng Diệu 2, Phường Linh Trung, Thủ Đức, Hồ Chí Minh, Vietnam
                    </p>
                    <p>
                        <i class="fas fa-phone" style="width: 20px; color: #4CAF50;"></i>
                        <strong>Điện thoại:</strong> 0963 922 597
                    </p>
                    <p>
                        <i class="fas fa-envelope" style="width: 20px; color: #4CAF50;"></i>
                        <strong>Email:</strong> datsanhub@gmail.com
                    </p>
                    <p>
                        <i class="fas fa-share-alt" style="width: 20px; color: #4CAF50;"></i>
                        <strong>Tìm chúng tôi:</strong>
                        <a href="https://facebook.com/datsanhub" target="_blank" title="Facebook" style="margin-left: 10px;">
                            <i class="fab fa-facebook" style="color: #4CAF50;"></i>
                        </a>
                        <a href="https://instagram.com/datsanhub" target="_blank" title="Instagram" style="margin-left: 10px;">
                            <i class="fab fa-instagram" style="color: #4CAF50;"></i>
                        </a>
                    </p>
                </div>
    
                <div class="working-hours">
                    <h2>Giờ làm việc</h2>
                    <p>
                        <i class="far fa-clock" style="width: 20px; color: #4CAF50;"></i>
                        <strong>Thứ 2 - Thứ 6:</strong> 7:00 AM - 8:00 PM
                    </p>
                    <p>
                        <i class="far fa-clock" style="width: 20px; color: #4CAF50;"></i>
                        <strong>Thứ 7 - Chủ nhật:</strong> 7:00 AM - 10:00 PM
                    </p>
                </div>
            </div>
    
            <!-- Cột phải: Form liên hệ -->
            <div class="right-column">
                <div class="contact-form">
                    <h2>Gửi tin nhắn cho chúng tôi</h2>
                    <p class="form-description">Mọi thắc mắc và yêu cầu hỗ trợ. Vui lòng để lại thông tin tại đây.</p>
                    <form id="contact-form" action="#" method="post">
                        <div class="form-group">
                            <label for="name">Họ và tên</label>
                            <input type="text" id="name" name="name" placeholder="Nhập họ và tên của bạn" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" placeholder="Nhập địa chỉ email của bạn" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="phone">Số điện thoại</label>
                            <input type="tel" id="phone" name="phone" placeholder="Nhập số điện thoại của bạn" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="subject">Chủ đề</label>
                            <input type="text" id="subject" name="subject" placeholder="Nhập chủ đề liên hệ" required>
                        <div class="form-group">
                            <label for="message">Nội dung</label>
                            <textarea id="message" name="message" rows="5" placeholder="Nhập nội dung của bạn" required></textarea>
                        </div>
                        
                        <button type="submit" class="submit-btn">Gửi thông tin</button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <!-- Sửa lại cấu trúc popup -->
    <div class="popup-overlay" id="successPopup">
        <div class="popup-content">
            <i class="fas fa-check-circle success-icon"></i>
            <h2>Gửi thông tin thành công!</h2>
            <p>Cảm ơn bạn đã liên hệ với chúng tôi. Chúng tôi sẽ phản hồi sớm nhất có thể.</p>
            <button class="close-btn" onclick="closePopup()">Đóng</button>
        </div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
            function showPopup() {
            const popup = document.getElementById('successPopup');
            popup.style.display = 'flex';

            function closePopup() {
            const popup = document.getElementById('successPopup');
            popup.style.display = 'none';
            document.getElementById('contact-form').reset();
        }
        // Xử lý click outside
        window.onclick = function(event) {
            const popup = document.getElementById('successPopup');
            if (event.target == popup) 
            {
                closePopup();
            }
        }
        // Cập nhật event listener form submit
        document.getElementById('contact-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            fetch('submit_contact.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showPopup();
                } else {
                    alert('Có lỗi xảy ra khi gửi form. Vui lòng thử lại sau.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi gửi form. Vui lòng thử lại sau.');
            });
        });

            // Khởi tạo bản đồ Leaflet
            const map = L.map('leaflet-map').setView([10.8575505, 106.7626846], 17); 

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            L.marker([10.8575505, 106.7626846]).addTo(map)
                .bindPopup('DatSanHub<br>56 Đ. Hoàng Diệu 2, Thủ Đức, Hồ Chí Minh')
                .openPopup();
    </script>
<?php
    include 'footer.php';
?>
</body>
</html>

<?php
session_start();
// Kiểm tra đăng nhập
if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: trangchu.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông Tin Cá Nhân</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #f4f4f4;
        }

        .profile-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-top: 80px;
        }

        .profile-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .profile-header h1 {
            color: #333;
            margin-bottom: 1rem;
        }

        .profile-image {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            margin: 0 auto 1rem;
            display: block;
            border: 3px solid #ffd700;
        }

        .profile-info {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .info-label {
            font-weight: bold;
            color: #555;
        }

        .info-content {
            color: #333;
        }

        .profile-section {
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #eee;
        }

        .profile-section h2 {
            color: #333;
            margin-bottom: 1rem;
            font-size: 1.5rem;
        }

        .edit-button {
            background-color: #ffd700;
            color: #333;
            border: none;
            padding: 0.8rem 2rem;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s ease;
            display: block;
            margin: 2rem auto 0;
        }

        .edit-button:hover {
            background-color: #ffed4a;
        }

        @media (max-width: 768px) {
            .profile-info {
                grid-template-columns: 1fr;
            }
            
            .profile-container {
                margin: 1rem;
                padding: 1rem;
            }
        }
        /* CSS cho search box và user actions */
        .search-box {
            display: flex;
            align-items: center;
            background-color: white;
            border-radius: 4px;
            padding: 5px 10px;
            width: 300px;
        }

        .search-box input {
            border: none;
            outline: none;
            width: 100%;
            padding: 5px;
        }

        .search-box button {
            background: none;
            border: none;
            cursor: pointer;
            color: #004d40;
        }

        .user-actions {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .account-btn, .cart-btn {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
            position: relative;
        }

        .avatar-container {
            position: relative;
            width: 200px;
            margin: 0 auto 1rem;
            text-align: center;
        }

        .profile-image {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            display: block;
            border: 3px solid #ffd700;
            margin: 0 auto;
        }

        .change-avatar-btn {
            display: inline-block;
            background-color: #00796b;
            color: white;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin-top: 10px;
            transition: background-color 0.3s;
            white-space: nowrap;
            width: 200px;
        }

        .change-avatar-btn:hover {
            background-color: #005a4f;
        }
        /* Ẩn text "Choose file" và "No file chosen" */
        input[type="file"] {
            width: 0.1px;
            height: 0.1px;
            opacity: 0;
            overflow: hidden;
            position: absolute;
            z-index: -1;
        }

        <?php include 'Assets\CSS\navbar.php'; ?>
        <?php include 'Assets\CSS\footer.php'; ?>
    </style>
</head>
<body>
<?php
    include 'navbar.php';
    ?>
    <div class="profile-container">
        <div class="profile-header">
            <div class="avatar-container">
                <img src="images/9-anh-dai-dien-trang-inkythuatso-03-15-27-03.jpg" alt="Ảnh đại diện" class="profile-image" id="avatarPreview">
                <label for="avatarUpload" class="change-avatar-btn">
                    <i class="fas fa-upload"></i> Thay đổi ảnh đại diện
                </label>
                <input type="file" id="avatarUpload" class="d-none" accept="image/*">
            </div>
            <h1>Thông Tin Cá Nhân</h1>
        </div>

        <div class="profile-section">
            <h2>Thông Tin Cơ Bản</h2>
            <div class="profile-info">
                <div class="info-label">Họ và Tên:</div>
                <div class="info-content">Nguyễn Văn A</div>

                <div class="info-label">Ngày Sinh:</div>
                <div class="info-content">01/01/1990</div>

                <div class="info-label">Email:</div>
                <div class="info-content">nguyenvana@email.com</div>

                <div class="info-label">Số Điện Thoại:</div>
                <div class="info-content">0123456789</div>
            </div>
        </div>

        <div class="profile-section">
            <h2>Địa Chỉ</h2>
            <div class="profile-info">
                <div class="info-label">Địa Chỉ:</div>
                <div class="info-content">123 Đường ABC, Phường XYZ</div>

                <div class="info-label">Quận/Huyện:</div>
                <div class="info-content">Quận 1</div>

                <div class="info-label">Thành Phố:</div>
                <div class="info-content">TP. Hồ Chí Minh</div>
            </div>
        </div>

        <div class="profile-section">
            <h2>Thông Tin Bổ Sung</h2>
            <div class="profile-info">
                <div class="info-label">Sở Thích:</div>
                <div class="info-content">Thể thao, Đọc sách, Du lịch</div>

                <div class="info-label">Môn Thể Thao Yêu Thích:</div>
                <div class="info-content">Bóng đá, Cầu lông</div>
            </div>
        </div>

        <button class="edit-button">Chỉnh Sửa Thông Tin</button>
    </div>

    <!-- Add Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <script>
    document.getElementById('avatarUpload').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatarPreview').src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });
    </script>
<?php
 include 'footer.php';
 ?>
</body>
</html>

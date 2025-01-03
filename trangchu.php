<?php
session_start();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DatSanHub.com - Trang chủ</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Reset CSS */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
<?php 
        include 'assets/CSS/navbar.php';
        include 'assets/CSS/footer.php';
?>

        /* Hero Section */
        .hero {
            background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('path/to/hero-bg.jpg');
            background-size: cover;
            background-position: center;
            height: 500px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            padding: 0 20px;
        }

        .hero-content {
            max-width: 800px;
        }

        .hero-content h1 {
            font-size: 48px;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        .hero-content p {
            font-size: 20px;
            margin-bottom: 30px;
        }

        .hero-search {
            display: flex;
            gap: 15px;
            justify-content: center;
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 8px;
            backdrop-filter: blur(5px);
        }

        .hero-search select,
        .hero-search input {
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            min-width: 200px;
        }

        .hero-search button {
            background-color: #00796b;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .hero-search button:hover {
            background-color: #005a4f;
        }

        /* Popular Sports Section */
        .popular-sports {
            padding: 60px 20px;
            background-color: #f5f5f5;
        }

        .popular-sports h2 {
            text-align: center;
            font-size: 32px;
            margin-bottom: 40px;
            color: #333;
        }

        .sport-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .sport-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }

        .sport-card:hover {
            transform: translateY(-5px);
        }

        .sport-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .sport-card h3 {
            font-size: 20px;
            margin: 15px;
            color: #333;
        }

        .sport-card p {
            color: #666;
            margin: 0 15px 15px;
        }

        .view-more {
            display: block;
            text-align: center;
            padding: 12px;
            background-color: #00796b;
            color: white;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .view-more:hover {
            background-color: #005a4f;
        }

        /* Featured Equipment Section */
        .featured-equipment {
            padding: 60px 20px;
        }

        .featured-equipment h2 {
            text-align: center;
            font-size: 32px;
            margin-bottom: 40px;
            color: #333;
        }

        .equipment-slider {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .equipment-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }

        .equipment-card:hover {
            transform: translateY(-5px);
        }

        .equipment-card img {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }

        .equipment-info {
            padding: 15px;
        }

        .equipment-info h3 {
            font-size: 18px;
            margin-bottom: 10px;
            color: #333;
        }

        .rating {
            color: #ffd700;
            margin-bottom: 10px;
        }

        .rating span {
            color: #666;
            margin-left: 5px;
        }

        .price {
            font-size: 20px;
            font-weight: bold;
            color: #00796b;
            margin-bottom: 15px;
        }

        .add-to-cart {
            width: 100%;
            padding: 12px;
            background-color: #00796b;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .add-to-cart:hover {
            background-color: #005a4f;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .hero-content h1 {
                font-size: 36px;
            }

            .hero-search {
                flex-direction: column;
            }

            .hero-search select,
            .hero-search input,
            .hero-search button {
                width: 100%;
            }

            .sport-cards,
            .equipment-slider {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            }
        }

        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .footer {
            margin-top: auto;
        }

        /* Điều chỉnh style cho input time */
        .hero-search input[type="time"] {
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            min-width: 200px;
            background-color: white;
            color: #333;
        }

        .hero-search input[type="time"]::-webkit-calendar-picker-indicator {
            cursor: pointer;
            opacity: 0.6;
        }

        .hero-search input[type="time"]::-webkit-calendar-picker-indicator:hover {
            opacity: 1;
        }

        /* Style cho time picker */
        .time-picker {
            display: flex;
            align-items: center;
            background: white;
            border-radius: 4px;
            padding: 0 10px;
            min-width: 200px;
        }

        .hour-select,
        .minute-select {
            padding: 12px 10px;
            border: none;
            background: transparent;
            font-size: 16px;
            color: #333;
            cursor: pointer;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 8px center;
            background-size: 12px;
            padding-right: 24px;
        }

        .hour-select {
            flex: 1;
        }

        .minute-select {
            flex: 1;
        }

        .time-separator {
            color: #333;
            padding: 0 5px;
            font-weight: bold;
        }

        /* Hover effect cho select */
        .hour-select:hover,
        .minute-select:hover {
            background-color: #f5f5f5;
        }

        /* Focus styles */
        .hour-select:focus,
        .minute-select:focus {
            outline: none;
            background-color: #f5f5f5;
        }

        /* Features Section */
        .features {
            padding: 60px 0;
            background-color: #f8f9fa;
        }

        .feature-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            gap: 30px;
            padding: 0 20px;
        }

        .feature-item {
            flex: 1;
            text-align: center;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
        }

        .feature-item:hover {
            transform: translateY(-5px);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            background: #00796b;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .feature-icon i {
            font-size: 35px;
            color: white;
        }

        .feature-item h3 {
            font-size: 22px;
            color: #333;
            margin-bottom: 15px;
        }

        .feature-item p {
            color: #666;
            line-height: 1.6;
            font-size: 15px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .feature-container {
                flex-direction: column;
            }

            .feature-item {
                margin-bottom: 20px;
            }
        }
    </style>
</head>
<body>
    <?php
    include 'navbar.php';
    ?>

    <section class="hero">
        <div class="hero-content">
            <h1>Đặt Sân Thể Thao Trực Tuyến</h1>
            <p>Tìm và đặt sân một cách dễ dàng, nhanh chóng</p>
            <div class="hero-search">
                <select>
                    <option>Chọn môn thể thao</option>
                    <option>Bóng đá</option>
                    <option>Cầu lông</option>
                    <option>Tennis</option>
                    <option>Bóng rổ</option>
                    <option>Pickleball</option>
                </select>
                <div class="time-picker">
                    <select class="hour-select">
                        <option value="">Giờ</option>
                        <option value="06">06</option>
                        <option value="07">07</option>
                        <option value="08">08</option>
                        <option value="09">09</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                        <option value="13">13</option>
                        <option value="14">14</option>
                        <option value="15">15</option>
                        <option value="16">16</option>
                        <option value="17">17</option>
                        <option value="18">18</option>
                        <option value="19">19</option>
                        <option value="20">20</option>
                        <option value="21">21</option>
                    </select>
                    <span class="time-separator">:</span>
                    <select class="minute-select">
                        <option value="">Phút</option>
                        <option value="00">00</option>
                        <option value="15">15</option>
                        <option value="30">30</option>
                        <option value="45">45</option>
                    </select>
                </div>
                <button>Tìm Sân</button>
            </div>
        </div>
    </section>

    <section class="features">
        <div class="feature-container">
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-map-marked-alt"></i>
                </div>
                <h3>Tìm kiếm sân thích hợp</h3>
                <p>Dữ liệu sân đấu đối dào, liên tục cập nhật, giúp bạn dễ dàng tìm kiếm loại sân mong muốn</p>
            </div>

            <div class="feature-item">
                <div class="feature-icon">
                    <i class="far fa-calendar-check"></i>
                </div>
                <h3>Đặt lịch online</h3>
                <p>Không cần đến trực tiếp, không cần gọi điện đặt lịch, bạn hoàn toàn có thể đặt sân ở bất kì đâu có internet</p>
            </div>

            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <h3>Mua, thuê dụng cụ thể thao</h3>
                <p>Tìm kiếm, sở hữu hoặc thuê các dụng cụ thể thao chuyên dụng phù hợp với nhu cầu cá nhân</p>
            </div>
        </div>
    </section>
 <?php
 include 'footer.php';
 ?>
</body>
    </html>
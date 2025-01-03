<?php
session_start();
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
        }

        .sidebar {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 200px;
            padding: 20px;
        }

        .sidebar h1 {
            font-size: 18px;
            color: #004d40;
            margin-bottom: 10px;
            text-align: center;
        }

        .sport-select {
            color: #004d40;
        }

        .sport-option {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #eee;
            cursor: pointer;
            color: #00796b;
        }

        .sport-option:last-child {
            border-bottom: none;
        }

        .sport-option:hover {
            background-color: #f0f0f0;
        }

        .sport-option span {
            color: #f9a825;
        }

        .main-content {
            flex: 1;
            min-width: 800px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .sport-type {
            display: none;
        }

        .sport-type.active {
            display: block;
        }

        .courts {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            min-height: 400px;
        }

        .court {
            background-color: #ffffff;
            border: 1px solid #b2dfdb;
            padding: 10px;
            text-align: center;
            cursor: pointer;
            width: calc(25% - 10px);
            box-sizing: border-box;
            min-height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .court:hover {
            background-color: #b2dfdb;
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
                <div class="sport-option" data-sport="football">Bóng Đá <span>4</span></div>
                <div class="sport-option" data-sport="tennis">Tennis <span>4</span></div>
                <div class="sport-option" data-sport="pickleball">Pickleball <span>4</span></div>
                <div class="sport-option" data-sport="volleyball">Bóng Chuyền <span>2</span></div>
                <div class="sport-option" data-sport="basketball">Bóng Rổ <span>2</span></div>
                <div class="sport-option" data-sport="badminton">Cầu Lông <span>7</span></div>
            </div>
        </div>

        <div class="main-content">
            <section class="sport-type active" id="football">
                <h2>Bóng Đá</h2>
                <div class="courts">
                    <div class="court" data-id="football-1">Sân Bóng Đá 1</div>
                    <div class="court" data-id="football-2">Sân Bóng Đá 2</div>
                    <div class="court" data-id="football-3">Sân Bóng Đá 3</div>
                    <div class="court" data-id="football-4">Sân Bóng Đá 4</div>
                </div>
            </section>

            <section class="sport-type" id="tennis">
                <h2>Tennis</h2>
                <div class="courts">
                    <div class="court" data-id="tennis-1">Sân Tennis 1</div>
                    <div class="court" data-id="tennis-2">Sân Tennis 2</div>
                    <div class="court" data-id="tennis-3">Sân Tennis 3</div>
                    <div class="court" data-id="tennis-4">Sân Tennis 4</div>
                </div>
            </section>

            <section class="sport-type" id="pickleball">
                <h2>Pickleball</h2>
                <div class="courts">
                    <div class="court" data-id="pickleball-1">Sân Pickleball 1</div>
                    <div class="court" data-id="pickleball-2">Sân Pickleball 2</div>
                    <div class="court" data-id="pickleball-3">Sân Pickleball 3</div>
                    <div class="court" data-id="pickleball-4">Sân Pickleball 4</div>
                </div>
            </section>

            <section class="sport-type" id="volleyball">
                <h2>Bóng Chuyền</h2>
                <div class="courts">
                    <div class="court" data-id="volleyball-1">Sân Bóng Chuyền 1</div>
                    <div class="court" data-id="volleyball-2">Sân Bóng Chuyền 2</div>
                </div>
            </section>

            <section class="sport-type" id="basketball">
                <h2>Bóng Rổ</h2>
                <div class="courts">
                    <div class="court" data-id="basketball-1">Sân Bóng Rổ 1</div>
                    <div class="court" data-id="basketball-2">Sân Bóng Rổ 2</div>
                </div>
            </section>

            <section class="sport-type" id="badminton">
                <h2>Cầu Lông</h2>
                <div class="courts">
                    <div class="court" data-id="badminton-1">Sân Cầu Lông 1</div>
                    <div class="court" data-id="badminton-2">Sân Cầu Lông 2</div>
                    <div class="court" data-id="badminton-3">Sân Cầu Lông 3</div>
                    <div class="court" data-id="badminton-4">Sân Cầu Lông 4</div>
                    <div class="court" data-id="badminton-5">Sân Cầu Lông 5</div>
                    <div class="court" data-id="badminton-6">Sân Cầu Lông 6</div>
                    <div class="court" data-id="badminton-7">Sân Cầu Lông 7</div>
                </div>
            </section>
        </div>
    </div>

    <script>
        document.querySelectorAll('.sport-option').forEach(option => {
            option.addEventListener('click', function() {
                const selectedSport = this.getAttribute('data-sport');
                document.querySelectorAll('.sport-type').forEach(section => {
                    section.classList.remove('active');
                });
                if (selectedSport) {
                    document.getElementById(selectedSport).classList.add('active');
                }
            });
        });

        document.querySelectorAll('.court').forEach(court => {
            court.addEventListener('click', () => {
                const courtId = court.getAttribute('data-id');
                alert(`Bạn đã chọn ${court.textContent}`);
                // Thêm logic đặt sân tại đây (ví dụ: gửi yêu cầu đến server)
            });
        });

        // Copy toàn bộ JavaScript xử lý popup từ trangchu.php
        function showPopup(popupId) {
            // ...
        }

        function closePopup(popupId) {
            // ...
        }
        // ... copy các hàm khác
    </script>
<?php
 include 'footer.php';
 ?>
</body>
</html>

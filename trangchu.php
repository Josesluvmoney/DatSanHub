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
<?php 
        include 'assets/CSS/navbar.css';
        include 'assets/CSS/footer.css';
        include 'assets/CSS/trangchu.css';
?>
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
            <form class="hero-search" id="searchForm">
                <select name="sport" id="sportSelect" required>
                    <option value="">Chọn môn thể thao</option>
                    <option value="Bóng đá">Bóng đá</option>
                    <option value="Cầu lông">Cầu lông</option>
                    <option value="Tennis">Tennis</option>
                    <option value="Bóng rổ">Bóng rổ</option>
                    <option value="Bóng chuyền">Bóng chuyền</option>
                    <option value="Pickleball">Pickleball</option>
                </select>
                <div class="time-picker">
                    <select class="hour-select" required>
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
                    <select class="minute-select" required>
                        <option value="">Phút</option>
                        <option value="00">00</option>
                        <option value="15">15</option>
                        <option value="30">30</option>
                        <option value="45">45</option>
                    </select>
                </div>
                <button type="submit">Tìm Sân</button>
            </form>
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
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.getElementById('searchForm');
    const sportSelect = document.getElementById('sportSelect');
    
    searchForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const sportType = sportSelect.value;
        
        if (!sportType) {
            alert("Vui lòng chọn môn thể thao");
            return;
        }

        const sportMap = {
            "Bóng đá": "football",
            "Cầu lông": "badminton",
            "Tennis": "tennis",
            "Bóng rổ": "basketball",
            "Bóng chuyền": "volleyball",
            "Pickleball": "pickleball"
        };

        // Chuyển hướng tới trang danh sách sân
        window.location.href = 'danhsachsanbai.php?sport=' + sportMap[sportType];
    });
});
</script>
</body>
    </html>

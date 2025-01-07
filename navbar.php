<head>
    <!-- Các thẻ meta và CSS khác -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<nav class="navbar">
        <a href="trangchu.php" class="logo">DatSanHub.com</a>
        
        <div class="nav-links">
            <div class="nav-item">
                <a href="trangchu.php" class="nav-link">Trang chủ</a>
            </div>
            
            <div class="nav-item">
                <a href="danhsachsanbai.php" class="nav-link">Danh sách sân bãi</a>
                <div class="dropdown-content">
                    <a href="danhsachsanbai.php#football">Sân bóng đá</a>
                    <a href="danhsachsanbai.php#badminton">Sân cầu lông</a>
                    <a href="danhsachsanbai.php#tennis">Sân tennis</a>
                    <a href="danhsachsanbai.php#basketball">Sân bóng rổ</a>
                    <a href="danhsachsanbai.php#volleyball">Sân bóng chuyền</a>
                    <a href="danhsachsanbai.php#pickleball">Sân pickleball</a>
                </div>
            </div>
            
            <div class="nav-item">
                <a href="dungcuthethao.php" class="nav-link">Dụng cụ thể thao</a>
                <div class="dropdown-content">
                    <a href="dungcuthethao.php#football-equipment">Bóng đá</a>
                    <a href="dungcuthethao.php#badminton-equipment">Cầu lông</a>
                    <a href="dungcuthethao.php#tennis-equipment">Tennis</a>
                    <a href="dungcuthethao.php#basketball-equipment">Bóng rổ</a>
                    <a href="dungcuthethao.php#volleyball-equipment">Bóng chuyền</a>
                    <a href="dungcuthethao.php#pickleball-equipment">Pickleball</a>
                </div>
            </div>
            
            <div class="nav-item">
                <a href="lienhe.php" class="nav-link">Liên hệ</a>
            </div>
        </div>

        <div class="search-box">
            <input type="text" placeholder="Tìm kiếm...">
            <button type="button">
                <i class="fas fa-search"></i>
            </button>
        </div>

        <div class="user-actions">
            <div class="nav-item">
                <button class="account-btn">
                    <i class="fas fa-user"></i>
                    Tài khoản
                    <div class="account-dropdown">
                        <?php if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                            <a href="thongtincanhan.php"><i class="fas fa-user-circle"></i>Thông tin cá nhân</a>
                            <a href="donhang.php"><i class="fas fa-shopping-bag"></i>Đơn hàng</a>
                            <a href="#" data-action="logout"><i class="fas fa-sign-out-alt"></i>Đăng xuất</a>
                        <?php else: ?>
                            <a href="#" onclick="showPopup('loginPopup')"><i class="fas fa-sign-in-alt"></i>Đăng nhập</a>
                            <a href="#" onclick="showPopup('registerPopup')"><i class="fas fa-user-plus"></i>Đăng ký</a>
                        <?php endif; ?>
                    </div>
                </button>
            </div>
            
            <button class="cart-btn" onclick="window.location.href='giohang.php'">
                <i class="fas fa-shopping-cart"></i>
                Giỏ hàng
            </button>
        </div>
    </nav>

    <!-- Login Popup -->
    <div class="popup-overlay" id="loginPopup">
        <div class="popup-container" onclick="event.stopPropagation()">
            <button class="close-popup" onclick="closePopup('loginPopup')">&times;</button>
            <h2 style="text-align: center; margin-bottom: 25px;">Đăng Nhập</h2>
            
            <div class="social-login">
                <button class="social-btn btn-facebook" title="Đăng nhập với Facebook">
                    <i class="fab fa-facebook-f"></i>
                </button>
                <button class="social-btn btn-google" title="Đăng nhập với Google">
                    <i class="fab fa-google"></i>
                </button>
            </div>

            <div class="divider">hoặc</div>

            <form id="loginForm">
                <div class="form-group">
                    <label for="phone">Số điện thoại</label>
                    <input type="tel" id="phone" name="phone" required placeholder="Nhập số điện thoại">
                </div>
                <div class="form-group">
                    <label for="password">Mật khẩu</label>
                    <div class="password-input-group">
                        <input type="password" id="password" name="password" required placeholder="Nhập mật khẩu">
                        <i class="fas fa-eye-slash toggle-password" onclick="togglePassword('password')"></i>
                    </div>
                </div>
                <div class="forgot-password">
                    <a href="#" onclick="showPopup('forgotPasswordPopup'); closePopup('loginPopup')">Quên mật khẩu?</a>
                </div>
                <button type="submit" name="login" class="btn btn-primary">Đăng Nhập</button>
            </form>
            
            <div class="register-link">
                Chưa có tài khoản? <a href="#" onclick="showPopup('registerPopup'); closePopup('loginPopup')">Đăng ký ngay</a>
            </div>
        </div>
    </div>

    <!-- Register Popup -->
    <div class="popup-overlay" id="registerPopup">
        <div class="popup-container" onclick="event.stopPropagation()">
            <button class="close-popup" onclick="closePopup('registerPopup')">&times;</button>
            <h2 style="text-align: center; margin-bottom: 25px;">Đăng Ký Tài Khoản</h2>
            
            <div class="social-login">
                <button class="social-btn btn-facebook" title="Đăng ký với Facebook">
                    <i class="fab fa-facebook-f"></i>
                </button>
                <button class="social-btn btn-google" title="Đăng ký với Google">
                    <i class="fab fa-google"></i>
                </button>
            </div>

            <div class="divider">hoặc</div>

            <form id="registerForm">
                <div class="form-group">
                    <label for="reg-name">Họ và tên</label>
                    <input type="text" id="reg-name" name="fullname" required placeholder="Nhập họ và tên">
                </div>
                <div class="form-group">
                    <label for="reg-phone">Số điện thoại</label>
                    <input type="tel" id="reg-phone" name="phone" required placeholder="Nhập số điện thoại">
                </div>
                <div class="form-group">
                    <label for="reg-password">Mật khẩu</label>
                    <div class="password-input-group">
                        <input type="password" id="reg-password" name="password" required placeholder="Nhập mật khẩu">
                        <i class="fas fa-eye-slash toggle-password" onclick="togglePassword('reg-password')"></i>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Đăng Ký</button>
            </form>
            
            <div class="login-link">
                Đã có tài khoản? <a href="#" onclick="showPopup('loginPopup'); closePopup('registerPopup')">Đăng nhập</a>
            </div>
        </div>
    </div>

    <!-- Forgot Password Popup -->
    <div class="popup-overlay" id="forgotPasswordPopup">
        <div class="popup-container" onclick="event.stopPropagation()">
            <button class="back-button" onclick="showPopup('loginPopup'); closePopup('forgotPasswordPopup')">
                <i class="fas fa-arrow-left"></i>
            </button>
            <button class="close-popup" onclick="closePopup('forgotPasswordPopup')">&times;</button>
            <h2 style="text-align: center; margin-bottom: 15px;">Quên Mật Khẩu</h2>
            
            <!-- Step 1: Enter Phone Number -->
            <div class="step active" id="step1">
                <p class="info-text">Vui lòng nhập số điện thoại để nhận mã xác thực</p>
                <form onsubmit="return showStep(2)">
                    <div class="form-group">
                        <label for="forgot-phone">Số điện thoại</label>
                        <input type="tel" id="forgot-phone" name="phone" required placeholder="Nhập số điện thoại" pattern="[0-9]*">
                    </div>
                    <button type="submit" class="btn btn-primary">Gửi mã xác thực</button>
                </form>
            </div>

            <!-- Step 2: Enter Verification Code -->
            <div class="step" id="step2">
                <button class="back-button" onclick="showStep(1)">
                    <i class="fas fa-arrow-left"></i>
                </button>
                <p class="info-text">Mã xác thực đã được gửi đến số điện thoại: <span id="displayPhone"></span></p>
                <form onsubmit="return showStep(3)">
                    <div class="verification-code">
                        <input type="text" maxlength="1" oninput="moveToNext(this)">
                        <input type="text" maxlength="1" oninput="moveToNext(this)">
                        <input type="text" maxlength="1" oninput="moveToNext(this)">
                        <input type="text" maxlength="1" oninput="moveToNext(this)">
                        <input type="text" maxlength="1" oninput="moveToNext(this)">
                        <input type="text" maxlength="1">
                    </div>
                    <div class="resend-code">
                        <button type="button" onclick="resendCode()">Gửi lại mã</button>
                    </div>
                    <button type="submit" class="btn btn-primary">Xác nhận</button>
                </form>
            </div>

            <!-- Step 3: Set New Password -->
            <div class="step" id="step3">
                <button class="back-button" onclick="showStep(2)">
                    <i class="fas fa-arrow-left"></i>
                </button>
                <p class="info-text">Vui lòng nhập mật khẩu mới</p>
                <form onsubmit="return resetPassword()">
                    <div class="form-group">
                        <label for="newPassword">Mật khẩu mới</label>
                        <input type="password" id="newPassword" required placeholder="Nhập mật khẩu mới">
                    </div>
                    <div class="form-group">
                        <label for="confirmPassword">Xác nhận mật khẩu</label>
                        <input type="password" id="confirmPassword" required placeholder="Nhập lại mật khẩu mới">
                    </div>
                    <button type="submit" class="btn btn-primary">Đặt lại mật khẩu</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Notification Popup -->
    <div class="popup-overlay" id="notificationPopup">
        <div class="popup-container" onclick="event.stopPropagation()">
            <button class="close-popup" onclick="closePopup('notificationPopup')">&times;</button>
            <div id="notificationMessage"></div>
        </div>
    </div>
    <script>
<?php include 'assets/js/navbar.js'; ?>
    </script>
    <style>
<?php include 'assets/CSS/navbar.css'; ?>
    </style>



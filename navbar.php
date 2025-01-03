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
                            <a href="logout.php"><i class="fas fa-sign-out-alt"></i>Đăng xuất</a>
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

            <form action="login.php" method="POST">
                <div class="form-group">
                    <label for="phone">Số điện thoại</label>
                    <input type="tel" id="phone" name="phone" required placeholder="Nhập số điện thoại">
                </div>
                <div class="form-group">
                    <label for="password">Mật khẩu</label>
                    <input type="password" id="password" name="password" required placeholder="Nhập mật khẩu">
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

            <form action="reg.php" method="POST">
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
                    <input type="password" id="reg-password" name="password" required placeholder="Nhập mật khẩu">
                </div>
                <button type="submit" name="register" class="btn btn-primary">Đăng Ký</button>
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
        let currentStep = 1; // Thêm biến để lưu trạng thái hiện tại của form
        let savedPhoneNumber = ''; // Thêm biến để lưu số điện thoại

        function showPopup(popupId) {
            document.getElementById(popupId).classList.add('active');
            document.body.style.overflow = 'hidden';
            // Reset form khi mở popup quên mật khẩu mới
            if (popupId === 'forgotPasswordPopup') {
                currentStep = 1;
                savedPhoneNumber = '';
                showStep(1);
            }
        }

        function closePopup(popupId) {
            // Nếu là form quên mật khẩu, kiểm tra xem người dùng có muốn đóng không
            if (popupId === 'forgotPasswordPopup' && currentStep > 1) {
                if (!confirm('Bạn có chắc muốn hủy quá trình đặt lại mật khẩu?')) {
                    return;
                }
            }
            document.getElementById(popupId).classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        // Update the account dropdown links to use popup
        document.addEventListener('DOMContentLoaded', function() {
            const loginLink = document.querySelector('a[href="DangNhap.php"]');
            const registerLink = document.querySelector('a[href="DangKy.php"]');
            
            // Add click handlers for popups
            document.getElementById('loginPopup').addEventListener('click', function(e) {
                if (e.target === this) {
                    closePopup('loginPopup');
                }
            });

            document.getElementById('registerPopup').addEventListener('click', function(e) {
                if (e.target === this) {
                    closePopup('registerPopup');
                }
            });
            
            if (loginLink) {
                loginLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    showPopup('loginPopup');
                });
            }
            
            if (registerLink) {
                registerLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    showPopup('registerPopup');
                });
            }

            document.getElementById('forgotPasswordPopup').addEventListener('click', function(e) {
                if (e.target === this) {
                    // Nếu đang ở bước sau bước 1, hiện thông báo xác nhận
                    if (currentStep > 1) {
                        if (confirm('Bạn có chắc muốn hủy quá trình đặt lại mật khẩu?')) {
                            closePopup('forgotPasswordPopup');
                        }
                    } else {
                        closePopup('forgotPasswordPopup');
                    }
                }
            });
        });

        function showStep(step) {
            document.querySelectorAll('.step').forEach(el => el.classList.remove('active'));
            document.getElementById('step' + step).classList.add('active');
            
            // If moving to step 2, save and display the phone number
            if (step === 2) {
                const phoneNumber = document.getElementById('forgot-phone').value;
                if (!phoneNumber) {
                    alert('Vui lòng nhập số điện thoại');
                    return false;
                }
                savedPhoneNumber = phoneNumber;
                document.getElementById('displayPhone').textContent = phoneNumber;
            }
            
            currentStep = step;
            return false;
        }

        function moveToNext(input) {
            if (input.value.length === input.maxLength) {
                let next = input.nextElementSibling;
                if (next) {
                    next.focus();
                }
            }
        }

        function resendCode() {
            alert('Mã xác thực mới đã được gửi!');
        }

        function resetPassword() {
            let newPass = document.getElementById('newPassword').value;
            let confirmPass = document.getElementById('confirmPassword').value;
            
            if (newPass !== confirmPass) {
                alert('Mật khẩu không khớp!');
                return false;
            }
            
            alert('Đặt lại mật khẩu thành công!');
            currentStep = 1;
            savedPhoneNumber = '';
            showPopup('loginPopup');
            closePopup('forgotPasswordPopup');
            return false;
        }

        function showNotification(message) {
            document.getElementById('notificationMessage').innerHTML = message;
            showPopup('notificationPopup');
            
            // Chỉ tự động đóng khi là thông báo lỗi
            if(message.includes('thất bại')) {
                setTimeout(() => {
                    closePopup('notificationPopup');
                }, 10000);
            }
        }

        // Kiểm tra URL parameters khi trang load
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            
            // Xử lý thông báo đăng ký
            if(urlParams.get('register') === 'success') {
                showNotification(`
                    <div class="success-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h3 style="color: #4CAF50;">Đăng ký thành công!</h3>
                    <p>Tài khoản của bạn đã được tạo thành công.<br>Bạn có thể đăng nhập ngay bây giờ.</p>
                    <div class="notification-buttons">
                        <button class="login-btn" onclick="closePopup('notificationPopup'); showPopup('loginPopup');">
                            <i class="fas fa-sign-in-alt"></i> Đăng nhập ngay
                        </button>
                    </div>
                `);
                // Xóa parameter khỏi URL
                window.history.replaceState({}, document.title, window.location.pathname);
            } else if(urlParams.get('register') === 'phone_exists') {
                showNotification(`
                    <div class="error-icon">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <h3 class="error-title">Đăng ký không thành công</h3>
                    <p>Số điện thoại này đã được đăng ký.<br>Vui lòng sử dụng số điện thoại khác.</p>
                    <div class="notification-buttons">
                        <button class="retry-btn" onclick="closePopup('notificationPopup'); showPopup('registerPopup');">
                            <i class="fas fa-redo"></i> Thử lại
                        </button>
                    </div>
                `);
                window.history.replaceState({}, document.title, window.location.pathname);
            } else if(urlParams.get('register') === 'failed') {
                showNotification(`
                    <div class="error-icon">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <h3 class="error-title">Đăng ký không thành công</h3>
                    <p>Có lỗi xảy ra trong quá trình đăng ký.</p>
                    <div class="notification-buttons">
                        <button class="retry-btn" onclick="closePopup('notificationPopup'); showPopup('registerPopup');">
                            <i class="fas fa-redo"></i> Thử lại
                        </button>
                    </div>
                `);
                window.history.replaceState({}, document.title, window.location.pathname);
            }
            
            // Thêm xử lý thông báo đăng nhập
            if(urlParams.get('login') === 'success') {
                showNotification(`
                    <div class="success-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h3 style="color: #4CAF50;">Đăng nhập thành công!</h3>
                    <p>Chào mừng bạn đã quay trở lại.</p>
                `);
                window.history.replaceState({}, document.title, window.location.pathname);
            } else if(urlParams.get('login') === 'failed') {
                showNotification(`
                    <div class="error-icon">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <h3 class="error-title">Đăng nhập không thành công</h3>
                    <p>Số điện thoại hoặc mật khẩu không chính xác.</p>
                    <div class="notification-buttons">
                        <button class="retry-btn" onclick="closePopup('notificationPopup'); showPopup('loginPopup');">
                            <i class="fas fa-redo"></i> Thử lại
                        </button>
                    </div>
                `);
                window.history.replaceState({}, document.title, window.location.pathname);
            }
        });

        document.querySelector('#loginPopup form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('login.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Đóng popup đăng nhập
                    closePopup('loginPopup');
                    
                    // Hiển thị thông báo thành công
                    showNotification(`
                        <div class="success-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h3 style="color: #4CAF50;">Đăng nhập thành công!</h3>
                        <p>Chào mừng bạn đã quay trở lại.</p>
                    `);
                    
                    // Reload trang sau 1 giây
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    showNotification(`
                        <div class="error-icon">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                        <h3 class="error-title">Đăng nhập không thành công</h3>
                        <p>${data.message}</p>
                        <div class="notification-buttons">
                            <button class="retry-btn" onclick="closePopup('notificationPopup'); showPopup('loginPopup');">
                                <i class="fas fa-redo"></i> Thử lại
                            </button>
                        </div>
                    `);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification(`
                    <div class="error-icon">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <h3 class="error-title">Lỗi</h3>
                    <p>Có lỗi xảy ra. Vui lòng thử lại sau.</p>
                `);
            });
        });
    </script>

    <style>
    #notificationPopup .popup-container {
        min-width: 350px;
        text-align: center;
        padding: 30px;
        border-radius: 10px;
        background: #fff;
    }

    #notificationMessage {
        margin: 20px 0;
    }

    #notificationMessage h3 {
        margin: 0 0 15px 0;
        font-size: 20px;
    }

    #notificationMessage p {
        color: #666;
        margin: 10px 0 20px 0;
        font-size: 16px;
    }

    #notificationMessage .success-icon {
        color: #4CAF50;
        font-size: 50px;
        margin-bottom: 15px;
    }

    #notificationMessage .error-icon {
        color: #FF9800;
        font-size: 50px;
        margin-bottom: 15px;
    }

    #notificationMessage .notification-buttons {
        margin-top: 20px;
    }

    #notificationMessage .login-btn {
        background: #4CAF50;
        color: white;
        padding: 10px 25px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        transition: background 0.3s;
    }

    #notificationMessage .login-btn:hover {
        background: #45a049;
    }

    #notificationMessage .retry-btn {
        background: #FF9800;
        color: white;
        padding: 10px 25px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        transition: background 0.3s;
        margin-top: 10px;
    }

    #notificationMessage .retry-btn:hover {
        background: #F57C00;
    }

    #notificationMessage .notification-buttons {
        margin-top: 20px;
        display: flex;
        justify-content: center;
        gap: 10px;
    }

    #notificationMessage h3.error-title {
        color: #F57C00;
        margin: 0 0 15px 0;
        font-size: 20px;
    }
    </style>

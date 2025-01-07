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

// Xử lý form đăng ký
const registerForm = document.getElementById('registerForm');
if (registerForm) {
    registerForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        formData.append('register', 'true'); // Thêm flag register
        
        fetch('reg.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closePopup('registerPopup');
                showNotification(`
                    <div class="success-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h3 style="color: #4CAF50;">Đăng ký thành công!</h3>
                    <p>Chào mừng bạn đến với DatSanHub.</p>
                `);
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                showNotification(`
                    <div class="error-icon">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <h3 class="error-title">Đăng ký không thành công</h3>
                    <p>${data.message}</p>
                    <div class="notification-buttons">
                        <button class="retry-btn" onclick="closePopup('notificationPopup'); showPopup('registerPopup');">
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
}

$(document).ready(function() {
    // Xử lý form đăng nhập
    $('#loginForm').submit(function(e) {
        e.preventDefault();
        
        $.ajax({
            type: 'POST',
            url: 'login.php',
            data: $(this).serialize(),
            success: function(response) {
                // Nếu response là text/html (redirect)
                if (typeof response === 'string' && response.includes('admin.php')) {
                    window.location.href = 'admin.php';
                    return;
                }
                
                // Xử lý response JSON cho user thường
                try {
                    const data = typeof response === 'string' ? JSON.parse(response) : response;
                    if (data.success) {
                        if(data.token) {
                            localStorage.setItem('auth_token', data.token);
                        }
                        
                        closePopup('loginPopup');
                        showNotification(`
                            <div class="success-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <h3 style="color: #4CAF50;">Đăng nhập thành công!</h3>
                            <p>Chào mừng bạn đã quay trở lại.</p>
                        `);
                        
                        setTimeout(function() {
                            window.location.href = data.redirect;
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
                } catch (e) {
                    // Nếu không phải JSON và không phải redirect, hiển thị lỗi
                    showNotification(`
                        <div class="error-icon">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                        <h3 class="error-title">Lỗi</h3>
                        <p>Có lỗi xảy ra. Vui lòng thử lại sau.</p>
                    `);
                }
            },
            error: function() {
                showNotification(`
                    <div class="error-icon">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <h3 class="error-title">Lỗi</h3>
                    <p>Có lỗi xảy ra. Vui lòng thử lại sau.</p>
                `);
            }
        });
    });

    // Thêm token vào mọi request AJAX
    $.ajaxSetup({
        beforeSend: function(xhr) {
            const token = localStorage.getItem('auth_token');
            if(token) {
                xhr.setRequestHeader('Authorization', 'Bearer ' + token);
            }
        }
    });
});

function checkAuth() {
    const token = localStorage.getItem('auth_token');
    return token != null;
}

function logout() {
    const logoutLink = document.querySelector('[data-action="logout"]');
    if (logoutLink) {
        logoutLink.click();
    }
}

function handleLogout(event) {
    event.preventDefault();
    
    const currentPage = window.location.href;
    
    $.ajax({
        type: 'POST',
        url: 'logout.php',
        dataType: 'json',
        success: function(response) {
            if(response.success) {
                localStorage.removeItem('auth_token');
                showNotification(`
                    <div class="success-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h3 style="color: #4CAF50;">Đăng xuất thành công!</h3>
                `);
                
                setTimeout(() => {
                    // Redirect về trang trước đó
                    window.location.href = response.redirect_url;
                }, 1000);
            }
        },
        error: function() {
            showNotification(`
                <div class="error-icon">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <h3 class="error-title">Lỗi</h3>
                <p>Có lỗi xảy ra khi đăng xuất. Vui lòng thử lại.</p>
            `);
        }
    });
}

$(document).ready(function() {
    // Xử lý sự kiện logout
    $(document).on('click', '[data-action="logout"]', function(e) {
        e.preventDefault();
        handleLogout(e);
    });
});

function togglePassword(inputId) {
    const passwordInput = document.getElementById(inputId);
    const toggleIcon = passwordInput.nextElementSibling;
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
        toggleIcon.classList.add('active');
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
        toggleIcon.classList.remove('active');
    }
}
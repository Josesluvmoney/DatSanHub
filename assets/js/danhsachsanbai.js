let selectedCourt = null;

function handleBooking(courtId, courtName, price, imageStatus, openingTime, closingTime) {
    if (!<?php echo isset($_SESSION['logged_in']) ? 'true' : 'false'; ?>) {
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
        return;
    }

    selectedCourt = { id: courtId, name: courtName, price: price, imageStatus: imageStatus };
    document.getElementById('selectedCourtName').textContent = courtName;
    document.getElementById('selectedCourtPrice').textContent = `${formatCurrency(price)}/giờ`;
    document.getElementById('bookingModal').style.display = 'block';
    updateTotalPrice();

    // Giới hạn thời gian đặt sân
    const startTimeInput = document.getElementById('startTime');
    startTimeInput.min = openingTime;
    startTimeInput.max = closingTime;

    // Hiển thị giờ hoạt động
    document.getElementById('operatingHours').textContent = 
        `Giờ hoạt động: ${formatTime(openingTime)} - ${formatTime(closingTime)}`;
}

function closeBookingModal() {
    document.getElementById('bookingModal').style.display = 'none';
    document.getElementById('bookingForm').reset();
}

function updateTotalPrice() {
    const duration = parseInt(document.getElementById('duration').value);
    const totalPrice = selectedCourt.price * duration;
    document.getElementById('totalPrice').textContent = formatCurrency(totalPrice);
}

document.getElementById('duration').addEventListener('change', updateTotalPrice);

document.getElementById('bookingForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const bookingDate = document.getElementById('bookingDate').value;
    const startTime = document.getElementById('startTime').value;
    const duration = document.getElementById('duration').value;
    
    if (!bookingDate || !startTime || !duration) {
        alert('Vui lòng điền đầy đủ thông tin đặt sân');
        return;
    }

    const formData = new FormData();
    formData.append('add_to_cart', '1');
    formData.append('id', selectedCourt.id);
    formData.append('type', 'court');
    formData.append('name', selectedCourt.name);
    formData.append('price', selectedCourt.price);
    formData.append('image_status', selectedCourt.imageStatus);
    formData.append('booking_date', bookingDate);
    formData.append('start_time', startTime);
    formData.append('duration', duration);

    fetch('xulygiohang.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeBookingModal();
            showNotification(`
                <div class="success-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h3 class="notification-title">Thành công!</h3>
                <p class="notification-message">Đã thêm vào giỏ hàng</p>
            `);
            
            updateCartCount(data.cartCount);
            
            setTimeout(() => {
                closePopup('notificationPopup');
            }, 500);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
});

function formatCurrency(amount) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(amount).replace('₫', 'VNĐ');
}

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

function updateCartCount(count) {
    const cartCountElement = document.querySelector('.cart-count');
    if (cartCountElement) {
        cartCountElement.textContent = count;
    }
}

function formatTime(time) {
    return time.substring(0, 5);
} 
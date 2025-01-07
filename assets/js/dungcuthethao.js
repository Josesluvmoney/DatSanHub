function handleEquipment(id, type, name, price, imageStatus) {
    <?php if (!isset($_SESSION['logged_in'])): ?>
        showNotification(`
            <div class="warning-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h3 class="notification-title">Yêu cầu đăng nhập</h3>
            <p class="notification-message">Vui lòng đăng nhập để ${type === 'buy' ? 'mua' : 'thuê'} dụng cụ</p>
            <div class="notification-buttons">
                <button class="login-btn" onclick="closePopup('notificationPopup'); showPopup('loginPopup');">
                    <i class="fas fa-sign-in-alt"></i> Đăng nhập ngay
                </button>
            </div>
        `);
    <?php else: ?>
        const formData = new FormData();
        formData.append('add_to_cart', '1');
        formData.append('id', id);
        formData.append('type', 'equipment');
        formData.append('name', name);
        formData.append('price', price);
        formData.append('image_status', imageStatus);
        formData.append('action_type', type);

        fetch('xulygiohang.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
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
    <?php endif; ?>
}
function updateCartCount(count) {
    const cartCountElement = document.querySelector('.cart-count');
    if (cartCountElement) {
        cartCountElement.textContent = count;
    }
}

document.addEventListener('DOMContentLoaded', function() {
const categoryItems = document.querySelectorAll('.category-item');
categoryItems.forEach(item => {
    item.addEventListener('click', function() {
        categoryItems.forEach(i => i.classList.remove('active'));
        this.classList.add('active');
        const type = this.getAttribute('data-type');
        document.querySelectorAll('.equipment-type').forEach(section => {
            section.classList.remove('active');
        });
        const targetSection = document.getElementById(type);
        if (targetSection) {
            targetSection.classList.add('active');
        }
    });
});
});
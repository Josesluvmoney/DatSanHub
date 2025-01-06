<?php
session_start();
require_once 'config.php';

$cart_items = array();
$total_amount = 0;

if (isset($_SESSION['logged_in']) && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Lấy các sản phẩm trong giỏ hàng của user kèm hình ảnh
    $sql = "SELECT gt.*, 
            CASE 
                WHEN gt.type = 'equipment' THEN dc.Image
                WHEN gt.type = 'court' THEN s.Image
            END AS Image,
            gt.booking_date,
            gt.start_time,
            gt.end_time,
            gt.duration
            FROM tbl_giohang_temp gt
            LEFT JOIN tbl_dungcu dc ON gt.type = 'equipment' AND gt.id_DungCu = dc.id_DungCu
            LEFT JOIN tbl_san s ON gt.type = 'court' AND gt.id_San = s.id_San
            WHERE gt.id_TK = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $cart_items[] = $row;
        $total_amount += ($row['price'] * $row['quantity']);
    }

    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ Hàng - DatSanHub</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
<?php 
include 'assets/CSS/navbar.css';
include 'assets/CSS/footer.css';
include 'assets/CSS/giohang.css';
?>
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="cart-container">
        <h1>Giỏ Hàng</h1>
        
        <?php if (!isset($_SESSION['logged_in'])): ?>
            <div class="empty-cart">
                <i class="fas fa-user-lock"></i>
                <p>Vui lòng đăng nhập để xem giỏ hàng của bạn</p>
            </div>
        <?php elseif (empty($cart_items)): ?>
            <div class="empty-cart">
            <i class="fas fa-shopping-cart"></i>
                <p>Giỏ hàng của bạn đang trống</p>
            <a href="dungcuthethao.php" class="continue-shopping">Tiếp tục mua sắm</a>
        </div>
        <?php else: ?>
        <div class="cart-content">
            <div class="cart-header">
                    <span>Sản phẩm</span>
                    <span>Giá</span>
                    <span>Số lượng</span>
                    <span>Tổng</span>
                    <span></span>
            </div>

                <?php foreach ($cart_items as $index => $item): ?>
            <div class="cart-item">
                <div class="product-info">
                    <img src="<?php 
                        if (isset($item['Image']) && $item['Image'] !== null) {
                            echo 'data:image/jpeg;base64,' . base64_encode($item['Image']);
                        } else {
                            echo 'images/default.jpg';
                        }
                    ?>" 
                    alt="<?php echo htmlspecialchars($item['name']); ?>" 
                    class="product-image">
                    <div>
                        <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                        <p>
                            <?php 
                            if ($item['type'] === 'equipment') {
                                echo $item['action_type'] === 'rent' ? 'Thuê 1h' : 'Mua';
                            } else {
                                echo 'Đặt sân';
                            }
                            ?>
                        </p>
                    </div>
                </div>
                        <div class="price"><?php echo number_format($item['price'], 0, ',', '.'); ?> VNĐ</div>
                <div class="quantity-controls">
                            <button class="quantity-btn" onclick="updateQuantity('decrease', <?php echo $item['id']; ?>)">-</button>
                            <input type="number" class="quantity-input" value="<?php echo $item['quantity']; ?>" 
                                   min="1" onchange="updateQuantity('input', <?php echo $item['id']; ?>)">
                            <button class="quantity-btn" onclick="updateQuantity('increase', <?php echo $item['id']; ?>)">+</button>
                        </div>
                        <div class="total">
                            <?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?> VNĐ
                </div>
                        <button class="remove-btn" onclick="removeItem(<?php echo $item['id']; ?>)">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
                <?php if ($item['type'] === 'court' && 
                          isset($item['booking_date']) && 
                          isset($item['start_time']) && 
                          isset($item['duration'])): ?>
                    <div class="booking-details">
                        <p>Ngày đặt: <?php echo date('d/m/Y', strtotime($item['booking_date'])); ?></p>
                        <p>Giờ bắt đầu: <?php echo date('H:i', strtotime($item['start_time'])); ?></p>
                        <?php if (isset($item['end_time'])): ?>
                            <p>Giờ kết thúc: <?php echo date('H:i', strtotime($item['end_time'])); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                <?php endforeach; ?>

            <div class="cart-summary">
                <div class="summary-row">
                        <span>Tổng tiền hàng:</span>
                        <span><?php echo number_format($total_amount, 0, ',', '.'); ?> VNĐ</span>
                </div>
                <div class="summary-row">
                    <span>Phí vận chuyển:</span>
                        <span>0 VNĐ</span>
                        </div>
                        <div class="summary-row total">
                            <span>Tổng cộng:</span>
                        <span><?php echo number_format($total_amount, 0, ',', '.'); ?> VNĐ</span>
                    </div>
                    <button class="checkout-btn" onclick="showPaymentPopup()">Thanh toán</button>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- JavaScript để xử lý các thao tác -->
    <script>
    function updateQuantity(action, itemId) {
        const quantityContainer = event.target.closest('.quantity-controls');
        const quantityInput = quantityContainer.querySelector('.quantity-input');
        const priceElement = event.target.closest('.cart-item').querySelector('.price');
        const totalElement = event.target.closest('.cart-item').querySelector('.total');
        
        let currentQuantity = parseInt(quantityInput.value);
        let price = parseInt(priceElement.textContent.replace(/[^\d]/g, '')); // Lấy số từ chuỗi giá
        
        if (action === 'decrease') {
            currentQuantity = Math.max(1, currentQuantity - 1);
        } else if (action === 'increase') {
            currentQuantity++;
        } else if (action === 'input') {
            currentQuantity = Math.max(1, parseInt(event.target.value) || 1);
        }
        
        // Cập nhật hiển thị số lượng và tổng tiền
        quantityInput.value = currentQuantity;
        totalElement.textContent = formatCurrency(price * currentQuantity);
        
        // Cập nhật tổng tiền giỏ hàng
        updateCartTotal();
        
        // Gửi request cập nhật lên server
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'xulygiohang.php';
        
        const inputs = {
            'update_quantity': '1',
            'item_id': itemId,
            'quantity': currentQuantity
        };
        
        for (const [name, value] of Object.entries(inputs)) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = name;
            input.value = value;
            form.appendChild(input);
        }
        
        document.body.appendChild(form);
        form.submit();
    }

    function updateCartTotal() {
        const cartItems = document.querySelectorAll('.cart-item');
        let total = 0;
        
        cartItems.forEach(item => {
            const totalText = item.querySelector('.total').textContent;
            total += parseInt(totalText.replace(/[^\d]/g, ''));
        });
        
        // Cập nhật tổng tiền hàng và tổng cộng
        document.querySelector('.cart-summary .summary-row:first-child span:last-child').textContent = formatCurrency(total);
        document.querySelector('.cart-summary .total span:last-child').textContent = formatCurrency(total);
    }

    function formatCurrency(amount) {
        return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' })
            .format(amount)
            .replace('₫', 'VNĐ');
    }

    function removeItem(itemId) {
        if (confirm('Bạn có chắc muốn xóa sản phẩm này?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'xulygiohang.php';
            
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'remove_item';
            input.value = itemId;
            
            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        }
    }

    function showPaymentPopup() {
        document.getElementById('paymentPopup').style.display = 'flex';
    }

    function closePaymentPopup() {
        document.getElementById('paymentPopup').style.display = 'none';
    }

    // Đóng popup khi click bên ngoài
    window.onclick = function(event) {
        const popup = document.getElementById('paymentPopup');
        if (event.target == popup) {
            popup.style.display = 'none';
        }
    }

    function showConfirmOrderPopup() {
        document.getElementById('paymentPopup').style.display = 'none';
        document.getElementById('confirmOrderPopup').style.display = 'flex';
    }

    function closeConfirmOrderPopup() {
        document.getElementById('confirmOrderPopup').style.display = 'none';
    }

    // Sửa lại xử lý form thanh toán
    document.getElementById('paymentForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Kiểm tra các trường bắt buộc
        const requiredFields = ['receiver_name', 'phone', 'address'];
        let isValid = true;
        const formData = {};
        
        requiredFields.forEach(field => {
            const input = this.querySelector(`[name="${field}"]`);
            formData[field] = input.value.trim();
            if (!formData[field]) {
                isValid = false;
                input.classList.add('error');
            } else {
                input.classList.remove('error');
            }
        });
        
        formData.note = this.querySelector('[name="note"]').value.trim();
        
        if (!isValid) {
            alert('Vui lòng điền đầy đủ thông tin giao hàng');
            return;
        }
        
        const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
        if (!paymentMethod) {
            alert('Vui lòng chọn phương thức thanh toán');
            return;
        }
        
        // Xử lý riêng cho từng phương thức thanh toán
        if (paymentMethod.value === 'banking') {
            // Hiển thị form chuyển khoản
            const bankingForm = document.getElementById('bankingOrderForm');
            for (const [key, value] of Object.entries(formData)) {
                bankingForm.querySelector(`[name="${key}"]`).value = value;
            }
            document.getElementById('bankingConfirmPopup').style.display = 'flex';
            closePaymentPopup();
        } else {
            // COD - hiển thị form xác nhận thông thường
            showConfirmOrderPopup();
        }
    });

    // Sửa lại xử lý form banking
    document.getElementById('bankingOrderForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Hiển thị popup xác nhận đã chuyển khoản
        if (confirm('Bạn đã hoàn tất chuyển khoản?')) {
            // Nếu đã chuyển khoản, submit form
            this.submit();
        }
    });

    // Cập nhật đóng popup khi click bên ngoài
    window.onclick = function(event) {
        const paymentPopup = document.getElementById('paymentPopup');
        const confirmOrderPopup = document.getElementById('confirmOrderPopup');
        const bankingPopup = document.getElementById('bankingConfirmPopup');
        
        if (event.target == paymentPopup) {
            paymentPopup.style.display = 'none';
        }
        if (event.target == confirmOrderPopup) {
            confirmOrderPopup.style.display = 'none';
        }
        if (event.target == bankingPopup) {
            bankingPopup.style.display = 'none';
        }
    }

    function closePendingPopup() {
        document.getElementById('pendingPopup').style.display = 'none';
        // Chuyển về URL gốc không có tham số
        window.history.replaceState({}, document.title, window.location.pathname);
        // Reload trang để cập nhật giỏ hàng
        window.location.reload();
    }

    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            // Hiển thị thông báo nhỏ khi copy thành công
            const toast = document.createElement('div');
            toast.className = 'copy-toast';
            toast.textContent = 'Đã sao chép!';
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.remove();
            }, 2000);
        });
    }

    // CSS cho toast notification
    const style = document.createElement('style');
    style.textContent = `
    .copy-toast {
        position: fixed;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        background-color: rgba(0, 0, 0, 0.8);
        color: white;
        padding: 10px 20px;
        border-radius: 4px;
        z-index: 1000;
        animation: fadeInOut 2s ease;
    }

    @keyframes fadeInOut {
        0% { opacity: 0; }
        20% { opacity: 1; }
        80% { opacity: 1; }
        100% { opacity: 0; }
    }
    `;
    document.head.appendChild(style);
    </script>

    <!-- Thêm popup thanh toán -->
    <div id="paymentPopup" class="payment-popup">
        <div class="popup-content">
            <div class="popup-header">
                <h2>Thanh toán</h2>
                <button onclick="closePaymentPopup()" class="close-btn">&times;</button>
            </div>
            <form id="paymentForm" action="xulygiohang.php" method="POST">
                <input type="hidden" name="checkout" value="1">
                
                <div class="delivery-info">
                    <h3>Thông tin giao hàng</h3>
                    <div class="form-group">
                        <label>Họ tên người nhận:</label>
                        <input type="text" name="receiver_name" required value="<?php echo $_SESSION['fullname']; ?>">
                    </div>
                    <div class="form-group">
                        <label>Số điện thoại:</label>
                        <input type="tel" name="phone" required pattern="[0-9]{10}">
                    </div>
                    <div class="form-group">
                        <label>Địa chỉ nhận hàng:</label>
                        <textarea name="address" required rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Ghi chú:</label>
                        <textarea name="note" rows="2"></textarea>
                    </div>
                </div>

                <div class="payment-section">
                    <h3>Phương thức thanh toán</h3>
                    <div class="payment-methods">
                        <label class="payment-method">
                            <input type="radio" name="payment_method" value="cod" required>
                            <span class="method-info">
                                <i class="fas fa-flag"></i>
                                Thanh toán khi nhận hàng (COD)
                            </span>
                        </label>
                        
                        <label class="payment-method">
                            <input type="radio" name="payment_method" value="banking" required>
                            <span class="method-info">
                                <i class="fas fa-university"></i>
                                Chuyển khoản ngân hàng
                            </span>
                        </label>
                    </div>
                </div>

                <!-- Thêm phần hiển thị thông tin chuyển khoản -->
                <div id="bankingInfo" class="banking-info" style="display: none;">
                    <h3>Thông tin chuyển khoản</h3>
                    <div class="bank-details">
                        <p><strong>Ngân hàng:</strong> MB BANK</p>
                        <p><strong>Số tài khoản:</strong> 0855558999999</p>
                        <p><strong>Tên tài khoản:</strong> HUYNH TAN DUNG</p>
                        <div class="qr-code">
                            <img src="images/qr-payment.jpg" alt="QR Code thanh toán">
                            <p class="qr-note"></p>
                        </div>
                        <p class="banking-note">
                            * Vui lòng chuyển khoản với nội dung: <br>
                            <strong>DH[Mã đơn hàng] [Số điện thoại]</strong>
                        </p>
                    </div>
                </div>

                <div class="order-summary">
                    <h3>Tổng quan đơn hàng</h3>
                    <div class="summary-row">
                        <span>Tổng tiền hàng:</span>
                        <span><?php echo number_format($total_amount, 0, ',', '.'); ?> VNĐ</span>
                    </div>
                    <div class="summary-row">
                        <span>Phí vận chuyển:</span>
                        <span>0 VNĐ</span>
                    </div>
                    <div class="summary-row total">
                        <span>Tổng cộng:</span>
                        <span><?php echo number_format($total_amount, 0, ',', '.'); ?> VNĐ</span>
                    </div>
                </div>

                <div class="payment-buttons">
                    <button type="button" class="cancel-btn" onclick="closePaymentPopup()">Hủy</button>
                    <button type="submit" class="confirm-btn">Xác nhận đặt hàng</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Thêm popup xác nhận đơn hàng COD -->
    <div id="confirmOrderPopup" class="payment-popup">
        <div class="popup-content">
            <div class="popup-header">
                <h2>Xác nhận đơn hàng</h2>
                <button onclick="closeConfirmOrderPopup()" class="close-btn">&times;</button>
            </div>
            <div class="order-details">
                <h3>Chi tiết đơn hàng</h3>
                <div class="order-items">
                    <?php foreach ($cart_items as $item): ?>
                    <div class="order-item">
                        <div class="item-info">
                            <img src="<?php 
                                if (isset($item['Image']) && $item['Image'] !== null) {
                                    echo 'data:image/jpeg;base64,' . base64_encode($item['Image']);
                                } else {
                                    echo 'images/default.jpg';
                                }
                            ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                            <div class="item-details">
                                <h4><?php echo htmlspecialchars($item['name']); ?></h4>
                                <p>
                                    <?php 
                                    if ($item['type'] === 'equipment') {
                                        echo $item['action_type'] === 'rent' ? 'Thuê' : 'Mua';
                                    } else {
                                        echo 'Đặt sân';
                                    }
                                    ?>
                                </p>
                                <p>Số lượng: <?php echo $item['quantity']; ?></p>
                                <p>Đơn giá: <?php echo number_format($item['price'], 0, ',', '.'); ?> VNĐ</p>
                            </div>
                        </div>
                        <div class="item-total">
                            <?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?> VNĐ
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="order-summary">
                    <div class="summary-row">
                        <span>Tổng tiền hàng:</span>
                        <span><?php echo number_format($total_amount, 0, ',', '.'); ?> VNĐ</span>
                    </div>
                    <div class="summary-row">
                        <span>Phí vận chuyển:</span>
                        <span>0 VNĐ</span>
                    </div>
                    <div class="summary-row total">
                        <span>Tổng cộng:</span>
                        <span><?php echo number_format($total_amount, 0, ',', '.'); ?> VNĐ</span>
                    </div>
                </div>
                <div class="payment-info">
                    <h3>Phương thức thanh toán</h3>
                    <p><i class="fas fa-flag"></i> Thanh toán khi nhận hàng (COD)</p>
                </div>
                <form id="finalOrderForm" action="xulygiohang.php" method="POST">
                    <input type="hidden" name="checkout" value="1">
                    <input type="hidden" name="payment_method" value="cod">
                    <div class="confirm-buttons">
                        <button type="button" class="cancel-btn" onclick="closeConfirmOrderPopup()">Quay lại</button>
                        <button type="submit" class="confirm-btn">Xác nhận đặt hàng</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Thêm popup thông báo thành công -->
    <div id="successPopup" class="payment-popup" style="display: <?php echo isset($_GET['order_success']) ? 'flex' : 'none'; ?>">
        <div class="popup-content">
            <div class="popup-header">
                <h2><i class="fas fa-check-circle" style="color: #4CAF50;"></i> Đặt hàng thành công</h2>
                <button onclick="closeSuccessPopup()" class="close-btn">&times;</button>
            </div>
            <div class="success-content">
                <?php
                if (isset($_GET['order_success'])) {
                    // Lấy đơn hàng mới nhất của user
                    $conn = new mysqli($servername, $username, $password, $dbname);
                    $user_id = $_SESSION['user_id'];
                    
                    $sql = "SELECT dh.*, u.Fullname 
                            FROM tbl_donhang dh 
                            JOIN tbl_user u ON dh.id_TK = u.id_TK 
                            WHERE dh.id_TK = ?
                            ORDER BY dh.created_at DESC
                            LIMIT 1";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $user_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $order = $result->fetch_assoc();
                    
                    if ($order) {
                        echo '<p class="success-message">';
                        echo 'Cảm ơn ' . htmlspecialchars($order['Fullname']) . ' đã đặt hàng!<br>';
                        echo 'Đơn hàng của bạn đã được xác nhận.<br>';
                        echo 'Mã đơn hàng: #' . $order['id_DonHang'] . '<br>';
                        echo 'Tổng tiền: ' . number_format($order['total_price'], 0, ',', '.') . ' VNĐ<br>';
                        echo 'Phương thức thanh toán: ' . ($order['payment_method'] === 'cod' ? 'Thanh toán khi nhận hàng' : 'Chuyển khoản ngân hàng');
                        echo '</p>';
                    }
                    $conn->close();
                }
                ?>
                <button onclick="closeSuccessPopup()" class="confirm-btn">Đóng</button>
            </div>
        </div>
    </div>
    <script>
    function closeSuccessPopup() {
        document.getElementById('successPopup').style.display = 'none';
        // Chuyển về URL gốc không có tham số
        window.history.replaceState({}, document.title, window.location.pathname);
        // Reload trang để cập nhật giỏ hàng
        window.location.reload();
    }
    </script>

    <!-- Thêm popup xác nhận đơn hàng Banking -->
    <div id="bankingConfirmPopup" class="payment-popup">
        <div class="popup-content">
            <div class="popup-header">
                <h2>Thông tin chuyển khoản</h2>
                <button onclick="closeBankingPopup()" class="close-btn">&times;</button>
            </div>
            <div class="banking-details">
                <div class="bank-info">
                    <h3>Thông tin tài khoản</h3>
                    <div class="bank-account-info">
                        <p><strong>Ngân hàng:</strong> MB BANK</p>
                        <p><strong>Chủ tài khoản:</strong> HUYNH TAN DUNG</p>
                        <p><strong>Số tài khoản:</strong> 0855558999999</p>
                        <p><strong>Số tiền:</strong> <?php echo number_format($total_amount, 0, ',', '.'); ?> VNĐ</p>
                    </div>
                    <div class="qr-code-section">
                        <img src="images/qr-payment.jpg" alt="QR Code thanh toán" class="qr-code-img">
                        <p class="qr-note"></p>
                    </div>
                </div>
                <div class="payment-note">
                    <p class="note-title">* Lưu ý:</p>
                    <ul>
                        <li>Vui lòng chuyển khoản chính xác số tiền</li>
                        <li>Nội dung chuyển khoản: <strong>DH [Số điện thoại]</strong></li>
                        <li>Đơn hàng sẽ được xử lý sau khi nhận được thanh toán</li>
                    </ul>
                </div>
                <form id="bankingOrderForm" action="xulygiohang.php" method="POST">
                    <input type="hidden" name="checkout" value="1">
                    <input type="hidden" name="payment_method" value="banking">
                    <input type="hidden" name="receiver_name" value="">
                    <input type="hidden" name="phone" value="">
                    <input type="hidden" name="address" value="">
                    <input type="hidden" name="note" value="">
                    <div class="confirm-buttons">
                        <button type="button" class="cancel-btn" onclick="closeBankingPopup()">Quay lại</button>
                        <button type="submit" class="confirm-btn">Xác nhận đặt hàng</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Thêm popup thông báo chờ thanh toán -->
    <div id="pendingPopup" class="payment-popup" style="display: <?php echo isset($_GET['order_pending']) ? 'flex' : 'none'; ?>">
        <div class="popup-content">
            <div class="popup-header">
                <h2><i class="fas fa-clock" style="color: #FFA500;"></i> Đơn hàng đang chờ thanh toán</h2>
                <button onclick="closePendingPopup()" class="close-btn">&times;</button>
            </div>
            <div class="pending-content">
                <?php
                if (isset($_GET['order_pending'])) {
                    $conn = new mysqli($servername, $username, $password, $dbname);
                    $user_id = $_SESSION['user_id'];
                    
                    $sql = "SELECT dh.*, u.Fullname 
                            FROM tbl_donhang dh 
                            JOIN tbl_user u ON dh.id_TK = u.id_TK 
                            WHERE dh.id_TK = ? AND dh.payment_method = 'banking'
                            ORDER BY dh.created_at DESC
                            LIMIT 1";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $user_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $order = $result->fetch_assoc();
                    
                    if ($order) {
                        echo '<div class="order-info">';
                        echo '<p>Cảm ơn ' . htmlspecialchars($order['Fullname']) . ' đã đặt hàng!</p>';
                        echo '<p>Mã đơn hàng: #' . $order['id_DonHang'] . '</p>';
                        echo '<p>Tổng tiền: ' . number_format($order['total_price'], 0, ',', '.') . ' VNĐ</p>';
                        echo '</div>';
                        
                        echo '<div class="bank-info">';
                        echo '<h3>Thông tin chuyển khoản</h3>';
                        echo '<div class="bank-details">';
                        echo '<div class="info-row">';
                        echo '<span class="label">Ngân hàng:</span>';
                        echo '<span class="value">MB BANK</span>';
                        echo '<button class="copy-btn" onclick="copyToClipboard(\'MB BANK\')"><i class="far fa-copy"></i></button>';
                        echo '</div>';
                        
                        echo '<div class="info-row">';
                        echo '<span class="label">Chủ tài khoản:</span>';
                        echo '<span class="value">HUYNH TAN DUNG</span>';
                        echo '<button class="copy-btn" onclick="copyToClipboard(\'HUYNH TAN DUNG\')"><i class="far fa-copy"></i></button>';
                        echo '</div>';
                        
                        echo '<div class="info-row">';
                        echo '<span class="label">Số tài khoản:</span>';
                        echo '<span class="value">0855558999999</span>';
                        echo '<button class="copy-btn" onclick="copyToClipboard(\'0855558999999\')"><i class="far fa-copy"></i></button>';
                        echo '</div>';
                        
                        echo '<div class="info-row">';
                        echo '<span class="label">Số tiền:</span>';
                        echo '<span class="value">' . number_format($order['total_price'], 0, ',', '.') . ' VNĐ</span>';
                        echo '<button class="copy-btn" onclick="copyToClipboard(\'' . $order['total_price'] . '\')"><i class="far fa-copy"></i></button>';
                        echo '</div>';
                        
                        echo '<div class="info-row">';
                        echo '<span class="label">Nội dung CK:</span>';
                        $transfer_content = 'DH' . $order['id_DonHang'] . ' ' . $order['phone'];
                        echo '<span class="value">' . $transfer_content . '</span>';
                        echo '<button class="copy-btn" onclick="copyToClipboard(\'' . $transfer_content . '\')"><i class="far fa-copy"></i></button>';
                        echo '</div>';
                        
                        echo '<div class="qr-code-section">';
                        echo '<img src="images/qr-payment.jpg" alt="QR Code thanh toán" class="qr-code-img">';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                    $conn->close();
                }
                ?>
                <div class="button-group">
                    <button onclick="window.print()" class="print-btn"><i class="fas fa-print"></i> In thông tin</button>
                    <button onclick="closePendingPopup()" class="confirm-btn">Đóng</button>
                </div>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>

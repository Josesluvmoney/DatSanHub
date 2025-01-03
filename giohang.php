<!DOCTYPE php>
<php lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ Hàng - DatSanHub</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* CSS cho giỏ hàng */
        .cart-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
        }

        .cart-header {
            display: grid;
            grid-template-columns: 3fr 1fr 1fr 1fr 0.5fr;
            padding: 15px;
            background-color: #f5f5f5;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .cart-item {
            display: grid;
            grid-template-columns: 3fr 1fr 1fr 1fr 0.5fr;
            padding: 15px;
            border-bottom: 1px solid #eee;
            align-items: center;
        }

        .product-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .product-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .quantity-btn {
            background: none;
            border: 1px solid #ddd;
            width: 30px;
            height: 30px;
            border-radius: 4px;
            cursor: pointer;
        }

        .quantity-input {
            width: 50px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 5px;
        }

        .remove-btn {
            background: none;
            border: none;
            color: #ff4444;
            cursor: pointer;
        }

        .cart-summary {
            margin-top: 30px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            width: 300px;
            margin-left: auto;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .checkout-btn {
            background-color: #00796b;
            color: white;
            border: none;
            padding: 15px;
            width: 100%;
            border-radius: 4px;
            margin-top: 20px;
            cursor: pointer;
            font-size: 16px;
        }

        .checkout-btn:hover {
            background-color: #005a4f;
        }

        .empty-cart {
            text-align: center;
            padding: 50px;
            color: #666;
        }

        .empty-cart i {
            font-size: 50px;
            color: #ddd;
            margin-bottom: 20px;
        }

        .continue-shopping {
            display: inline-block;
            background-color: #00796b;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 4px;
            margin-top: 20px;
        }
        /* Thêm CSS cho popup thanh toán */
        .payment-popup {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .popup-content {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
        }

        .popup-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #666;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .form-group textarea {
            height: 80px;
            resize: vertical;
        }

        .order-summary {
            margin: 20px 0;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 4px;
        }

        .order-summary h3 {
            margin-bottom: 10px;
            color: #333;
        }

        .confirm-btn {
            background-color: #00796b;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 4px;
            width: 100%;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .confirm-btn:hover {
            background-color: #005a4f;
        }

        .summary-row.total {
            font-weight: bold;
            font-size: 18px;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
        }

        /* CSS cho QR popup */
        .qr-popup {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .qr-popup .popup-content {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            width: 90%;
            max-width: 800px;
            display: flex;
            flex-direction: column;
        }

        .qr-popup .popup-body {
            display: flex;
            gap: 30px;
            margin: 20px 0;
        }

        .bank-info {
            flex: 1;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            height: fit-content;
        }

        .bank-info p {
            margin: 15px 0;
            font-size: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .bank-info .copy-btn {
            background: none;
            border: none;
            color: #00796b;
            cursor: pointer;
            font-size: 14px;
            padding: 5px;
        }

        .bank-info .copy-btn:hover {
            color: #005a4f;
        }

        .qr-code {
            flex: 1;
            text-align: center;
        }

        .qr-code img {
            max-width: 300px;
            height: auto;
            border: 1px solid #eee;
            padding: 10px;
            border-radius: 8px;
        }

        .popup-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 20px;
        }

        .popup-actions button {
            padding: 12px 25px;
            border-radius: 4px;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .confirm-payment-btn {
            background-color: #00796b;
            color: white;
            border: none;
        }

        .confirm-payment-btn:hover {
            background-color: #005a4f;
        }

        .back-btn {
            background-color: #f5f5f5;
            color: #333;
            border: 1px solid #ddd;
        }

        .back-btn:hover {
            background-color: #eee;
        }

        .copied-tooltip {
            position: fixed;
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 8px 15px;
            border-radius: 4px;
            font-size: 14px;
            pointer-events: none;
            transition: opacity 0.3s;
            z-index: 1100;
        }

        .note {
            color: #666;
            margin-top: 15px;
        }

        /* Chỉnh sửa select payment method */
        .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
        }

        /* CSS cho popup thông báo thành công */
        .success-popup {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .success-popup .popup-content {
            background-color: white;
            padding: 40px;
            border-radius: 8px;
            width: 90%;
            max-width: 500px;
            text-align: center;
            animation: slideIn 0.5s ease-out;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-100px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .success-icon {
            color: #4CAF50;
            font-size: 80px;
            margin-bottom: 20px;
        }

        .success-popup h2 {
            color: #333;
            margin-bottom: 20px;
            font-size: 24px;
        }

        .success-message {
            color: #666;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .order-info {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: left;
        }

        .order-info p {
            margin: 10px 0;
        }

        .continue-btn {
            background-color: #00796b;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .continue-btn:hover {
            background-color: #005a4f;
        }
        <?php include 'Assets\CSS\navbar.php'; ?>
        <?php include 'Assets\CSS\footer.php'; ?>
    </style>
</head>
<body>
<?php
    include 'navbar.php';
    ?>
    <div class="cart-container">
        <h1>Giỏ hàng của bạn</h1>
        
        <!-- Trường hợp giỏ hàng trống -->
        <div class="empty-cart" style="display: none;">
            <i class="fas fa-shopping-cart"></i>
            <h2>Giỏ hàng trống</h2>
            <p>Bạn chưa có sản phẩm nào trong giỏ hàng.</p>
            <a href="dungcuthethao.php" class="continue-shopping">Tiếp tục mua sắm</a>
        </div>

        <!-- Trường hợp có sản phẩm -->
        <div class="cart-content">
            <div class="cart-header">
                <div>Sản phẩm</div>
                <div>Đơn giá</div>
                <div>Số lượng</div>
                <div>Thành tiền</div>
                <div></div>
            </div>

            <!-- Mẫu một sản phẩm -->
            <div class="cart-item">
                <div class="product-info">
                    <img src="path/to/product-image.jpg" alt="Tên sản phẩm" class="product-image">
                    <div>
                        <h3>Bóng đá Nike Strike</h3>
                        <p>Màu: Trắng/Đen</p>
                    </div>
                </div>
                <div class="price">599,000đ</div>
                <div class="quantity-controls">
                    <button class="quantity-btn" onclick="updateQuantity('decrease')">-</button>
                    <input type="number" class="quantity-input" value="1" min="1">
                    <button class="quantity-btn" onclick="updateQuantity('increase')">+</button>
                </div>
                <div class="total">599,000đ</div>
                <button class="remove-btn">
                    <i class="fas fa-trash"></i>
                </button>
            </div>

            <!-- Thêm các sản phẩm khác tương tự -->

            <!-- Tổng kết giỏ hàng -->
            <div class="cart-summary">
                <div class="summary-row">
                    <span>Tạm tính:</span>
                    <span>0đ</span>
                </div>
                <div class="summary-row">
                    <span>Phí vận chuyển:</span>
                    <span>10,000đ</span>
                </div>
                <hr>
                <div class="summary-row" style="font-weight: bold;">
                    <span>Tổng cộng:</span>
                    <span>10,000đ</span>
                </div>
                <button class="checkout-btn">Tiến hành thanh toán</button>
            </div>
        </div>
    </div>

    <!-- Thêm php cho popup thanh toán -->
    <div class="payment-popup" id="paymentPopup">
        <div class="popup-content">
            <div class="popup-header">
                <h2>Thông tin thanh toán</h2>
                <button class="close-btn" onclick="closePaymentPopup()">×</button>
            </div>
            <div class="popup-body">
                <form id="paymentForm">
                    <div class="form-group">
                        <label>Họ và tên:</label>
                        <input type="text" id="fullName" value="Nguyễn Văn A" required>
                    </div>
                    <div class="form-group">
                        <label>Số điện thoại:</label>
                        <input type="tel" id="phone" value="0123456789" required>
                    </div>
                    <div class="form-group">
                        <label>Email:</label>
                        <input type="email" id="email" value="nguyenvana@email.com" required>
                    </div>
                    <div class="form-group">
                        <label>Địa chỉ:</label>
                        <textarea id="address" required>123 Đường ABC, Phường XYZ, Quận 1, TP. Hồ Chí Minh</textarea>
                    </div>
                    <div class="form-group">
                        <label>Phương thức thanh toán:</label>
                        <select id="paymentMethod" required>
                            <option value="cod">Thanh toán khi nhận hàng</option>
                            <option value="banking">Chuyển khoản ngân hàng</option>
                        </select>
                    </div>
                    <div class="order-summary">
                        <h3>Thông tin đơn hàng</h3>
                        <div class="summary-row">
                            <span>Tạm tính:</span>
                            <span id="popupSubtotal">0đ</span>
                        </div>
                        <div class="summary-row">
                            <span>Phí vận chuyển:</span>
                            <span id="popupShipping">10,000đ</span>
                        </div>
                        <div class="summary-row total">
                            <span>Tổng cộng:</span>
                            <span id="popupTotal">10,000đ</span>
                        </div>
                    </div>
                    <button type="submit" class="confirm-btn">Xác nhận đặt hàng</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Thêm popup QR code -->
    <div class="qr-popup" id="qrPopup">
        <div class="popup-content">
            <div class="popup-header">
                <h2>Thanh toán chuyển khoản</h2>
                <button class="close-btn" onclick="closeQRPopup()">×</button>
            </div>
            <div class="popup-body">
                <div class="bank-info">
                    <p>
                        <span><strong>Ngân hàng:</strong> MB Bank</span>
                    </p>
                    <p>
                        <span><strong>Chủ tài khoản:</strong> Huynh Tan Dung</span>
                        <button class="copy-btn" onclick="copyText('Huynh Tan Dung')">
                            <i class="fas fa-copy"></i>
                        </button>
                    </p>
                    <p>
                        <span><strong>Số tài khoản:</strong> 0855558999999</span>
                        <button class="copy-btn" onclick="copyText('0855558999999')">
                            <i class="fas fa-copy"></i>
                        </button>
                    </p>
                    <p>
                        <span><strong>Số tiền:</strong> <span id="qrAmount">0đ</span></span>
                        <button class="copy-btn" onclick="copyAmount()">
                            <i class="fas fa-copy"></i>
                        </button>
                    </p>
                    <p>
                        <span><strong>Nội dung CK:</strong> <span id="transferContent"></span></span>
                        <button class="copy-btn" onclick="copyTransferContent()">
                            <i class="fas fa-copy"></i>
                        </button>
                    </p>
                </div>
                <div class="qr-code">
                    <img src="images/91260ee3-dde3-46e8-bea1-84c4cec11d2e.jpg" alt="QR Code thanh toán">
                    <p class="note">Quét mã QR để thanh toán nhanh</p>
                </div>
            </div>
            <div class="popup-actions">
                <button class="back-btn" onclick="backToPayment()">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </button>
                <button class="confirm-payment-btn" onclick="confirmPayment()">
                    <i class="fas fa-check"></i> Đã thanh toán
                </button>
            </div>
        </div>
    </div>

    <!-- Thêm php cho popup thông báo thành công -->
    <div class="success-popup" id="successPopup">
        <div class="popup-content">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h2>Đặt hàng thành công!</h2>
            <div class="success-message">
                <p>Cảm ơn bạn đã mua hàng tại DatSanHub.</p>
                <p>Đơn hàng của bạn sẽ được giao trong vòng 1-2 ngày.</p>
                <div class="order-info">
                    <p><strong>Mã đơn hàng:</strong> <span id="orderCode"></span></p>
                    <p><strong>Thời gian đặt:</strong> <span id="orderTime"></span></p>
                </div>
            </div>
            <button class="continue-btn" onclick="closeSuccessPopup()">Tiếp tục mua sắm</button>
        </div>
    </div>

    <script>
        function formatCurrency(amount) {
            return amount.toLocaleString('vi-VN') + 'đ';
        }

        function updateQuantity(action) {
            const input = event.target.parentNode.querySelector('.quantity-input');
            const cartItem = event.target.closest('.cart-item');
            let value = parseInt(input.value);
            
            if (action === 'increase') {
                value++;
            } else if (action === 'decrease' && value > 1) {
                value--;
            }
            
            input.value = value;
            updateItemTotal(cartItem);
            updateCartTotal();
        }

        function updateItemTotal(cartItem) {
            const price = parseInt(cartItem.querySelector('.price').textContent.replace(/\D/g, ''));
            const quantity = parseInt(cartItem.querySelector('.quantity-input').value);
            const total = price * quantity;
            cartItem.querySelector('.total').textContent = formatCurrency(total);
        }

        function updateCartTotal() {
            const cartItems = document.querySelectorAll('.cart-item');
            let subtotal = 0;
            const shippingFee = 10000;

            cartItems.forEach(item => {
                const total = parseInt(item.querySelector('.total').textContent.replace(/\D/g, ''));
                subtotal += total;
            });

            const grandTotal = subtotal + shippingFee;

            document.querySelector('.cart-summary .summary-row:nth-child(1) span:last-child')
                .textContent = formatCurrency(subtotal);
            
            document.querySelector('.cart-summary .summary-row:nth-child(2) span:last-child')
                .textContent = formatCurrency(shippingFee);
            
            document.querySelector('.cart-summary .summary-row:nth-child(4) span:last-child')
                .textContent = formatCurrency(grandTotal);
        }

        // Xử lý xóa sản phẩm
        document.querySelectorAll('.remove-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const cartItem = this.closest('.cart-item');
                cartItem.remove();
                updateCartTotal();
                
                // Kiểm tra nếu giỏ hàng trống
                const remainingItems = document.querySelectorAll('.cart-item');
                if (remainingItems.length === 0) {
                    document.querySelector('.cart-content').style.display = 'none';
                    document.querySelector('.empty-cart').style.display = 'block';
                }
            });
        });

        // Thêm sự kiện cho input số lượng
        document.querySelectorAll('.quantity-input').forEach(input => {
            input.addEventListener('change', function() {
                const cartItem = this.closest('.cart-item');
                if (this.value < 1) this.value = 1;
                updateItemTotal(cartItem);
                updateCartTotal();
            });
        });

        // Khởi tạo tổng tiền ban đầu
        window.onload = function() {
            updateCartTotal();
        };

        // Thêm JavaScript cho popup thanh toán
        function showPaymentPopup() {
            document.getElementById('paymentPopup').style.display = 'flex';
            // Cập nhật thông tin đơn hàng trong popup
            document.getElementById('popupSubtotal').textContent = 
                document.querySelector('.cart-summary .summary-row:nth-child(1) span:last-child').textContent;
            document.getElementById('popupShipping').textContent = 
                document.querySelector('.cart-summary .summary-row:nth-child(2) span:last-child').textContent;
            document.getElementById('popupTotal').textContent = 
                document.querySelector('.cart-summary .summary-row:nth-child(4) span:last-child').textContent;
        }

        function closePaymentPopup() {
            document.getElementById('paymentPopup').style.display = 'none';
        }

        // Thêm sự kiện cho nút thanh toán
        document.querySelector('.checkout-btn').addEventListener('click', showPaymentPopup);

        // Xử lý form thanh toán
        document.getElementById('paymentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const paymentMethod = document.getElementById('paymentMethod').value;
            
            if (paymentMethod === 'cod') {
                closePaymentPopup();
                showSuccessPopup();
            } else if (paymentMethod === 'banking') {
                closePaymentPopup();
                showQRPopup();
            }
        });

        function generateOrderCode() {
            return 'DH' + Date.now().toString().slice(-8);
        }

        function formatDateTime(date) {
            const options = {
                hour: '2-digit',
                minute: '2-digit',
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            };
            return date.toLocaleString('vi-VN', options);
        }

        function showSuccessPopup() {
            const popup = document.getElementById('successPopup');
            document.getElementById('orderCode').textContent = generateOrderCode();
            document.getElementById('orderTime').textContent = formatDateTime(new Date());
            popup.style.display = 'flex';
        }

        function closeSuccessPopup() {
            document.getElementById('successPopup').style.display = 'none';
            window.location.href = 'dungcuthethao.php'; // Chuyển về trang dụng cụ thể thao
        }

        function showQRPopup() {
            const totalAmount = document.querySelector('.cart-summary .summary-row:nth-child(4) span:last-child').textContent;
            const orderCode = generateOrderCode();
            
            document.getElementById('qrAmount').textContent = totalAmount;
            document.getElementById('transferContent').textContent = orderCode;
            document.getElementById('qrPopup').style.display = 'flex';
        }

        function closeQRPopup() {
            document.getElementById('qrPopup').style.display = 'none';
        }

        // Đóng QR popup khi click bên ngoài
        window.onclick = function(event) {
            const paymentPopup = document.getElementById('paymentPopup');
            const qrPopup = document.getElementById('qrPopup');
            const successPopup = document.getElementById('successPopup');
            
            if (event.target == paymentPopup) {
                closePaymentPopup();
            }
            if (event.target == qrPopup) {
                closeQRPopup();
            }
            if (event.target == successPopup) {
                closeSuccessPopup();
            }
        }

        // Thêm các hàm xử lý cho popup QR
        function copyText(text) {
            navigator.clipboard.writeText(text).then(() => {
                showCopiedTooltip(event);
            });
        }

        function copyAmount() {
            const amount = document.getElementById('qrAmount').textContent;
            copyText(amount.replace(/[^\d]/g, '')); // Chỉ copy số
        }

        function copyTransferContent() {
            const content = document.getElementById('transferContent').textContent;
            copyText(content);
        }

        function showCopiedTooltip(event) {
            const tooltip = document.createElement('div');
            tooltip.className = 'copied-tooltip';
            tooltip.textContent = 'Đã sao chép';
            
            // Định vị tooltip
            tooltip.style.top = (event.pageY - 40) + 'px';
            tooltip.style.left = (event.pageX - 30) + 'px';
            
            document.body.appendChild(tooltip);
            
            setTimeout(() => {
                tooltip.style.opacity = '0';
                setTimeout(() => tooltip.remove(), 300);
            }, 1000);
        }

        function backToPayment() {
            closeQRPopup();
            showPaymentPopup();
        }

        function confirmPayment() {
            closeQRPopup();
            showSuccessPopup();
        }
    </script>
     <?php
 include 'footer.php';
 ?>
</body>
</php>

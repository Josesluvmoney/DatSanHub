<!DOCTYPE php>
<php lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đơn Hàng - DatSanHub</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Reset CSS */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Main content styles */
        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .order-tabs {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
            border-bottom: 2px solid #eee;
        }

        .tab-button {
            padding: 10px 20px;
            border: none;
            background: none;
            font-size: 16px;
            cursor: pointer;
            position: relative;
            color: #666;
        }

        .tab-button.active {
            color: #00796b;
            font-weight: bold;
        }

        .tab-button.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: #00796b;
        }

        .order-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .order-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            background-color: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .order-id {
            font-weight: bold;
            color: #00796b;
        }

        .order-status {
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 14px;
            font-weight: bold;
        }

        .status-completed {
            background-color: #e8f5e9;
            color: #2e7d32;
        }

        .status-cancelled {
            background-color: #ffebee;
            color: #c62828;
        }

        .status-pending {
            background-color: #fff3e0;
            color: #ef6c00;
        }

        .order-items {
            padding: 15px 0;
        }

        .order-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }

        .item-details {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .item-image {
            width: 60px;
            height: 60px;
            border-radius: 4px;
            object-fit: cover;
        }

        .item-info h4 {
            color: #333;
            margin-bottom: 5px;
        }

        .item-info p {
            color: #666;
            font-size: 14px;
        }

        .order-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }

        .order-total {
            font-weight: bold;
            font-size: 18px;
            color: #00796b;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .action-btn {
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .view-btn {
            background-color: #00796b;
            color: white;
        }

        .view-btn:hover {
            background-color: #005a4f;
        }

        .cancel-btn {
            background-color: #f44336;
            color: white;
        }

        .cancel-btn:hover {
            background-color: #d32f2f;
        }

        /* Thêm CSS cho phần lọc ngày */
        .filter-tools {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f5f5f5;
            border-radius: 8px;
        }

        .date-filter {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .date-input {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .filter-btn {
            padding: 8px 15px;
            background-color: #00796b;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .filter-btn:hover {
            background-color: #005a4f;
        }

        .reset-btn {
            padding: 8px 15px;
            background-color: #666;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .reset-btn:hover {
            background-color: #555;
        }

        /* CSS cho popup chi tiết đơn hàng */
        .order-detail-popup {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }

        .order-detail-content {
            position: relative;
            background-color: white;
            width: 90%;
            max-width: 800px;
            margin: 50px auto;
            padding: 30px;
            border-radius: 8px;
            max-height: 90vh;
            overflow-y: auto;
        }

        .close-popup {
            position: absolute;
            top: 15px;
            right: 15px;
            font-size: 24px;
            cursor: pointer;
            color: #666;
        }

        .order-detail-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid #eee;
        }

        .order-detail-info {
            flex: 1;
        }

        .order-detail-info h2 {
            color: #00796b;
            margin-bottom: 10px;
        }

        .order-detail-status {
            margin-left: 20px;
        }

        .order-timeline {
            margin: 30px 0;
            padding: 20px;
            background-color: #f5f5f5;
            border-radius: 8px;
        }

        .timeline-item {
            display: flex;
            align-items: start;
            margin-bottom: 15px;
            position: relative;
        }

        .timeline-item:not(:last-child)::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 30px;
            bottom: -15px;
            width: 2px;
            background-color: #ddd;
        }

        .timeline-icon {
            width: 32px;
            height: 32px;
            background-color: #00796b;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            margin-right: 15px;
        }

        .timeline-content {
            flex: 1;
        }

        .timeline-time {
            color: #666;
            font-size: 14px;
        }

        .order-detail-items {
            margin: 20px 0;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            padding: 15px;
            border-bottom: 1px solid #eee;
        }

        .detail-item:last-child {
            border-bottom: none;
        }

        .customer-info {
            margin: 20px 0;
            padding: 20px;
            background-color: #f5f5f5;
            border-radius: 8px;
        }

        .customer-info h3 {
            color: #00796b;
            margin-bottom: 15px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
        }

        .info-label {
            color: #666;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .info-value {
            font-weight: bold;
        }

        .payment-info {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #eee;
        }

        .payment-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .payment-total {
            font-size: 20px;
            font-weight: bold;
            color: #00796b;
        }

        /* CSS cho popup thông báo */
        .notification-popup {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1100;
        }

        .notification-content {
            position: relative;
            background-color: white;
            width: 90%;
            max-width: 400px;
            margin: 150px auto;
            padding: 30px;
            border-radius: 8px;
            text-align: center;
        }

        .notification-icon {
            font-size: 48px;
            margin-bottom: 20px;
        }

        .notification-success .notification-icon {
            color: #4caf50;
        }

        .notification-warning .notification-icon {
            color: #ff9800;
        }

        .notification-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #333;
        }

        .notification-message {
            color: #666;
            margin-bottom: 20px;
            line-height: 1.5;
        }

        .notification-buttons {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .notification-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .confirm-btn {
            background-color: #00796b;
            color: white;
        }

        .confirm-btn:hover {
            background-color: #005a4f;
        }

        .cancel-notification-btn {
            background-color: #f44336;
            color: white;
        }

        .cancel-notification-btn:hover {
            background-color: #d32f2f;
        }

        .back-btn {
            background-color: #666;
            color: white;
        }

        .back-btn:hover {
            background-color: #555;
        }

        .notification-btn i {
            margin-right: 5px;
        }

        .notification-message small {
            font-size: 13px;
            opacity: 0.8;
        }

        /* CSS cho logo */
        .logo-text {
            color: white;
            text-decoration: none;
            font-size: 24px;
            font-weight: bold;
        }
        <?php include 'Assets\CSS\navbar.css'; ?>
<?php include 'Assets\CSS\footer.css'; ?>
    </style>
</head>
<body>
<?php
    include 'navbar.php';
    ?>

    <div class="container">
        <div class="order-tabs">
            <button class="tab-button active">Tất cả đơn hàng</button>
            <button class="tab-button">Đang xử lý</button>
            <button class="tab-button">Đã hoàn thành</button>
            <button class="tab-button">Đã hủy</button>
        </div>

        <div class="filter-tools">
            <div class="date-filter">
                <label for="startDate">Từ ngày:</label>
                <input type="date" id="startDate" class="date-input">
                
                <label for="endDate">Đến ngày:</label>
                <input type="date" id="endDate" class="date-input">
            </div>
            
            <button class="filter-btn" onclick="filterByDate()">
                <i class="fas fa-filter"></i> Lọc
            </button>
            
            <button class="reset-btn" onclick="resetFilter()">
                <i class="fas fa-undo"></i> Đặt lại
            </button>
        </div>

        <div class="order-list">
            <!-- Đơn hàng thuê sân -->
            <div class="order-card">
                <div class="order-header">
                    <span class="order-id">Đơn hàng #DH123456</span>
                    <span class="order-status status-completed">Đã hoàn thành</span>
                </div>
                <div class="order-items">
                    <div class="order-item">
                        <div class="item-details">
                            <img src="path/to/court-image.jpg" alt="Sân bóng đá" class="item-image">
                            <div class="item-info">
                                <h4>Thuê sân bóng đá số 1</h4>
                                <p>Ngày: 15/03/2024</p>
                                <p>Thời gian: 18:00 - 20:00</p>
                            </div>
                        </div>
                        <div class="item-price">500.000đ</div>
                    </div>
                </div>
                <div class="order-footer">
                    <div class="order-total">Tổng tiền: 500.000đ</div>
                    <div class="action-buttons">
                        <button class="action-btn view-btn">Xem chi tiết</button>
                    </div>
                </div>
            </div>

            <!-- Đơn hàng mua dụng cụ -->
            <div class="order-card">
                <div class="order-header">
                    <span class="order-id">Đơn hàng #DH123457</span>
                    <span class="order-status status-pending">Đang xử lý</span>
                </div>
                <div class="order-items">
                    <div class="order-item">
                        <div class="item-details">
                            <img src="path/to/equipment-image.jpg" alt="Bóng đá" class="item-image">
                            <div class="item-info">
                                <h4>Bóng đá Nike Strike</h4>
                                <p>Số lượng: 2</p>
                            </div>
                        </div>
                        <div class="item-price">1.198.000đ</div>
                    </div>
                </div>
                <div class="order-footer">
                    <div class="order-total">Tổng tiền: 1.198.000đ</div>
                    <div class="action-buttons">
                        <button class="action-btn view-btn">Xem chi tiết</button>
                        <button class="action-btn cancel-btn">Hủy đơn</button>
                    </div>
                </div>
            </div>

            <!-- Đơn hàng thuê dụng cụ -->
            <div class="order-card">
                <div class="order-header">
                    <span class="order-id">Đơn hàng #DH123458</span>
                    <span class="order-status status-cancelled">Đã hủy</span>
                </div>
                <div class="order-items">
                    <div class="order-item">
                        <div class="item-details">
                            <img src="path/to/rental-equipment.jpg" alt="Vợt cầu lông" class="item-image">
                            <div class="item-info">
                                <h4>Thuê vợt cầu lông Yonex</h4>
                                <p>Thời gian thuê: 3 ngày</p>
                                <p>Ngày bắt đầu: 10/03/2024</p>
                            </div>
                        </div>
                        <div class="item-price">240.000đ</div>
                    </div>
                </div>
                <div class="order-footer">
                    <div class="order-total">Tổng tiền: 240.000đ</div>
                    <div class="action-buttons">
                        <button class="action-btn view-btn">Xem chi tiết</button>
                    </div>
                </div>
            </div>

            <!-- Đơn hàng đã thanh toán chuyển khoản -->
            <div class="order-card" data-payment-method="paid">
                <div class="order-header">
                    <span class="order-id">Đơn hàng #DH123459</span>
                    <span class="order-status status-pending">Đang xử lý</span>
                </div>
                <div class="order-items">
                    <div class="order-item">
                        <div class="item-details">
                            <img src="path/to/court-image.jpg" alt="Sân bóng đá" class="item-image">
                            <div class="item-info">
                                <h4>Thuê sân bóng đá số 2</h4>
                                <p>Ngày: 20/03/2024</p>
                                <p>Thời gian: 19:00 - 21:00</p>
                                <p>Phương thức thanh toán: Chuyển khoản (Đã thanh toán)</p>
                            </div>
                        </div>
                        <div class="item-price">600.000đ</div>
                    </div>
                </div>
                <div class="order-footer">
                    <div class="order-total">Tổng tiền: 600.000đ</div>
                    <div class="action-buttons">
                        <button class="action-btn view-btn">Xem chi tiết</button>
                        <button class="action-btn cancel-btn">Hủy đơn</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Popup Chi tiết đơn hàng -->
    <div class="order-detail-popup" id="orderDetailPopup">
        <div class="order-detail-content">
            <span class="close-popup" onclick="closeOrderDetail()">&times;</span>
            
            <div class="order-detail-header">
                <div class="order-detail-info">
                    <h2>Chi tiết đơn hàng #<span id="detailOrderId"></span></h2>
                    <p>Ngày đặt: <span id="detailOrderDate"></span></p>
                </div>
                <div class="order-detail-status">
                    <span id="detailOrderStatus" class="order-status"></span>
                </div>
            </div>

            <div class="order-timeline">
                <h3>Trạng thái đơn hàng</h3>
                <div id="orderTimeline">
                    <!-- Timeline items will be inserted here -->
                </div>
            </div>

            <div class="customer-info">
                <h3>Thông tin khách hàng</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Họ tên</span>
                        <span class="info-value" id="customerName"></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Số điện thoại</span>
                        <span class="info-value" id="customerPhone"></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Email</span>
                        <span class="info-value" id="customerEmail"></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Địa chỉ</span>
                        <span class="info-value" id="customerAddress"></span>
                    </div>
                </div>
            </div>

            <div class="order-detail-items">
                <h3>Chi tiết sản phẩm/dịch vụ</h3>
                <div id="detailItems">
                    <!-- Detail items will be inserted here -->
                </div>
            </div>

            <div class="payment-info">
                <div class="payment-row">
                    <span>Tạm tính</span>
                    <span id="subtotal"></span>
                </div>
                <div class="payment-row">
                    <span>Phí dịch vụ</span>
                    <span id="serviceFee"></span>
                </div>
                <div class="payment-row payment-total">
                    <span>Tổng cộng</span>
                    <span id="totalAmount"></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Popup Thông báo -->
    <div class="notification-popup" id="notificationPopup">
        <div class="notification-content">
            <span class="close-popup" onclick="closeNotification()">&times;</span>
            
            <div class="notification-icon">
                <i class="fas fa-check"></i>
            </div>
            
            <div class="notification-title">Thông báo</div>
            
            <div class="notification-message">Đơn hàng đã được hủy thành công!</div>
            
            <div class="notification-buttons">
                <button class="notification-btn confirm-btn" onclick="closeNotification()">Đồng ý</button>
                <button class="notification-btn cancel-notification-btn" onclick="closeNotification()">Hủy</button>
            </div>
        </div>
    </div>

    <!-- Popup xác nhận hủy đơn -->
    <div class="notification-popup" id="cancelConfirmPopup">
        <div class="notification-content">
            <div class="notification-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="notification-title">Xác nhận hủy đơn</div>
            <div class="notification-message">
                Bạn có chắc chắn muốn hủy đơn hàng này không?
            </div>
            <div class="notification-buttons">
                <button class="notification-btn confirm-btn" onclick="confirmCancel()">Xác nhận</button>
                <button class="notification-btn cancel-notification-btn" onclick="closeNotification('cancelConfirmPopup')">Đóng</button>
            </div>
        </div>
    </div>

    <!-- Popup thông báo hủy thành công - Thanh toán khi nhận hàng -->
    <div class="notification-popup" id="cancelSuccessPopup">
        <div class="notification-content notification-success">
            <div class="notification-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="notification-title">Hủy đơn thành công</div>
            <div class="notification-message">
                Đơn hàng của bạn đã được hủy thành công. Bạn sẽ nhận được email xác nhận trong vòng 24 giờ.
            </div>
            <div class="notification-buttons">
                <button class="notification-btn confirm-btn" onclick="closeNotification('cancelSuccessPopup')">Đóng</button>
            </div>
        </div>
    </div>

    <!-- Popup thông báo hủy đơn - Đã thanh toán -->
    <div class="notification-popup" id="cancelPaidPopup">
        <div class="notification-content notification-success">
            <div class="notification-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="notification-title">Hủy đơn hàng thành công</div>
            <div class="notification-message">
                Đơn hàng của bạn đã được hủy thành công.<br>
                Vui lòng liên hệ số hotline 0963.922.597 để được hỗ trợ hoàn tiền trong vòng 24h.<br>
                <small style="color: #666; display: block; margin-top: 10px;">
                    *Lưu ý: Thời gian hoàn tiền có thể mất 1-2 ngày làm việc tùy theo ngân hàng của bạn.
                </small>
            </div>
            <div class="notification-buttons">
                <button class="notification-btn confirm-btn" onclick="window.location.href='tel:0963922597'">
                    <i class="fas fa-phone"></i> Gọi ngay
                </button>
            </div>
        </div>
    </div>

    <!-- Popup xác nhận hủy đơn đã thanh toán -->
    <div class="notification-popup" id="cancelPaidConfirmPopup">
        <div class="notification-content notification-warning">
            <div class="notification-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="notification-title">Xác nhận hủy đơn đã thanh toán</div>
            <div class="notification-message">
                Đơn hàng này đã được thanh toán. Bạn có chắc chắn muốn hủy không?<br>
                Sau khi hủy, bạn sẽ cần liên hệ với chúng tôi để được hoàn tiền.
            </div>
            <div class="notification-buttons">
                <button class="notification-btn confirm-btn" onclick="confirmPaidCancel()">Xác nhận hủy</button>
                <button class="notification-btn cancel-notification-btn" onclick="closeNotification('cancelPaidConfirmPopup')">Đóng</button>
            </div>
        </div>
    </div>

    <script>
        // Xử lý chuyển tab
        const tabButtons = document.querySelectorAll('.tab-button');
        
        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                // Remove active class from all buttons
                tabButtons.forEach(btn => btn.classList.remove('active'));
                // Add active class to clicked button
                button.classList.add('active');
                
                // Thêm logic lọc đơn hàng theo trạng thái tại đây
            });
        });

        // Hàm lọc theo ngày
        function filterByDate() {
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            
            if (!startDate || !endDate) {
                alert('Vui lòng chọn khoảng thời gian');
                return;
            }

            const start = new Date(startDate);
            const end = new Date(endDate);
            
            if (start > end) {
                alert('Ngày bắt đầu phải nhỏ hơn ngày kết thúc');
                return;
            }

            const orderCards = document.querySelectorAll('.order-card');
            
            orderCards.forEach(card => {
                // Lấy ngày từ thẻ order-card
                const orderDateText = card.querySelector('.item-info p:first-of-type').textContent;
                const orderDate = new Date(orderDateText.replace('Ngày: ', ''));
                
                if (orderDate >= start && orderDate <= end) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        // Hàm đặt lại bộ lọc
        function resetFilter() {
            document.getElementById('startDate').value = '';
            document.getElementById('endDate').value = '';
            
            const orderCards = document.querySelectorAll('.order-card');
            orderCards.forEach(card => {
                card.style.display = 'block';
            });
        }

        // Thiết lập giá trị mặc định cho input date
        document.addEventListener('DOMContentLoaded', function() {
            // Thiết lập ngày bắt đầu là đầu tháng hiện tại
            const today = new Date();
            const startOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
            document.getElementById('startDate').value = startOfMonth.toISOString().split('T')[0];
            
            // Thiết lập ngày kết thúc là ngày hiện tại
            document.getElementById('endDate').value = today.toISOString().split('T')[0];
        });

        // Kết hợp lọc theo tab và ngày
        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                tabButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
                
                const status = button.textContent.trim();
                const orderCards = document.querySelectorAll('.order-card');
                
                orderCards.forEach(card => {
                    const cardStatus = card.querySelector('.order-status').textContent;
                    
                    if (status === 'Tất cả đơn hàng' || cardStatus === status) {
                        // Kiểm tra điều kiện ngày nếu đang có lọc theo ngày
                        const startDate = document.getElementById('startDate').value;
                        const endDate = document.getElementById('endDate').value;
                        
                        if (startDate && endDate) {
                            const orderDateText = card.querySelector('.item-info p:first-of-type').textContent;
                            const orderDate = new Date(orderDateText.replace('Ngày: ', ''));
                            const start = new Date(startDate);
                            const end = new Date(endDate);
                            
                            card.style.display = (orderDate >= start && orderDate <= end) ? 'block' : 'none';
                        } else {
                            card.style.display = 'block';
                        }
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });

        // Hàm hiển thị popup chi tiết đơn hàng
        function showOrderDetail(orderId) {
            const popup = document.getElementById('orderDetailPopup');
            
            // Giả lập dữ liệu đơn hàng (trong thực tế sẽ lấy từ backend)
            const orderData = {
                id: orderId,
                date: '15/03/2024',
                status: 'Đã hoàn thành',
                timeline: [
                    { time: '15/03/2024 18:00', status: 'Đơn hàng hoàn thành', icon: 'fa-check' },
                    { time: '15/03/2024 17:55', status: 'Đã thanh toán', icon: 'fa-credit-card' },
                    { time: '15/03/2024 15:00', status: 'Đơn hàng được xác nhận', icon: 'fa-clipboard-check' },
                    { time: '15/03/2024 14:30', status: 'Đơn hàng được tạo', icon: 'fa-shopping-cart' }
                ],
                customer: {
                    name: 'Nguyễn Văn A',
                    phone: '0123456789',
                    email: 'nguyenvana@email.com',
                    address: '123 Đường ABC, Quận 1, TP.HCM'
                },
                items: [
                    {
                        name: 'Thuê sân bóng đá số 1',
                        time: '18:00 - 20:00',
                        price: '500.000đ'
                    }
                ],
                payment: {
                    subtotal: '500.000đ',
                    serviceFee: '0đ',
                    total: '500.000đ'
                }
            };

            // Cập nhật thông tin vào popup
            document.getElementById('detailOrderId').textContent = orderData.id;
            document.getElementById('detailOrderDate').textContent = orderData.date;
            document.getElementById('detailOrderStatus').textContent = orderData.status;
            document.getElementById('detailOrderStatus').className = `order-status status-${orderData.status.toLowerCase().replace(' ', '-')}`;

            // Cập nhật timeline
            const timelinephp = orderData.timeline.map(item => `
                <div class="timeline-item">
                    <div class="timeline-icon">
                        <i class="fas ${item.icon}"></i>
                    </div>
                    <div class="timeline-content">
                        <div class="timeline-status">${item.status}</div>
                        <div class="timeline-time">${item.time}</div>
                    </div>
                </div>
            `).join('');
            document.getElementById('orderTimeline').innerphp = timelinephp;

            // Cập nhật thông tin khách hàng
            document.getElementById('customerName').textContent = orderData.customer.name;
            document.getElementById('customerPhone').textContent = orderData.customer.phone;
            document.getElementById('customerEmail').textContent = orderData.customer.email;
            document.getElementById('customerAddress').textContent = orderData.customer.address;

            // Cập nhật chi tiết sản phẩm
            const itemsphp = orderData.items.map(item => `
                <div class="detail-item">
                    <div class="item-info">
                        <h4>${item.name}</h4>
                        <p>${item.time}</p>
                    </div>
                    <div class="item-price">${item.price}</div>
                </div>
            `).join('');
            document.getElementById('detailItems').innerphp = itemsphp;

            // Cập nhật thông tin thanh toán
            document.getElementById('subtotal').textContent = orderData.payment.subtotal;
            document.getElementById('serviceFee').textContent = orderData.payment.serviceFee;
            document.getElementById('totalAmount').textContent = orderData.payment.total;

            // Hiển thị popup
            popup.style.display = 'block';
        }

        // Hàm đóng popup
        function closeOrderDetail() {
            document.getElementById('orderDetailPopup').style.display = 'none';
        }

        // Cập nhật sự kiện click cho các nút "Xem chi tiết"
        document.querySelectorAll('.view-btn').forEach(button => {
            button.addEventListener('click', function() {
                const orderCard = this.closest('.order-card');
                const orderId = orderCard.querySelector('.order-id').textContent.replace('Đơn hàng #', '');
                showOrderDetail(orderId);
            });
        });

        // Thêm event listener để đóng popup khi click bên ngoài
        document.addEventListener('click', function(event) {
            const popup = document.getElementById('orderDetailPopup');
            const popupContent = document.querySelector('.order-detail-content');
            
            // Kiểm tra nếu popup đang hiển thị và click không phải vào nội dung popup
            if (popup.style.display === 'block' && 
                !popupContent.contains(event.target) && 
                !event.target.classList.contains('view-btn')) {
                closeOrderDetail();
            }
        });

        // Ngăn chặn sự kiện click trong nội dung popup lan truyền ra ngoài
        document.querySelector('.order-detail-content').addEventListener('click', function(event) {
            event.stopPropagation();
        });

        // Hàm đóng popup thông báo
        function closeNotification() {
            document.getElementById('notificationPopup').style.display = 'none';
        }

        // Biến lưu trữ thông tin đơn hàng đang được hủy
        let currentCancelOrder = null;

        // Hàm hiển thị popup xác nhận hủy đơn
        function showCancelConfirmation(orderCard) {
            currentCancelOrder = orderCard;
            const isPaid = orderCard.dataset.paymentMethod === 'paid';
            
            if (isPaid) {
                document.getElementById('cancelPaidConfirmPopup').style.display = 'block';
            } else {
                document.getElementById('cancelConfirmPopup').style.display = 'block';
            }
        }

        // Hàm xác nhận hủy đơn
        function confirmCancel() {
            if (currentCancelOrder) {
                closeNotification('cancelConfirmPopup');
                document.getElementById('cancelSuccessPopup').style.display = 'block';
                
                // Cập nhật trạng thái đơn hàng trên giao diện
                const statusElement = currentCancelOrder.querySelector('.order-status');
                statusElement.textContent = 'Đã hủy';
                statusElement.className = 'order-status status-cancelled';
                
                // Ẩn nút hủy đơn
                const cancelButton = currentCancelOrder.querySelector('.cancel-btn');
                if (cancelButton) {
                    cancelButton.style.display = 'none';
                }
                
                currentCancelOrder = null;
            }
        }

        // Thêm hàm xử lý hủy đơn đã thanh toán
        function confirmPaidCancel() {
            if (currentCancelOrder) {
                closeNotification('cancelPaidConfirmPopup');
                document.getElementById('cancelPaidPopup').style.display = 'block';
                
                // Cập nhật trạng thái đơn hàng trên giao diện
                const statusElement = currentCancelOrder.querySelector('.order-status');
                statusElement.textContent = 'Đã hủy';
                statusElement.className = 'order-status status-cancelled';
                
                // Ẩn nút hủy đơn
                const cancelButton = currentCancelOrder.querySelector('.cancel-btn');
                if (cancelButton) {
                    cancelButton.style.display = 'none';
                }
                
                currentCancelOrder = null;
            }
        }

        // Hàm đóng popup thông báo
        function closeNotification(popupId) {
            document.getElementById(popupId).style.display = 'none';
        }

        // Cập nhật sự kiện click cho các nút "Hủy đơn"
        document.querySelectorAll('.cancel-btn').forEach(button => {
            button.addEventListener('click', function() {
                const orderCard = this.closest('.order-card');
                showCancelConfirmation(orderCard);
            });
        });

        // Thêm vào event listener đóng popup khi click bên ngoài
        document.addEventListener('click', function(event) {
            const popups = document.querySelectorAll('.notification-popup');
            popups.forEach(popup => {
                const content = popup.querySelector('.notification-content');
                if (popup.style.display === 'block' && 
                    !content.contains(event.target) && 
                    !event.target.classList.contains('cancel-btn')) {
                    popup.style.display = 'none';
                }
            });
        });

        // Ngăn chặn sự kiện click trong nội dung popup lan truyền ra ngoài
        document.querySelectorAll('.notification-content').forEach(content => {
            content.addEventListener('click', function(event) {
                event.stopPropagation();
            });
        });
    </script>
 <?php
 include 'footer.php';
 ?> 
</body>
</php> 
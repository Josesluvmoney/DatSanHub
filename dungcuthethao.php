<?php
session_start();
?>
<!DOCTYPE html>
<php lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dụng Cụ Thể Thao</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Reset CSS */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Container Styles */
        .container {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            gap: 20px;
        }

        .sidebar {
            width: 250px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
        }

        .main-content {
            flex: 1;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
        }

        /* Equipment Card Styles */
        .equipment-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            padding: 20px;
        }

        .equipment-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            transition: transform 0.3s ease;
            min-height: 380px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .equipment-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .equipment-image {
            width: 150px;
            height: 150px;
            object-fit: cover;
            margin-bottom: 10px;
        }

        .equipment-name {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .equipment-price {
            color: #e65100;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .equipment-quantity {
            color: #666;
            margin-bottom: 10px;
        }

        .add-to-cart-btn {
            background-color: #00796b;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        .add-to-cart-btn:hover {
            background-color: #004d40;
        }

        /* Category Styles */
        .category-list {
            list-style: none;
        }

        .category-item {
            padding: 10px;
            cursor: pointer;
            border-bottom: 1px solid #eee;
        }

        .category-item:hover {
            background-color: #f5f5f5;
        }

        .category-item.active {
            background-color: #e0f2f1;
            color: #00796b;
        }

        /* Thêm CSS cho danh mục */
        .category-header {
            font-weight: bold;
            color: #00796b;
            padding: 15px 10px 5px;
            border-bottom: 2px solid #00796b;
            margin-top: 10px;
        }

        /* Chỉnh sửa card cho sản phẩm thuê */
        .equipment-card.rental {
            border: 1px solid #00796b;
        }

        .rental .equipment-price {
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .rental .price-per-day {
            color: #00796b;
            font-size: 0.9em;
            font-weight: bold;
        }

        .rental .deposit {
            font-size: 0.85em;
            color: #666;
        }

        .rental .no-deposit {
            font-size: 0.8em;
            color: #00796b;
            font-style: italic;
        }

        .rental .add-to-cart-btn {
            background-color: #004d40;
        }

        .rental .add-to-cart-btn:hover {
            background-color: #00695c;
        }

        <?php 
        include 'assets/CSS/navbar.css';
        include 'assets/CSS/footer.css';
        ?>
    </style>
</head>
<body>
<?php
    include 'navbar.php';
    ?>
    <div class="container">
        <div class="sidebar">
            <h2>Danh mục</h2>
            <ul class="category-list">
                <li class="category-item active" data-category="all">Tất cả</li>
                <li class="category-header">Mua dụng cụ</li>
                <li class="category-item" data-category="football-buy">Bóng đá</li>
                <li class="category-item" data-category="badminton-buy">Cầu lông</li>
                <li class="category-item" data-category="tennis-buy">Tennis</li>
                <li class="category-item" data-category="basketball-buy">Bóng rổ</li>
                <li class="category-item" data-category="volleyball-buy">Bóng chuyền</li>
                <li class="category-item" data-category="pickleball-buy">Pickleball</li>
                <li class="category-header">Thuê dụng cụ</li>
                <li class="category-item" data-category="football-rent">Bóng đá</li>
                <li class="category-item" data-category="badminton-rent">Cầu lông</li>
                <li class="category-item" data-category="tennis-rent">Tennis</li>
                <li class="category-item" data-category="basketball-rent">Bóng rổ</li>
                <li class="category-item" data-category="volleyball-rent">Bóng chuyền</li>
                <li class="category-item" data-category="pickleball-rent">Pickleball</li>
            </ul>
        </div>

        <div class="main-content">
            <div class="equipment-grid" id="equipmentGrid">
                <!-- Dụng cụ sẽ được thêm vào đây bằng JavaScript -->
            </div>
        </div>
    </div>

    <script>
        // Danh sách dụng cụ thể thao mẫu
        const equipmentList = [
            // Dụng cụ bán
            {
                id: 1,
                name: 'Bóng đá Nike Strike',
                category: 'football-buy',
                price: 599000,
                quantity: 15,
                image: 'path/to/football.jpg',
                type: 'buy'
            },
            {
                id: 2,
                name: 'Vợt cầu lông Yonex',
                category: 'badminton-buy',
                price: 1200000,
                quantity: 8,
                image: 'path/to/badminton.jpg',
                type: 'buy'
            },
            // Dụng cụ cho thuê
            {
                id: 3,
                name: 'Bóng đá Nike Strike (Cho thuê)',
                category: 'football-rent',
                pricePerDay: 50000,
                deposit: 20000,
                quantity: 10,
                image: 'path/to/football-rent.jpg',
                type: 'rent'
            },
            {
                id: 4,
                name: 'Vợt cầu lông Yonex (Cho thuê)',
                category: 'badminton-rent',
                pricePerDay: 80000,
                deposit: 20000,
                quantity: 5,
                image: 'path/to/badminton-rent.jpg',
                type: 'rent'
            }
        ];

        // Hàm hiển thị dụng cụ
        function displayEquipment(category = 'all') {
            const grid = document.getElementById('equipmentGrid');
            grid.innerphp = '';

            equipmentList
                .filter(item => category === 'all' || item.category === category)
                .forEach(item => {
                    if (item.type === 'buy') {
                        // Card cho sản phẩm bán
                        const card = `
                            <div class="equipment-card">
                                <img src="${item.image}" alt="${item.name}" class="equipment-image">
                                <div class="equipment-name">${item.name}</div>
                                <div class="equipment-price">${item.price.toLocaleString()}đ</div>
                                <div class="equipment-quantity">Còn lại: ${item.quantity}</div>
                                <button class="add-to-cart-btn">
                                    <i class="fas fa-shopping-cart"></i> Thêm vào giỏ
                                </button>
                            </div>
                        `;
                        grid.innerphp += card;
                    } else {
                        // Card cho sản phẩm thuê
                        const card = `
                            <div class="equipment-card rental">
                                <img src="${item.image}" alt="${item.name}" class="equipment-image">
                                <div class="equipment-name">${item.name}</div>
                                <div class="equipment-price">
                                    <span class="price-per-day">${item.pricePerDay.toLocaleString()}đ/ngày</span>
                                    <span class="deposit">Đặt cọc online: ${item.deposit.toLocaleString()}đ</span>
                                    <span class="no-deposit">* Không cần đặt cọc khi thuê trực tiếp tại sân</span>
                                </div>
                                <div class="equipment-quantity">Còn lại: ${item.quantity}</div>
                                <button class="add-to-cart-btn">
                                    <i class="fas fa-shopping-cart"></i> Thuê ngay
                                </button>
                            </div>
                        `;
                        grid.innerphp += card;
                    }
                });
        }

        // Xử lý sự kiện chọn danh mục
        document.querySelectorAll('.category-item').forEach(item => {
            item.addEventListener('click', function() {
                // Xóa active class từ tất cả các items
                document.querySelectorAll('.category-item').forEach(i => {
                    i.classList.remove('active');
                });
                
                // Thêm active class cho item được chọn
                this.classList.add('active');
                
                // Hiển thị dụng cụ theo danh mục
                const category = this.getAttribute('data-category');
                displayEquipment(category);
            });
        });

        // Hiển thị tất cả dụng cụ khi trang được tải
        window.onload = () => displayEquipment();
    </script>
     <?php
 include 'footer.php';
 ?>
</body>
</php>

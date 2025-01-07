<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$message = '';
$messageType = '';

try {
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Xử lý form submit
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $fullname = trim($_POST['fullname']);
        $phone = trim($_POST['phone']);
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        // Lấy thông tin người dùng hiện tại
        $stmt = $conn->prepare("SELECT * FROM tbl_user WHERE id_TK = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        $can_update = true;
        $should_update_password = false;

        // Kiểm tra số điện thoại trùng
        if ($phone !== $user['Phone']) {
            $stmt = $conn->prepare("SELECT id_TK FROM tbl_user WHERE Phone = ? AND id_TK != ?");
            $stmt->bind_param("si", $phone, $user_id);
            $stmt->execute();
            if ($stmt->get_result()->num_rows > 0) {
                $message = "Số điện thoại đã được sử dụng!";
                $messageType = "error";
                $can_update = false;
            }
            $stmt->close();
        }

        // Kiểm tra mật khẩu nếu có nhập
        if (!empty($current_password)) {
            if (md5($current_password) !== $user['Password']) {
                $message = "Mật khẩu hiện tại không đúng!";
                $messageType = "error";
                $can_update = false;
            } elseif ($new_password !== $confirm_password) {
                $message = "Mật khẩu mới không khớp!";
                $messageType = "error";
                $can_update = false;
            } else {
                $should_update_password = true;
            }
        }

        // Thực hiện cập nhật nếu không có lỗi
        if ($can_update) {
            if ($should_update_password) {
                $new_password_hash = md5($new_password);
                $stmt = $conn->prepare("UPDATE tbl_user SET Fullname = ?, Phone = ?, Password = ? WHERE id_TK = ?");
                $stmt->bind_param("sssi", $fullname, $phone, $new_password_hash, $user_id);
            } else {
                $stmt = $conn->prepare("UPDATE tbl_user SET Fullname = ?, Phone = ? WHERE id_TK = ?");
                $stmt->bind_param("ssi", $fullname, $phone, $user_id);
            }

            if ($stmt->execute()) {
                $message = "Cập nhật thông tin thành công!";
                $messageType = "success";
            } else {
                $message = "Có lỗi xảy ra, vui lòng thử lại!";
                $messageType = "error";
            }
            $stmt->close();
        }
    }

    // Lấy thông tin người dùng mới nhất để hiển thị
    $stmt = $conn->prepare("SELECT * FROM tbl_user WHERE id_TK = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $stmt->close();

} catch (Exception $e) {
    $message = "Có lỗi xảy ra: " . $e->getMessage();
    $messageType = "error";
} finally {
    if (isset($conn)) {
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin cá nhân</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        <?php 
            include 'assets/CSS/navbar.css';
            include 'assets/CSS/footer.css';
            include 'assets/CSS/thongtincanhan.css';
        ?>
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container">
        <div class="profile-container">
            <h1>Thông tin cá nhân</h1>
            
            <?php if (!empty($message)): ?>
                <div class="message <?php echo $messageType; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="" class="profile-form">
                <div class="form-group">
                    <label for="fullname">Họ và tên:</label>
                    <input type="text" id="fullname" name="fullname" 
                           value="<?php echo htmlspecialchars($user['Fullname']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="phone">Số điện thoại:</label>
                    <input type="tel" id="phone" name="phone" 
                           value="<?php echo htmlspecialchars($user['Phone']); ?>" required>
                </div>

                <div class="password-section">
                    <h2>Đổi mật khẩu</h2>
                    <div class="form-group">
                        <label for="current_password">Mật khẩu hiện tại:</label>
                        <input type="password" id="current_password" name="current_password">
                    </div>

                    <div class="form-group">
                        <label for="new_password">Mật khẩu mới:</label>
                        <input type="password" id="new_password" name="new_password">
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Xác nhận mật khẩu mới:</label>
                        <input type="password" id="confirm_password" name="confirm_password">
                    </div>
                </div>

                <div class="button-group">
                    <button type="submit" class="save-button">
                        <i class="fas fa-save"></i> Lưu thay đổi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>

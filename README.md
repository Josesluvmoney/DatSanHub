# Cấp quyền (trên Linux/Unix)
sudo chmod -R 755 assets/images
sudo chown -R www-data:www-data assets/images

# Trên Windows (chạy Command Prompt với quyền Admin)
icacls "assets\images" /grant "Everyone":(OI)(CI)F /T

.navbar {
            background-color: #00796b; /* Thay đổi màu theo theme */
            padding: 10px 50px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            color: white;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: white;
            text-decoration: none;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 30px;
        }

        .nav-item {
            position: relative;
            padding: 10px 0;
        }

        .nav-link {
            color: white;
            text-decoration: none;
            font-size: 16px;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background-color: white;
            min-width: 200px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            z-index: 1;
            border-radius: 4px;
        }

        .nav-item:hover .dropdown-content {
            display: block;
        }

        .dropdown-content a {
            color: #004d40;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #e0f7fa;
        }

        .search-box {
            display: flex;
            align-items: center;
            background-color: white;
            border-radius: 4px;
            padding: 5px 10px;
            width: 300px;
        }

        .search-box input {
            border: none;
            outline: none;
            width: 100%;
            padding: 5px;
        }

        .search-box button {
            background: none;
            border: none;
            cursor: pointer;
            color: #004d40;
        }

        .user-actions {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .account-btn, .cart-btn {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
            position: relative;
        }

        .account-dropdown {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            background-color: white;
            min-width: 200px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            border-radius: 4px;
            z-index: 1;
        }

        .account-btn:hover .account-dropdown {
            display: block;
        }

        .account-dropdown a {
            color: #004d40;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            border-bottom: 1px solid #e0f7fa;
            display: flex;
            align-items: center;
        }

        .account-dropdown a:last-child {
            border-bottom: none;
        }

        .account-dropdown a:hover {
            background-color: #e0f7fa;
        }

        .account-dropdown i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
            display: inline-block;
        }

        /* Popup styles */
        .popup-overlay {
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

        .popup-overlay.active {
            display: flex;
        }

        .popup-container {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            position: relative;
            width: 400px;
            max-width: 90%;
        }

        .close-popup {
            position: absolute;
            top: 15px;
            right: 15px;
            font-size: 24px;
            cursor: pointer;
            color: #666;
            background: none;
            border: none;
            padding: 0;
        }

        .back-button {
            position: absolute;
            top: 15px;
            left: 15px;
            font-size: 24px;
            cursor: pointer;
            color: #666;
            background: none;
            border: none;
            padding: 0;
            z-index: 2;
        }

        .back-button:hover,
        .close-popup:hover {
            color: #333;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }

        .form-group input:focus {
            border-color: #00796b;
            outline: none;
            box-shadow: 0 0 5px rgba(0,121,107,0.2);
        }

        /* Social Login Styles */
        .social-login {
            margin: 20px 0;
            text-align: center;
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .social-btn {
            width: 60px;
            height: 60px;
            padding: 0;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-facebook {
            background-color: #1877f2;
            color: white;
        }

        .btn-google {
            background-color: #fff;
            color: #333;
            border: 1px solid #ddd;
        }

        .social-btn i {
            font-size: 24px;
        }

        /* Divider Styles */
        .divider {
            text-align: center;
            margin: 20px 0;
            position: relative;
        }

        .divider::before,
        .divider::after {
            content: "";
            position: absolute;
            top: 50%;
            width: 45%;
            height: 1px;
            background-color: #ddd;
        }

        .divider::before { left: 0; }
        .divider::after { right: 0; }

        /* Button Styles */
        .btn {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-primary {
            background-color: #00796b;
            color: white;
        }

        .btn-primary:hover {
            background-color: #004d40;
        }

        /* Links Styles */
        .forgot-password {
            text-align: right;
            margin: -10px 0 15px;
        }

        .forgot-password a,
        .register-link a,
        .login-link a {
            color: #00796b;
            text-decoration: none;
        }

        .forgot-password a:hover,
        .register-link a:hover,
        .login-link a:hover {
            text-decoration: underline;
        }

        .register-link,
        .login-link {
            text-align: center;
            margin-top: 20px;
        }

        /* Verification Code Styles */
        .verification-code {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            justify-content: center;
        }

        .verification-code input {
            width: 45px;
            height: 45px;
            text-align: center;
            font-size: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 0;
        }

        .verification-code input:focus {
            border-color: #00796b;
            outline: none;
        }

        .resend-code {
            text-align: center;
            margin: 15px 0;
        }

        .resend-code button {
            background: none;
            border: none;
            color: #00796b;
            cursor: pointer;
            font-size: 14px;
            padding: 5px 10px;
        }

        .resend-code button:hover {
            text-decoration: underline;
        }

        .step {
            display: none;
        }

        .step.active {
            display: block;
        }

        .info-text {
            text-align: center;
            color: #666;
            margin-bottom: 25px;
            font-size: 14px;
            line-height: 1.6;
        }

        /* Style for the phone number display */
        #displayPhone {
            font-weight: bold;
            color: #00796b;
        }
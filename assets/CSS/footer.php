.footer {
            background-color: #1e3c72;
            color: white;
            padding: 40px 0;
            margin-top: 40px;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            padding: 0 20px;
        }

        .footer-section {
            flex: 1;
            padding: 0 20px;
        }

        .footer-section h3 {
            font-size: 20px;
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 10px;
        }

        .footer-section h3::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 50px;
            height: 2px;
            background-color: #ffd700;
        }
        .company-info p,
        .contact-info p {
            margin-bottom: 15px;
            font-size: 14px;
            display: flex;
            align-items: start;
            gap: 10px;
        }

        .company-info i,
        .contact-info i {
            color: #ffd700;
            width: 20px;
        }

        .phone-number {
            color: #ffd700;
            font-size: 24px;
            font-weight: bold;
            text-decoration: none;
            display: block;
            margin: 10px 0;
        }

        .call-now-btn {
            background-color: #ffd700;
            color: #1e3c72;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }

        .call-now-btn:hover {
            background-color: #ffed4a;
        }

        .social-links {
            flex: 1;
            text-align: left;
        }

        .social-icons {
            display: flex;
            gap: 15px;
            margin-top: 10px;
            justify-content: flex-start;
        }

        .social-icons a {
            color: white;
            font-size: 24px;
            transition: color 0.3s;
        }

        .social-icons a:hover {
            color: #ffd700;
        }

        .contact-social {
            display: flex;
            justify-content: space-between;
            width: 100%;
        }

        .contact-info {
            flex: 1;
        }



        /* Responsive */
        @media (max-width: 768px) {
            .footer-content {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .footer-section {
                padding: 20px 0;
                align-items: center;
                text-align: center;
            }

            .contact-social {
                flex-direction: column;
                align-items: center;
            }

            .social-links {
                text-align: center;
            }
        }
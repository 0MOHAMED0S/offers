<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GoOut Egypt - اكتشف مصر معانا</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }

        /* Navbar */
        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 20px 60px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 28px;
            font-weight: bold;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .logo-icon {
            font-size: 32px;
        }

        .nav-links {
            display: flex;
            gap: 30px;
            align-items: center;
        }

        .nav-links a {
            color: #333;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav-links a:hover {
            color: #667eea;
        }

        .login-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 30px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: transform 0.3s;
            display: inline-block;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }

        /* Hero Section */
        .hero {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 100px 20px 60px;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            top: -200px;
            right: -200px;
        }

        .hero::after {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            bottom: -150px;
            left: -150px;
        }

        .hero-content {
            max-width: 1200px;
            text-align: center;
            color: white;
            z-index: 1;
        }

        .hero h1 {
            font-size: 56px;
            margin-bottom: 20px;
            animation: fadeInUp 1s ease;
        }

        .hero p {
            font-size: 24px;
            margin-bottom: 40px;
            opacity: 0.95;
            animation: fadeInUp 1s ease 0.2s both;
        }

        .cta-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
            animation: fadeInUp 1s ease 0.4s both;
        }

        .cta-btn {
            padding: 16px 40px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            font-size: 18px;
            transition: all 0.3s;
            display: inline-block;
        }

        .cta-primary {
            background: white;
            color: #667eea;
        }

        .cta-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(255, 255, 255, 0.3);
        }

        .cta-secondary {
            background: transparent;
            color: white;
            border: 2px solid white;
        }

        .cta-secondary:hover {
            background: white;
            color: #667eea;
        }

        /* Features Section */
        .features {
            padding: 100px 20px;
            background: #f8f9fa;
        }

        .features-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .section-title {
            text-align: center;
            font-size: 42px;
            margin-bottom: 60px;
            color: #333;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
        }

        .feature-card {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
            text-align: center;
        }

        .feature-card:hover {
            transform: translateY(-10px);
        }

        .feature-icon {
            font-size: 60px;
            margin-bottom: 20px;
        }

        .feature-card h3 {
            font-size: 24px;
            margin-bottom: 15px;
            color: #333;
        }

        .feature-card p {
            color: #666;
            line-height: 1.6;
        }

        /* Stats Section */
        .stats {
            padding: 80px 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .stats-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 40px;
            text-align: center;
        }

        .stat-item h2 {
            font-size: 48px;
            margin-bottom: 10px;
        }

        .stat-item p {
            font-size: 18px;
            opacity: 0.9;
        }

        /* Footer */
        .footer {
            background: #2d3748;
            color: white;
            padding: 40px 20px;
            text-align: center;
        }

        .footer p {
            margin-bottom: 10px;
        }

        .footer-links {
            display: flex;
            gap: 20px;
            justify-content: center;
            margin-top: 20px;
        }

        .footer-links a {
            color: white;
            text-decoration: none;
            opacity: 0.8;
            transition: opacity 0.3s;
        }

        .footer-links a:hover {
            opacity: 1;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .navbar {
                padding: 15px 20px;
            }

            .nav-links {
                gap: 15px;
            }

            .hero h1 {
                font-size: 36px;
            }

            .hero p {
                font-size: 18px;
            }

            .cta-buttons {
                flex-direction: column;
            }

            .cta-btn {
                width: 100%;
            }

            .section-title {
                font-size: 32px;
            }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="logo">
            <span class="logo-icon">🌍</span>
            <span>GoOut Egypt</span>
        </div>
        <div class="nav-links">
            <a href="#features">المميزات</a>
            <a href="#about">عن التطبيق</a>
            <a href="login.html" class="login-btn">تسجيل الدخول</a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>اكتشف مصر معانا 🇪🇬</h1>
            <p>أفضل الأماكن والمطاعم والكافيهات في مصر كلها</p>
            <div class="cta-buttons">
                <a href="login.html" class="cta-btn cta-primary">ابدأ الآن</a>
                <a href="#features" class="cta-btn cta-secondary">اعرف أكتر</a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="features">
        <div class="features-container">
            <h2 class="section-title">ليه تختار GoOut Egypt؟</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">📍</div>
                    <h3>آلاف الأماكن</h3>
                    <p>اكتشف آلاف المطاعم والكافيهات والأماكن السياحية في كل أنحاء مصر</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">⭐</div>
                    <h3>تقييمات حقيقية</h3>
                    <p>شوف تقييمات وآراء زوار حقيقيين قبل ما تزور أي مكان</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">🗺️</div>
                    <h3>خرائط تفاعلية</h3>
                    <p>اعرف ازاي توصل لأي مكان بسهولة من خلال خرائط تفاعلية</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">💰</div>
                    <h3>عروض وخصومات</h3>
                    <p>استمتع بأفضل العروض والخصومات الحصرية لمستخدمي التطبيق</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">📱</div>
                    <h3>سهل الاستخدام</h3>
                    <p>واجهة بسيطة وسهلة تخليك تلاقي اللي تحتاجه بسرعة</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">🎯</div>
                    <h3>توصيات ذكية</h3>
                    <p>احصل على توصيات مخصصة ليك بناءً على اهتماماتك</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats">
        <div class="stats-container">
            <div class="stat-item">
                <h2>10,000+</h2>
                <p>مكان مميز</p>
            </div>
            <div class="stat-item">
                <h2>50,000+</h2>
                <p>مستخدم نشط</p>
            </div>
            <div class="stat-item">
                <h2>100,000+</h2>
                <p>تقييم</p>
            </div>
            <div class="stat-item">
                <h2>4.8/5</h2>
                <p>التقييم العام</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2024 GoOut Egypt. جميع الحقوق محفوظة</p>
        <div class="footer-links">
            <a href="#privacy">سياسة الخصوصية</a>
            <a href="#terms">الشروط والأحكام</a>
            <a href="#contact">اتصل بنا</a>
        </div>
    </footer>

    <script>
        // Smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Check if user is already logged in
        window.addEventListener('load', () => {
            const authToken = sessionStorage.getItem('authToken');
            if (authToken) {
                // User is logged in, redirect to home
                window.location.href = 'home.html';
            }
        });
    </script>
</body>

</html>

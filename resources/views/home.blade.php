<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الصفحة الرئيسية - GoOut Egypt</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;
            background:linear-gradient(135deg,#667eea,#764ba2); min-height:100vh; }
        .navbar { background:white; padding:20px 40px; box-shadow:0 2px 10px rgba(0,0,0,0.1);
            display:flex; justify-content:space-between; align-items:center; }
        .logo { display:flex; align-items:center; gap:10px; font-size:24px; font-weight:bold; color:#667eea; }
        .logo-icon { width:40px; height:40px; background:linear-gradient(135deg,#667eea,#764ba2 100%);
            border-radius:50%; display:flex; align-items:center; justify-content:center; color:white; font-size:20px; }
        .user-info { display:flex; align-items:center; gap:15px; }
        .user-avatar { width:45px; height:45px; border-radius:50%; background:linear-gradient(135deg,#667eea,#764ba2);
            display:flex; align-items:center; justify-content:center; color:white; font-size:20px; font-weight:bold; }
        .user-avatar img { width:100%; height:100%; border-radius:50%; object-fit:cover; }
        .user-details { text-align:right; }
        .user-name { font-weight:600; color:#333; font-size:16px; }
        .user-email { font-size:13px; color:#666; }
        .logout-btn { background:#dc3545; color:white; border:none; padding:10px 20px; border-radius:8px; cursor:pointer; font-size:14px; font-weight:500; transition:all 0.3s; }
        .logout-btn:hover { background:#c82333; transform:translateY(-2px); }
        .container { max-width:1200px; margin:40px auto; padding:0 20px; }
        .welcome-card { background:white; border-radius:20px; padding:40px; box-shadow:0 20px 60px rgba(0,0,0,0.2); text-align:center; margin-bottom:30px; }
        .welcome-card h1 { color:#333; margin-bottom:10px; font-size:32px; }
        .welcome-card p { color:#666; font-size:18px; }
        .success-icon { width:80px; height:80px; background:#28a745; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 20px; font-size:40px; color:white; }
        .stats-grid { display:grid; grid-template-columns:repeat(auto-fit, minmax(250px, 1fr)); gap:20px; margin-top:30px; }
        .stat-card { background:white; border-radius:15px; padding:30px; box-shadow:0 10px 30px rgba(0,0,0,0.1); transition:transform 0.3s; text-align:center; }
        .stat-card:hover { transform:translateY(-5px); }
        .stat-icon { width:60px; height:60px; border-radius:15px; display:flex; align-items:center; justify-content:center; font-size:30px; margin-bottom:15px; }
        .stat-card:nth-child(1) .stat-icon { background:linear-gradient(135deg,#667eea 0%,#764ba2 100%); }
        .stat-card:nth-child(2) .stat-icon { background:linear-gradient(135deg,#f093fb 0%,#f5576c 100%); }
        .stat-card:nth-child(3) .stat-icon { background:linear-gradient(135deg,#4facfe 0%,#00f2fe 100%); }
        .stat-card:nth-child(4) .stat-icon { background:linear-gradient(135deg,#43e97b 0%,#38f9d7 100%); }
        .stat-title { color:#666; font-size:14px; margin-bottom:8px; }
        .stat-value { color:#333; font-size:28px; font-weight:bold; }
        @media (max-width:768px) {
            .navbar { flex-direction:column; gap:15px; padding:20px; }
            .user-info { flex-direction:column; text-align:center; }
            .welcome-card h1 { font-size:24px; }
            .stats-grid { grid-template-columns:1fr; }
        }
    </style>
</head>

<body>
    <div class="navbar">
        <div class="logo">
            <div class="logo-icon">🌍</div>
            <span>GoOut Egypt</span>
        </div>
        <div class="user-info">
            <div class="user-avatar" id="userAvatar">👤</div>
            <div class="user-details">
                <div class="user-name" id="userName">جاري التحميل...</div>
                <div class="user-email" id="userEmail"></div>
            </div>
            <button class="logout-btn" onclick="logout()">تسجيل الخروج</button>
        </div>
    </div>

    <div class="container">
        <div class="welcome-card">
            <div class="success-icon">✓</div>
            <h1>مرحباً بك! 🎉</h1>
            <p>تم تسجيل الدخول بنجاح</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">📊</div>
                <div class="stat-title">إجمالي الزيارات</div>
                <div class="stat-value">1,234</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">⭐</div>
                <div class="stat-title">التقييمات</div>
                <div class="stat-value">4.8/5</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">👥</div>
                <div class="stat-title">المستخدمين النشطين</div>
                <div class="stat-value">856</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">🎯</div>
                <div class="stat-title">الأماكن المفضلة</div>
                <div class="stat-value">42</div>
            </div>
        </div>
    </div>

    <script>
                // في بداية سكريبت صفحة Home
window.addEventListener('load', async () => {
    // التحقق هل الرابط يحتوي على Access Token قادم من جوجل؟
    const hash = window.location.hash.substring(1);
    const params = new URLSearchParams(hash);
    const accessToken = params.get("access_token");

    if (accessToken) {
        // إذا وجدنا توكن، نقوم بتسجيل الدخول فوراً
        await handleGoogleCallback(accessToken);
    } else {
        // إذا لم نجد، نقوم بالفحص العادي
        checkAuth();
    }
});

async function handleGoogleCallback(token) {
    try {
        const formData = new FormData();
        formData.append("access_token", token);

        // استبدل الرابط برابط الـ API الخاص بك
        const response = await fetch("https://gooutegypt.mo-sayed.site/api/auth/google-login", {
            method: "POST",
            body: formData
        });

        const data = await response.json();

        if (response.ok && data.status) {
            localStorage.setItem("auth_token", data.token);
            localStorage.setItem("userData", JSON.stringify(data.user));

            // تنظيف الرابط من التوكن ليكون شكله نظيفاً
            window.history.replaceState({}, document.title, window.location.pathname);

            // تحديث بيانات المستخدم في الصفحة
            loadUserInfo();
        } else {
            // فشل التوكن، ارجع لصفحة اللوجن
             window.location.href = '{{ route("login") }}';
        }
    } catch (error) {
        console.error(error);
    }
}
        function checkAuth() {
            const auth_token = localStorage.getItem('auth_token');
            if (!auth_token) {
                window.location.href = '{{ route("login") }}';
                return;
            }
            loadUserInfo();
        }

        function loadUserInfo() {
            try {
                const userData = localStorage.getItem('userData');
                if (userData) {
                    const user = JSON.parse(userData);
                    displayUserInfo(user);
                } else {
                    document.getElementById('userName').textContent = 'مستخدم GoOut';
                    document.getElementById('userEmail').textContent = 'user@goout.com';
                }
            } catch (error) {
                console.error('Error loading user info:', error);
                document.getElementById('userName').textContent = 'مستخدم';
            }
        }

        function displayUserInfo(user) {
            if (user.name) document.getElementById('userName').textContent = user.name;
            if (user.email) document.getElementById('userEmail').textContent = user.email;
            if (user.picture) {
                document.getElementById('userAvatar').innerHTML = `<img src="${user.picture}" alt="Avatar">`;
            } else if (user.name) {
                const firstLetter = user.name.charAt(0).toUpperCase();
                document.getElementById('userAvatar').textContent = firstLetter;
            }
        }

        function logout() {
            localStorage.removeItem('auth_token');
            localStorage.removeItem('userData');
            window.location.href = '{{ route("login") }}';
        }

        window.addEventListener('load', checkAuth);
    </script>
</body>
</html>

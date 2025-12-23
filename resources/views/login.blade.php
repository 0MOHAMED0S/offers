<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - GoOut Egypt</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;
            background:linear-gradient(135deg,#667eea,#764ba2);
            min-height:100vh; display:flex; align-items:center; justify-content:center; }
        .container { background:#fff; padding:40px; border-radius:20px; width:100%; max-width:400px;
            text-align:center; box-shadow:0 20px 60px rgba(0,0,0,.3); }
        .logo { width:80px; height:80px; margin:0 auto 20px; border-radius:50%;
            background:linear-gradient(135deg,#667eea,#764ba2); display:flex;
            align-items:center; justify-content:center; font-size:40px; color:#fff; }
        h1 { margin-bottom:10px; }
        p { margin-bottom:30px; color:#666; }
        button { width:100%; padding:12px; border-radius:8px; border:2px solid #dadce0;
            background:#fff; cursor:pointer; font-size:16px; font-weight:500; }
        button:hover { background:#f8f9fa; }
        .loading { display:none; margin-top:20px; }
        .spinner { width:40px; height:40px; border:4px solid #eee; border-top:4px solid #667eea;
            border-radius:50%; animation:spin 1s linear infinite; margin:auto; }
        @keyframes spin { to { transform:rotate(360deg); } }
        .message { display:none; margin-top:20px; padding:15px; border-radius:8px; }
        .success { background:#d4edda; color:#155724; }
        .error { background:#f8d7da; color:#721c24; }
    </style>
</head>

<body>
    <div class="container">
        <div class="logo">🌍</div>
        <h1>مرحبا بك</h1>
        <p>سجّل الدخول باستخدام Google</p>
        <button id="google-login-btn">تسجيل الدخول بـ Google</button>

        <div class="loading" id="loading">
            <div class="spinner"></div>
            <p style="margin-top:10px">جاري تسجيل الدخول...</p>
        </div>

        <div class="message" id="message"></div>
    </div>

    <script>
        const clientId = "291192722002-m1ujvc40djk83nqimo29vmaqfn86h8ll.apps.googleusercontent.com";
    const redirectUri = "https://gooutegypt.mo-sayed.site/login";

        // Google login button
        document.getElementById("google-login-btn").addEventListener("click", () => {
            const googleAuthUrl =
                "https://accounts.google.com/o/oauth2/v2/auth" +
                "?client_id=" + clientId +
                "&redirect_uri=" + redirectUri +
                "&response_type=token" +
                "&scope=email profile openid";
            window.location.href = googleAuthUrl;
        });

        // After redirect from Google
        const hash = window.location.hash.substring(1);
        const params = new URLSearchParams(hash);
        const accessToken = params.get("access_token");

        if (accessToken) sendTokenToBackend(accessToken);

        async function sendTokenToBackend(token) {
            document.getElementById("loading").style.display = "block";
            const formData = new FormData();
            formData.append("access_token", token);

            try {
                const response = await fetch(
                    "https://gooutegypt.mo-sayed.site/api/auth/google-login",
                    { method: "POST", body: formData }
                );

                const data = await response.json();
                document.getElementById("loading").style.display = "none";
                const messageDiv = document.getElementById("message");

                if (response.ok && data.status) {
                    // Save token and user info
                    localStorage.setItem("auth_token", data.token);
                    localStorage.setItem("userData", JSON.stringify(data.user));

                    // Clear hash
                    window.history.replaceState({}, document.title, "/home.html");

                    // Success message
                    messageDiv.className = "message success";
                    messageDiv.innerText = "✅ تم تسجيل الدخول بنجاح! جاري التحويل...";
                    messageDiv.style.display = "block";

                    // Redirect to home after short delay
                    setTimeout(() => { window.location.href = '/home.html'; }, 1000);
                } else {
                    messageDiv.className = "message error";
                    messageDiv.innerText = "❌ فشل تسجيل الدخول";
                    messageDiv.style.display = "block";
                }
            } catch (error) {
                console.error(error);
                const messageDiv = document.getElementById("message");
                messageDiv.className = "message error";
                messageDiv.innerText = "❌ خطأ في الاتصال بالسيرفر";
                messageDiv.style.display = "block";
            }
        }
    </script>
</body>
</html>

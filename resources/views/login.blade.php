<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Login</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea, #764ba2);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            background: #fff;
            padding: 40px;
            border-radius: 20px;
            width: 100%;
            max-width: 400px;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0, 0, 0, .3);
        }

        .logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            color: #fff;
        }

        h1 {
            margin-bottom: 10px;
        }

        p {
            margin-bottom: 30px;
            color: #666;
        }

        button {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: 2px solid #dadce0;
            background: #fff;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
        }

        button:hover {
            background: #f8f9fa;
        }

        .loading {
            display: none;
            margin-top: 20px;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #eee;
            border-top: 4px solid #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: auto;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .message {
            display: none;
            margin-top: 20px;
            padding: 15px;
            border-radius: 8px;
        }

        .success {
            background: #d4edda;
            color: #155724;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="logo">🌍</div>
        <h1>Welcome Back</h1>
        <p>Sign in with Google</p>

        <button onclick="loginWithGoogle()">Sign in with Google</button>

        <div class="loading">
            <div class="spinner"></div>
            <p style="margin-top:10px">جاري تسجيل الدخول...</p>
        </div>

        <div class="message" id="message"></div>
    </div>


    <script>
        /* ===============================
               1️⃣ تسجيل الدخول بجوجل
            ================================ */
        function loginWithGoogle() {
            const clientId = "291192722002-m1ujvc40djk83nqimo29vmaqfn86h8ll.apps.googleusercontent.com";
            const redirectUri = "https://gooutegypt.mo-sayed.site";

            const googleAuthUrl =
                "https://accounts.google.com/o/oauth2/v2/auth" +
                "?client_id=" + clientId +
                "&redirect_uri=" + redirectUri +
                "&response_type=token" +
                "&scope=email profile openid";

            window.location.href = googleAuthUrl;
        }

        /* ===============================
           2️⃣ تنفيذ الكود بعد الرجوع من Google
        ================================ */
        console.log("✅ JS Loaded");
        console.log("URL:", window.location.href);
        console.log("HASH:", window.location.hash);

        // استخراج access_token من الـ hash
        const hash = window.location.hash.substring(1);
        const params = new URLSearchParams(hash);
        const accessToken = params.get("access_token");

        console.log("ACCESS TOKEN:", accessToken);

        // لو فيه توكن ابعته للباك إند
        if (accessToken) {
            sendTokenToBackend(accessToken);
        }

        /* ===============================
           3️⃣ إرسال التوكن للباك إند
           (form-data زي Postman)
        ================================ */
        async function sendTokenToBackend(token) {
            console.log("🚀 Sending token to backend...");

            const formData = new FormData();
            formData.append("access_token", token);

            try {
                const response = await fetch(
                    "https://gooutegypt.mo-sayed.site/api/auth/google-login", {
                        method: "POST",
                        body: formData
                    }
                );

                console.log("STATUS:", response.status);

                const data = await response.json();
                console.log("RESPONSE:", data);

                if (response.ok && data.status) {
                    // مسح التوكن من الـ URL (اختياري وأفضل)
                    window.history.replaceState({}, document.title, window.location.pathname);
                    // تحويل لصفحة الهوم بعد ثانية
                    setTimeout(() => {
                        window.location.href = "{{ route('home') }}";
                    }, 1000);
                    localStorage.setItem("auth_token", data.token);
                } else {
                    alert("❌ فشل تسجيل الدخول");
                }

            } catch (error) {
                console.error("🔥 ERROR:", error);
                alert("❌ خطأ في الاتصال بالسيرفر");
            }
        }
    </script>
</body>

</html>

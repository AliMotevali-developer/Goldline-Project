<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_lifetime' => 3600,
        'cookie_path' => '/',
        'cookie_secure' => true,
        'cookie_httponly' => true,
        'cookie_samesite' => 'Strict'
    ]);
};

//هدایت کاربر به صفحه داشبرد در صورتی که قبلا لاگین یا رجیستر کرده باشد

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header("Location: home.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>گلدلاین | ورود</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.0.0/Vazirmatn-font-face.css" rel="stylesheet" type="text/css" />

    <style>
        body { background-color: #f8f9fa; font-family: 'vazirmatn'; }
        .auth-card { max-width: 400px; margin: 80px auto; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: none; }
    </style>
</head>
<body>

<div class="container">
    <div class="card auth-card p-4">
        <div class="text-center mb-4">
            <h2 class="h4 fw-bold text-primary">ورود به گلدلاین</h2>
            <p class="text-muted small">برای مدیریت صندوق طلا وارد شوید</p>
        </div>

        <div id="alertBox" class="alert d-none text-center small"></div>

        <form id="loginForm">
            <div class="mb-3">
                <input type="text" id="username" class="form-control form-control-lg bg-light border-0" placeholder="نام کاربری" required>
            </div>
            <div class="mb-4">
                <input type="password" id="password" class="form-control form-control-lg bg-light border-0" placeholder="رمز عبور" required>
            </div>
            <button type="submit" id="submitBtn" class="btn btn-primary btn-lg w-100 rounded-pill shadow-sm">ورود به حساب</button>
        </form>

        <div class="text-center mt-4">
            <a href="sabtnam.php" class="text-decoration-none small text-muted">حساب کاربری ندارید؟ ثبت‌نام کنید</a>
        </div>
    </div>
</div>

<script>
    
    document.getElementById('loginForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const btn = document.getElementById('submitBtn');
        const alertBox = document.getElementById('alertBox');
        
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> در حال بررسی...';
        alertBox.classList.add('d-none');

        const payload = {
            action: 'login',
            username: document.getElementById('username').value.trim(),
            password: document.getElementById('password').value
        };

        try {
            const response = await fetch('ajax.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });
            const result = await response.json();

            if (result.status === 'success') {
                alertBox.className = 'alert alert-success d-block text-center small';
                alertBox.innerText = result.message;
                setTimeout(() => window.location.href = 'home.php', 1000);
            } else {
                alertBox.className = 'alert alert-danger d-block text-center small';
                alertBox.innerText = result.message;
                btn.disabled = false;
                btn.innerText = 'ورود به حساب';
            }
        } catch (error) {
            alertBox.className = 'alert alert-danger d-block text-center small';
            alertBox.innerText = 'خطا در ارتباط با سرور.';
            btn.disabled = false;
            btn.innerText = 'ورود به حساب';
        }
    });
</script>
</body>
</html>
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
    <title>گلدلاین | ایجاد حساب کاربری</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">
        <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.0.0/Vazirmatn-font-face.css" rel="stylesheet" type="text/css" />

    <style>
        body { background-color: #f8f9fa; font-family: 'vazirmatn'; }
        .auth-card { max-width: 450px; margin: 50px auto; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: none; }
        .form-control:focus { border-color: #ffd700; box-shadow: 0 0 10px rgba(255, 215, 0, 0.2); }
    </style>
</head>
<body>

<div class="container">
    <div class="card auth-card p-4">
        <div class="text-center mb-4">
            <h2 class="h4 fw-bold text-warning">ثبت‌نام در گلدلاین</h2>
            <p class="text-muted small">شروع سرمایه‌گذاری هوشمند در صندوق طلا</p>
        </div>

        <div id="alertBox" class="alert d-none text-center small"></div>

        <form id="registerForm">
            <div class="mb-3">
                <input type="text" id="fullname" class="form-control form-control-lg bg-light border-0" placeholder="نام و نام خانوادگی" required>
            </div>
            <div class="mb-3">
                <input type="tel" id="phone" class="form-control form-control-lg bg-light border-0" placeholder="شماره موبایل (مثال: 09123456789)" required>
            </div>
            <div class="mb-3">
                <input type="text" id="username" class="form-control form-control-lg bg-light border-0" placeholder="نام کاربری (انگلیسی)" required>
            </div>
            <div class="mb-4">
                <input type="password" id="password" class="form-control form-control-lg bg-light border-0" placeholder="رمز عبور (حداقل 8 کاراکتر)" required>
            </div>
            <button type="submit" id="submitBtn" class="btn btn-warning btn-lg w-100 rounded-pill shadow-sm fw-bold">ایجاد حساب کاربری</button>
        </form>

        <div class="text-center mt-4">
            <a href="index.php" class="text-decoration-none small text-muted">حساب کاربری دارید؟ وارد شوید</a>
        </div>
    </div>
</div>

<script>
    document.getElementById('registerForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const btn = document.getElementById('submitBtn');
        const alertBox = document.getElementById('alertBox');
        
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> در حال ثبت‌نام...';
        alertBox.classList.add('d-none');

        const payload = {
            action: 'register',
            fullname: document.getElementById('fullname').value.trim(),
            phone: document.getElementById('phone').value.trim(),
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
                setTimeout(() => window.location.href = 'home.php', 1500);
            } else {
                alertBox.className = 'alert alert-danger d-block text-center small';
                alertBox.innerText = result.message;
                btn.disabled = false;
                btn.innerText = 'ایجاد حساب کاربری';
            }
        } catch (error) {
            alertBox.className = 'alert alert-danger d-block text-center small';
            alertBox.innerText = 'خطا در ارتباط با سرور.';
            btn.disabled = false;
            btn.innerText = 'ایجاد حساب کاربری';
        }
    });
</script>
</body>
</html>
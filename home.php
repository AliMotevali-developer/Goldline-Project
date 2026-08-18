<?php
// صفحه داشبرد (Dashboard)
if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_lifetime' => 3600,
        'cookie_path' => '/',
        'cookie_secure' => true,
        'cookie_httponly' => true,
        'cookie_samesite' => 'Strict'
    ]);
};

// هدایت کاربر به داشبرد
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: index.php");
    exit;
}

date_default_timezone_set("Asia/Tehran");
$time = date("Y/m/d - H:i");
$fullname = htmlspecialchars($_SESSION['fullname'], ENT_QUOTES, "UTF-8");
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>گلدلاین | داشبورد</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.0.0/Vazirmatn-font-face.css" rel="stylesheet" type="text/css" />

    <style>
        body { font-family: 'Vazirmatn'; background-color: #f4f6f9; color: #333; }
        .dashboard-card { background: #ffffff; border-radius: 16px; box-shadow: 0 8px 30px rgba(0,0,0,0.04); border: none; }
        .price-badge { background: #ffdf00; color: #2d2d2d; box-shadow: 0 4px 15px rgba(255, 223, 0, 0.3); }
        .input-gold { border: 2px solid #edf2f7; border-radius: 10px; background-color: #f8fafc; transition: 0.3s ease; }
        .input-gold:focus { border-color: #ffdf00; background-color: #ffffff; box-shadow: 0 0 0 4px rgba(255, 223, 0, 0.15); }
        
        .table-modern { border-collapse: separate; border-spacing: 0 12px; margin-top: -12px; }
        .table-modern thead th { border-bottom: none; color: #64748b; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px; padding: 0 15px; }
        .table-modern tbody tr { background-color: #ffffff; box-shadow: 0 2px 10px rgba(0,0,0,0.02); border-radius: 12px; transition: transform 0.2s ease; }
        .table-modern tbody tr:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .table-modern tbody td { border: none; padding: 16px 15px; vertical-align: middle; }
        .table-modern tbody td:first-child { border-top-right-radius: 12px; border-bottom-right-radius: 12px; }
        .table-modern tbody td:last-child { border-top-left-radius: 12px; border-bottom-left-radius: 12px; }
        .table-scrollable {
            max-height: 280px; 
            overflow-y: auto;
            padding-right: 5px;
        }
        
        .table-modern thead th {
            position: sticky;
            top: 0;
            background-color: #ffffff; 
            z-index: 1;
            border-bottom: 2px solid #edf2f7;
        }

        .table-scrollable::-webkit-scrollbar {
            width: 6px;
        }
        .table-scrollable::-webkit-scrollbar-track {
            background: #f8fafc;
            border-radius: 10px;
        }
        .table-scrollable::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
        .table-scrollable::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>
<body class="py-4">

<div class="container">
    <nav class="navbar dashboard-card px-4 mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h5 fw-bold text-primary mb-0">گلدلاین</h1>
            <small class="text-muted">خوش آمدید، <?= $fullname ?> عزیز</small>
        </div>
        <div>
            <span class="badge bg-light text-dark border me-2"><?= $time ?></span>
            <a href="logout.php" class="btn btn-sm btn-outline-danger rounded-pill px-3">خروج</a>
        </div>
    </nav>

    <div class="row justify-content-center mb-4 g-3">
        <div class="col-md-10">
            <div class="row g-3">
                
                <div class="col-md-4 text-center">
                    <div class="dashboard-card p-4 h-100">
                        <p class="text-muted mb-2">قیمت لحظه‌ای طلا (mg)</p>
                        <div class="h4 fw-bold price-badge rounded-pill py-2 px-3 d-inline-block">
                            <span id="irangold">در حال دریافت...</span> <span class="h6">تومان</span>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 text-center">
                    <div class="dashboard-card p-4 h-100" style="border-top: 4px solid #28a745;">
                        <p class="text-muted mb-2">موجودی کیف پول شما</p>
                        <div class="h4 fw-bold text-success mb-3">
                            <span id="wallet-irt">0</span> <span class="h6 text-muted">تومان</span>
                        </div>
                        <button class="btn btn-outline-success btn-sm rounded-pill px-4 fw-bold shadow-sm" onclick="rechargeWallet()">+ شارژ تستی حساب</button>
                    </div>
                </div>

                <div class="col-md-4 text-center">
                    <div class="dashboard-card p-4 h-100" style="border-top: 4px solid #ffdf00;">
                        <p class="text-muted mb-2">موجودی طلای شما</p>
                        <div class="h4 fw-bold text-dark mb-3">
                            <span id="wallet-gold">0</span> <span class="h6 text-muted">میلی‌گرم</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="dashboard-card p-4">
                <div class="d-flex border-bottom pb-3 mb-4 gap-3">
                    <button class="btn btn-primary px-4 rounded-pill" id="btn-buy">خرید طلا</button>
                    <button class="btn btn-light text-muted px-4 rounded-pill" id="btn-sell">فروش طلا</button>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-muted small">مقدار (میلی‌گرم)</label>
                        <input type="number" class="form-control form-control-lg input-gold text-center" id="mili" placeholder="مثلا 1000">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small">مبلغ کل (تومان)</label>
                        <input type="text" class="form-control form-control-lg input-gold text-center bg-light" id="tooman" readonly>
                    </div>
                </div>
                <div class="mt-4 text-start">
                    <button class="btn btn-warning btn-lg rounded-pill px-5 fw-bold" id="actionBtn">تایید عملیات</button>
                </div>
            </div>
        </div>
    </div>

    <div class="dashboard-card p-4 mt-5 mb-5">
        <h6 class="text-muted mb-4 fw-bold ps-2">آخرین تراکنش‌های شما</h6>
        <div class="table-responsive table-scrollable">
            <table class="table table-modern text-center align-middle mb-0">
                <thead>
                    <tr>
                        <th>نوع عملیات</th>
                        <th>مقدار (میلی‌گرم)</th>
                        <th>مبلغ کل (تومان)</th>
                    </tr>
                </thead>
                <tbody id="tx-history">
                    <tr><td colspan="3" class="text-muted py-4">در حال بارگذاری...</td></tr>
                </tbody>
            </table>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    let currentGoldPrice = 0;
    let tradeAction = 'buy'; 

    const miliInput = document.getElementById('mili');
    const toomanInput = document.getElementById('tooman');
    const priceDisplay = document.getElementById('irangold');
    const actionBtn = document.getElementById('actionBtn'); 
    const btnBuy = document.getElementById('btn-buy');
    const btnSell = document.getElementById('btn-sell');
    const walletIrtDisplay = document.getElementById('wallet-irt');
    const walletGoldDisplay = document.getElementById('wallet-gold');

    const fetchWallet = async () => {
        try {
            const res = await fetch('ajax.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'get_wallet' })
            });
            const result = await res.json();
            if (result.status === 'success') {
                if (walletIrtDisplay) walletIrtDisplay.innerText = Number(result.irt_balance).toLocaleString('fa-IR');
                if (walletGoldDisplay) walletGoldDisplay.innerText = Number(result.gold_balance).toLocaleString('fa-IR');
            }
        } catch (e) { console.error('خطا در دریافت موجودی'); }
    };

    window.rechargeWallet = async () => {
        const amountStr = prompt('سیستم شبیه‌ساز درگاه پرداخت:\nمبلغ شارژ را به تومان وارد کنید (مثلا 50000000):');
        if (!amountStr) return;
        
        const amount = parseInt(amountStr.replace(/\D/g, ''));
        if (!amount || amount < 50000) {
            alert('حداقل مبلغ شارژ ۵۰,۰۰۰ تومان است.');
            return;
        }

        try {
            const res = await fetch('ajax.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'recharge', amount: amount })
            });
            const data = await res.json();
            
            alert(data.message);
            if (data.status === 'success') {
                fetchWallet(); 
            }
        } catch (e) {
            alert('خطا در ارتباط با سرور.');
        }
    };

    const fetchPrice = async () => {
        try {
            const response = await fetch('irangold.php');
            const priceText = await response.text();
            const parsedPrice = parseInt(priceText.replace(/\D/g, ''));
            
            if (!isNaN(parsedPrice) && parsedPrice > 0) {
                currentGoldPrice = parsedPrice;
                if (priceDisplay) priceDisplay.innerText = currentGoldPrice.toLocaleString('fa-IR');
                calculateTotal(); 
            } else {
                if (priceDisplay) priceDisplay.innerText = "خطا در دریافت";
            }
        } catch (error) {
            if (priceDisplay) priceDisplay.innerText = "قطع ارتباط";
        }
    };

    const calculateTotal = () => {
        if (!miliInput || !toomanInput) return;
        const mili = parseFloat(miliInput.value) || 0;
        const total = mili * currentGoldPrice;
        toomanInput.value = total > 0 ? total.toLocaleString('fa-IR') : '';
    };

    if (miliInput) miliInput.addEventListener('input', calculateTotal);

    if (btnBuy) {
        btnBuy.addEventListener('click', function() {
            tradeAction = 'buy';
            this.className = 'btn btn-primary px-4 rounded-pill';
            if (btnSell) btnSell.className = 'btn btn-light text-muted px-4 rounded-pill';
            if (actionBtn) actionBtn.innerText = 'تایید عملیات (خرید)';
        });
    }
    
    if (btnSell) {
        btnSell.addEventListener('click', function() {
            tradeAction = 'sell';
            this.className = 'btn btn-primary px-4 rounded-pill';
            if (btnBuy) btnBuy.className = 'btn btn-light text-muted px-4 rounded-pill';
            if (actionBtn) actionBtn.innerText = 'تایید عملیات (فروش)';
        });
    }

    const fetchHistory = async () => {
        try {
            const res = await fetch('ajax.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'get_history' })
            });
            const result = await res.json();
            
            if (result.status === 'success') {
                const tbody = document.getElementById('tx-history');
                if (tbody) {
                    if (result.data.length > 0) {
                        tbody.innerHTML = result.data.map(tx => `
                            <tr>
                                <td><span class="badge ${tx.trade_type === 'buy' ? 'bg-success' : 'bg-danger'}">${tx.trade_type === 'buy' ? 'خرید' : 'فروش'}</span></td>
                                <td class="fw-bold">${tx.mili_grams}</td>
                                <td>${Number(tx.total_price).toLocaleString('fa-IR')}</td>
                            </tr>
                        `).join('');
                    } else {
                        tbody.innerHTML = '<tr><td colspan="3" class="text-muted py-4">تراکنشی یافت نشد</td></tr>';
                    }
                }
            }
        } catch (e) { console.error('خطا در دریافت تاریخچه'); }
    };

    if (actionBtn) {
        actionBtn.addEventListener('click', async () => {
            if (!miliInput) return;
            const amount = parseInt(miliInput.value);
            
            if (!amount || amount <= 0) {
                alert('لطفاً مقدار معتبری وارد کنید.');
                return;
            }

            actionBtn.disabled = true;
            actionBtn.innerText = 'در حال پردازش...';

            try {
                const res = await fetch('ajax.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: tradeAction, mili: amount })
                });
                const data = await res.json();

                if (data.status === 'success') {
                    alert(data.message); 
                    miliInput.value = '';
                    if (toomanInput) toomanInput.value = '';
                    fetchHistory(); 
                    fetchWallet(); 
                } else {
                    alert('خطا: ' + data.message); 
                }
            } catch (e) {
                alert('خطا در ارتباط با سرور.');
            } finally {
                actionBtn.disabled = false;
                actionBtn.innerText = tradeAction === 'buy' ? 'تایید عملیات (خرید)' : 'تایید عملیات (فروش)';
            }
        });
    }

    fetchPrice();
    fetchHistory();
    fetchWallet(); 
    setInterval(fetchPrice, 5000);
});
</script>
</body>
</html>
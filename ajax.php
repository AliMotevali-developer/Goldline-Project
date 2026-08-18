<?php
// فایل: ajax.php (کنترلر مرکزی)
require_once 'config.php';

header('Content-Type: application/json; charset=utf-8');

$database = new Database();
$db = $database->connect();

$request_body = file_get_contents('php://input');
$data = json_decode($request_body, true);

$action = $data['action'] ?? '';

// بخش ثبت‌نام (Register)
if ($action === 'register') {
    $fullname = trim($data['fullname']);
    $phone = trim($data['phone']);
    $username = trim($data['username']);
    $password = $data['password'];

    if (!preg_match("/^09\d{9}$/", $phone)) {
        echo json_encode(['status' => 'error', 'message' => 'شماره موبایل نامعتبر است.']);
        exit;
    }
    if (strlen($password) < 8) {
        echo json_encode(['status' => 'error', 'message' => 'رمز عبور باید حداقل 8 کاراکتر باشد.']);
        exit;
    }

    $check_stmt = $db->prepare("SELECT id FROM user WHERE Username = :username OR Number = :phone");
    $check_stmt->execute(['username' => $username, 'phone' => $phone]);
    
    if ($check_stmt->rowCount() > 0) {
        echo json_encode(['status' => 'error', 'message' => 'نام کاربری یا شماره موبایل قبلاً ثبت شده است.']);
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $insert_stmt = $db->prepare("INSERT INTO user (Username, Password, Number, Fullname) VALUES (:username, :password, :phone, :fullname)");
    
    if ($insert_stmt->execute(['username' => $username, 'password' => $hashed_password, 'phone' => $phone, 'fullname' => $fullname])) {
        $_SESSION['user_id'] = $db->lastInsertId();
        $_SESSION['fullname'] = $fullname;
        $_SESSION['logged_in'] = true;
        echo json_encode(['status' => 'success', 'message' => 'ثبت‌نام موفقیت‌آمیز بود.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'خطا در ثبت اطلاعات.']);
    }
    exit;
}

// بخش ورود (Login)
if ($action === 'login') {
    $username = trim($data['username']);
    $password = $data['password'];

    $stmt = $db->prepare("SELECT * FROM user WHERE Username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['Password'])) {
        $_SESSION['user_id'] = $user['id'] ?? null;
        $_SESSION['fullname'] = $user['Fullname'];
        $_SESSION['logged_in'] = true;
        echo json_encode(['status' => 'success', 'message' => 'ورود موفقیت‌آمیز بود.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'نام کاربری یا رمز عبور اشتباه است.']);
    }
    exit;
}

$user_id = $_SESSION['user_id'] ?? null;

// خرید طلا (Buy Gold)
if ($action === 'buy' && $user_id) {
    $mili = intval($data['mili'] ?? 0);
    if ($mili <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'مقدار وارد شده نامعتبر است.']);
        exit;
    }
    
    try {
        $db->beginTransaction();
        $current_price = 18500;
        $cache_file = 'gold_cache.json';
        if (file_exists($cache_file)) {
            $cache_data = json_decode(file_get_contents($cache_file), true);
            if (isset($cache_data['price'])) {
                $current_price = intval($cache_data['price']);
            }
        }
        $total_cost = $mili * $current_price;

        $stmt = $db->prepare("SELECT irt_balance, gold_balance FROM user WHERE id = :id FOR UPDATE");
        $stmt->execute(['id' => $user_id]);
        $user = $stmt->fetch();

        if (!$user || $user['irt_balance'] < $total_cost) {
            $db->rollBack();
            echo json_encode(['status' => 'error', 'message' => 'موجودی کیف پول شما کافی نیست. لطفا حساب خود را شارژ کنید.']);
            exit;
        }

        $new_irt = $user['irt_balance'] - $total_cost;
        $new_gold = $user['gold_balance'] + $mili;

        $update_stmt = $db->prepare("UPDATE user SET irt_balance = :irt, gold_balance = :gold WHERE id = :id");
        $update_stmt->execute(['irt' => $new_irt, 'gold' => $new_gold, 'id' => $user_id]);

        $log_stmt = $db->prepare("INSERT INTO transactions (user_id, trade_type, mili_grams, price_per_mg, total_price) VALUES (:uid, 'buy', :mili, :price, :total)");
        $log_stmt->execute(['uid' => $user_id, 'mili' => $mili, 'price' => $current_price, 'total' => $total_cost]);

        $db->commit();
        echo json_encode(['status' => 'success', 'message' => "خرید $mili میلی‌گرم طلا با موفقیت انجام شد."]);
        exit;
    } catch (Exception $e) {
        $db->rollBack();
        echo json_encode(['status' => 'error', 'message' => 'ارور دیتابیس: ' . $e->getMessage()]);
        exit;
    }
}

// بخش فروش طلا (Sell Gold)
if ($action === 'sell' && $user_id) {
    $mili = intval($data['mili'] ?? 0);
    if ($mili <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'مقدار وارد شده نامعتبر است.']);
        exit;
    }
    
    try {
        $db->beginTransaction();
        $current_price = 18500;
        $cache_file = 'gold_cache.json';
        if (file_exists($cache_file)) {
            $cache_data = json_decode(file_get_contents($cache_file), true);
            if (isset($cache_data['price'])) {
                $current_price = intval($cache_data['price']);
            }
        }
        $total_revenue = $mili * $current_price;

        $stmt = $db->prepare("SELECT irt_balance, gold_balance FROM user WHERE id = :id FOR UPDATE");
        $stmt->execute(['id' => $user_id]);
        $user = $stmt->fetch();

        if (!$user || $user['gold_balance'] < $mili) {
            $db->rollBack();
            echo json_encode(['status' => 'error', 'message' => 'موجودی طلای شما برای این فروش کافی نیست.']);
            exit;
        }

        $new_irt = $user['irt_balance'] + $total_revenue;
        $new_gold = $user['gold_balance'] - $mili;

        $update_stmt = $db->prepare("UPDATE user SET irt_balance = :irt, gold_balance = :gold WHERE id = :id");
        $update_stmt->execute(['irt' => $new_irt, 'gold' => $new_gold, 'id' => $user_id]);

        $log_stmt = $db->prepare("INSERT INTO transactions (user_id, trade_type, mili_grams, price_per_mg, total_price) VALUES (:uid, 'sell', :mili, :price, :total)");
        $log_stmt->execute(['uid' => $user_id, 'mili' => $mili, 'price' => $current_price, 'total' => $total_revenue]);

        $db->commit();
        echo json_encode(['status' => 'success', 'message' => "فروش $mili میلی‌گرم طلا با موفقیت انجام شد."]);
        exit;
    } catch (Exception $e) {
        $db->rollBack();
        echo json_encode(['status' => 'error', 'message' => 'ارور دیتابیس: ' . $e->getMessage()]);
        exit;
    }
}

// تاریخچه معاملات (History)
if ($action === 'get_history' && $user_id) {
    $stmt = $db->prepare("SELECT trade_type, mili_grams, total_price FROM transactions WHERE user_id = :id ORDER BY created_at DESC LIMIT 50");
    $stmt->execute(['id' => $user_id]);
    $history = $stmt->fetchAll();
    echo json_encode(['status' => 'success', 'data' => $history]);
    exit;
}

// موجودی کیف پول (Wallet)
if ($action === 'get_wallet' && $user_id) {
    $stmt = $db->prepare("SELECT irt_balance, gold_balance FROM user WHERE id = :id");
    $stmt->execute(['id' => $user_id]);
    $user = $stmt->fetch();
    echo json_encode([
        'status' => 'success',
        'irt_balance' => $user['irt_balance'],
        'gold_balance' => $user['gold_balance']
    ]);
    exit;
}

//شارژ تستی کیف پول (Recharge Wallet)
if ($action === 'recharge' && $user_id) {
    $amount = intval($data['amount'] ?? 0);
    if ($amount < 50000) {
        echo json_encode(['status' => 'error', 'message' => 'برای تست، حداقل مبلغ شارژ ۵۰,۰۰۰ تومان است.']);
        exit;
    }

    try {
        $stmt = $db->prepare("UPDATE user SET irt_balance = irt_balance + :amount WHERE id = :id");
        $stmt->execute(['amount' => $amount, 'id' => $user_id]);
        echo json_encode(['status' => 'success', 'message' => 'کیف پول شما با موفقیت شارژ شد. اکنون میتوانید خرید کنید!']);
        exit;
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'خطا در عملیات شارژ سیستم.']);
        exit;
    }
}

// درخواست‌های نامعتبر
echo json_encode(['status' => 'error', 'message' => 'درخواست نامعتبر است.']);
?>
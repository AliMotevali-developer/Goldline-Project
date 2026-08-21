<div align="center">

# 🪙 Goldline

**Real-Time Online Gold Trading Simulator & Digital Wallet Platform**

[English](#-english) • [فارسی](#-فارسی)

</div>

---

<a name="-english"></a>
## 🇬🇧 English

**Goldline** is a full-stack online gold trading simulator and digital wallet built with **PHP 8 (OOP & PDO)** and **MySQL**. It integrates real-time live market prices via financial APIs and offers a seamless, reactive user interface powered by modern JavaScript (Fetch API) without full-page reloads.

### 📸 Screenshots

| Login Page | Dashboard | Transaction History |
| :---: | :---: | :---: |
| ![Login](https://raw.githubusercontent.com/AliMotevali-developer/Goldline-Project/main/login.png) | ![Dashboard](https://raw.githubusercontent.com/AliMotevali-developer/Goldline-Project/main/dashboard.png) | ![Transactions](https://raw.githubusercontent.com/AliMotevali-developer/Goldline-Project/main/dashboard_transaction.png) |

### ✨ Features
- 📡 **Live Market Data:** Direct API integration for real-time gold rates, supported by an automated **5-minute smart caching layer** to optimize network requests and prevent rate-limiting.
- 🔐 **Secure Authentication:** User registration and session management leveraging strong `Bcrypt` password hashing and secure session handling.
- 💳 **Virtual Payment Gateway:** Integrated test payment gateway simulator for real-time fiat balance deposits.
- ⚡ **Asynchronous UX (AJAX/Fetch API):** Seamless execution of buy/sell operations, wallet funding, and transaction feed updates without page reloads.
- 📊 **Transaction Ledger:** Scrollable real-time transaction ledger monitoring user trade history.
- 🛡️ **Backend Security:** Robust database transactions and strict parameterized queries using `PDO` prepared statements to mitigate SQL Injection.
- 🎨 **Responsive UI:** Clean, modern interface designed with **Bootstrap 5.3** and custom styling.

### 🛠️ Tech Stack
- **Backend:** PHP 8.x (Object-Oriented Programming & PDO)
- **Database:** MySQL
- **Frontend:** HTML5, CSS3, JavaScript (ES6+ / Fetch API)
- **UI Framework & Libraries:** Bootstrap 5.3, cURL

### 🚀 Getting Started

1. **Clone the repository:**
   ```bash
   git clone [https://github.com/AliMotevali-developer/Goldline-Project.git](https://github.com/AliMotevali-developer/Goldline-Project.git)

   Move to web server directory:
Move the cloned project to your local server directory (e.g., htdocs in XAMPP/Laragon or www in WAMP).


Setup Database:
Create a new MySQL database (e.g., goldline).
Import the provided SQL dump file (h398260_goldline.sql).

Configure Database Connection:
Open config.php and configure your credentials:
private $db_name = 'goldline';
private $username = 'root';
private $password = '';

Run Application:
Open your browser and navigate to:
http://localhost/Goldline-Project/index.php




# 🪙 Goldline - پلتفرم شبیه‌ساز معاملات آنلاین طلا

**گلدلاین (Goldline)** یک مینی‌صرافی و پلتفرم شبیه‌ساز برای خرید و فروش آنلاین طلا (بر پایه میلی‌گرم) است. این پروژه با استفاده از معماری تمیز **PHP & MySQL** توسعه داده شده و قیمت لحظه‌ای طلا را به صورت زنده از بازار واقعی دریافت می‌کند. هدف از توسعه این پلتفرم، ارائه یک نمونه‌کار قدرتمند از تلفیق بک‌اند ایمن با فرانت‌اند تعاملی (بدون رفرش) است.

---

## 📸 تصاویر پروژه

### صفحه‌ی ورود
![صفحه ورود](https://raw.githubusercontent.com/AliMotevali-developer/Goldline-Project/main/login.png)
### صفحه‌ی داشبورد
![Dashboard](https://raw.githubusercontent.com/AliMotevali-developer/Goldline-Project/main/dashboard.png)

### تاریخچه معاملات
![Transactions](https://raw.githubusercontent.com/AliMotevali-developer/Goldline-Project/main/dashboard_transaction.png)

---

## ✨ ویژگی‌های کلیدی

- 📡 **دریافت قیمت زنده (Live API):** اتصال به API صرافی نوبیتکس برای دریافت قیمت واقعی طلا به همراه سیستم **Cache هوشمند ۵ دقیقه‌ای** برای جلوگیری از بلاک شدن سرور.
- 🔐 **احراز هویت ایمن:** سیستم ثبت‌نام و ورود کاربران با استفاده از Sessionهای محافظت‌شده و هشینگ قدرتمند رمزهای عبور (`Bcrypt`).
- 💳 **کیف پول و شارژ مجازی:** دارای سیستم شبیه‌ساز درگاه پرداخت برای شارژ تستی کیف پول ریالی توسط کاربر.
- ⚡ **تجربه کاربری نرم (AJAX):** انجام تمامی عملیات‌ها (خرید، فروش، شارژ و آپدیت تاریخچه) با استفاده از `Fetch API` جاوااسکریپت، کاملاً بدون رفرش صفحه.
- 📊 **تاریخچه معاملات:** جدول اسکرول‌دار و مدرن برای نمایش زنده آخرین تراکنش‌های کاربر.
- 🛡️ **امنیت بک‌اند:** استفاده از `PDO` و `Prepared Statements` برای جلوگیری از حملات SQL Injection و مدیریت تراکنش‌ها (`Database Transactions`).
- 🎨 **رابط کاربری (UI) مدرن:** طراحی ریسپانسیو و چشم‌نواز با استفاده از **Bootstrap 5** و فونت استاندارد **Vazirmatn**.

---

## 🛠️ تکنولوژی‌های استفاده شده

- **Backend:** PHP 8.x (OOP & PDO)
- **Database:** MySQL
- **Frontend:** HTML5, CSS3, JavaScript (ES6)
- **Framework / Libraries:** Bootstrap 5.3, cURL (for API requests)

---

## 🚀 راهنمای نصب و راه‌اندازی

برای اجرای این پروژه روی سرور لوکال (مثل XAMPP) یا هاست واقعی، مراحل زیر را دنبال کنید:

### ۱. کلون کردن پروژه
```bash
git clone https://github.com/AliMotevali-developer/Goldline-Project.git

۲. انتقال به پوشه‌ی سرور
پروژه را به پوشه‌ی htdocs (در XAMPP) یا www (در WAMP) منتقل کنید.

۳. ایجاد دیتابیس
یک دیتابیس جدید در MySQL بسازید (مثلاً goldline).

فایل h398260_goldline.sql موجود در پروژه را در آن Import کنید.

۴. تنظیمات اتصال به دیتابیس
فایل config.php را باز کنید و اطلاعات دیتابیس خود را وارد کنید:

php
private $db_name = 'goldline';
private $username = 'root';
private $password = '';

۵. اجرا
پروژه را از طریق مرورگر باز کنید:

text
http://localhost/Goldline-Project/index.php

🔒 نکته امنیتی
فایل config.php شامل اطلاعات حساس دیتابیس (نام کاربری، رمز عبور، نام دیتابیس) است.
این فایل را هرگز در گیت‌هاب آپلود نکنید.
با استفاده از فایل .gitignore از آن محافظت شده است.

🤝 مشارکت و توسعه
اگر ایده‌ای برای بهبود پروژه دارید یا باگی پیدا کرده‌اید، خوشحال می‌شویم از طریق Pull Request یا Issue در گیت‌هاب با ما در میان بگذارید.

موفق باشید! 🚀

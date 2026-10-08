<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="320" alt="Laravel Logo">
</p>

<h1 align="center">💸 MoneyFlow — Smart Financial & Surplus Investment Management</h1>

<p align="center">
  A modern, high-precision personal finance management web application built with <b>Laravel</b> to track cash flow, categorize priority vs. flexible expenses, and allocate <i>net surplus</i> into financial assets and skill development roadmaps.
</p>

<p align="center">
  <a href="https://php.net"><img src="https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.3"></a>
  <a href="https://laravel.com"><img src="https://img.shields.io/badge/Laravel-11%2B-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel"></a>
  <a href="https://tailwindcss.com"><img src="https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS"></a>
  <a href="https://vitejs.dev"><img src="https://img.shields.io/badge/Vite-646CFF?style=for-the-badge&logo=vite&logoColor=white" alt="Vite"></a>
  <a href="https://mysql.com"><img src="https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL"></a>
  <a href="LICENSE"><img src="https://img.shields.io/badge/License-MIT-green.svg?style=for-the-badge" alt="License"></a>
</p>

---

## 🌟 Key Features

### 1. 📊 Real-Time Surplus & Financial Health Engine
- Automatically computes **Gross Surplus** using `Total Income - Total Expenses`.
- Dynamic financial health indicator:
  - 🟢 **Healthy** (Positive surplus)
  - 🟡 **Balanced** (Break-even cash flow)
  - 🔴 **Deficit** (Expenses exceed income)

### 2. 💵 Income Stream Management
- Record multiple income streams (Salary, Freelance, Business, Dividends, Asset Liquidation, etc.).
- Filter transactions by month and year with instant summary recalculations.

### 3. 🎯 Smart Expense Categorization
- **Priority Expenses**: Essential and obligatory living costs (Housing, groceries, utilities, debt servicing, insurance).
- **Flexible Expenses**: Lifestyle and discretionary spending (Dining out, entertainment, shopping, hobbies) with real-time budget ratio tracking.

### 4. 🚀 Surplus Investment Allocation Hub
A dedicated allocation hub to direct remaining surplus funds into productive growth:
- 📈 **Financial Investments**: Stocks, Mutual Funds, Bonds/SBN, Gold, High-Yield Savings, Crypto.
- 📚 **Skill & Knowledge Investments**: Books, professional certifications, coding bootcamps, courses, and mentoring.
- Built-in allocation safeguards to prevent over-allocation (*exceeding surplus protection*).

### 5. 📑 Unified Ledger & Data Export
- Comprehensive **Unified Ledger** merging incomes, expenses, and investment allocations chronologically.
- **Export to CSV** for offline spreadsheets, audits, and tax preparation.
- **1-Click Demo Login** button for instant sandbox evaluation.

---

## 📐 Financial Logic Architecture

```text
┌────────────────────────────────────────────────────────┐
│  Gross Surplus = Total Incomes - Total Expenses        │
└───────────────────────────────────┬────────────────────┘
                                    │
       ┌────────────────────────────┴───────────────────────────┐
       ▼                                                        ▼
[ Financial Investments ]                               [ Skill Investments ]
 (Stocks, Mutual Funds, Gold)                            (Books, Certifications, Courses)
       │                                                        │
       └────────────────────────────┬───────────────────────────┘
                                    │
                                    ▼
           [ Remaining Unallocated Surplus (Emergency / Reserve) ]
```

---

## 🛠️ Tech Stack

- **Backend:** [Laravel](https://laravel.com/) (PHP 8.3)
- **Frontend:** [Blade Templates](https://laravel.com/docs/blade), [Tailwind CSS](https://tailwindcss.com/), [Alpine.js](https://alpinejs.dev/)
- **Build Tool:** [Vite](https://vitejs.dev/)
- **Database:** [MySQL](https://www.mysql.com/) / [SQLite](https://sqlite.org/)
- **Authentication:** [Laravel Breeze](https://laravel.com/docs/starter-kits#laravel-breeze)
- **Testing:** [PHPUnit](https://phpunit.de/)

---

## 🚀 Getting Started

### Prerequisites
- PHP >= 8.3 with required extensions (pdo, mbstring, openssl)
- Composer
- Node.js & NPM
- MySQL / MariaDB (or Laragon / XAMPP)

### Installation Steps

1. **Clone the repository:**
   ```bash
   git clone https://github.com/Akbardwi123/money-flow.git
   cd money-flow
   ```

2. **Install PHP and Node dependencies:**
   ```bash
   composer install
   npm install
   ```

3. **Set up the environment file:**
   ```bash
   cp .env.example .env
   ```

4. **Generate the application key:**
   ```bash
   php artisan key:generate
   ```

5. **Configure your Database in `.env`:**
   Adjust database credentials according to your local environment:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=money_flow
   DB_USERNAME=root
   DB_PASSWORD=
   ```

6. **Run database migrations and seeders:**
   ```bash
   php artisan migrate:fresh --seed
   ```

7. **Compile frontend assets and start the application:**
   ```bash
   npm run build
   php artisan serve
   ```
   Open your browser and navigate to: [http://localhost:8000](http://localhost:8000)

---

## 🧪 Running Automated Tests

The application is thoroughly covered by automated feature and unit tests:
```bash
php artisan test
```

---

## 👤 Demo Credentials

You can use the **Quick Demo Login** button on the authentication page or use the default seeded account:
- **Email:** `user@moneyflow.test`
- **Password:** `password`

---

## 📄 License

This open-source project is licensed under the [MIT License](LICENSE).

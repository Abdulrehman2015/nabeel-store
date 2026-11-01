# 🛒 Nabeel Bulk Sales Store — Laravel Wholesale E-Commerce Platform

A production-ready, dynamic bulk-sales and wholesale e-commerce platform built on **Laravel 12**, **PHP 8.2**, **Tailwind CSS**, and **Alpine.js**. Modeled after high-volume carton grocery platforms (such as [99bulksales.my](https://www.99bulksales.my/)), this system delivers a fast, mobile-first customer storefront and an administrative control panel where **100% of all configurations, delivery zones, pricing, themes, and legal policies are manageable without touching code**.

---

## 🌟 Key Architecture & Highlights

- **100% Free Services — Zero Recurring API Bills**:
  - **Mapping & Geocoding**: Leaflet.js + OpenStreetMap tiles + Nominatim address search (no Google Maps API fees or credit card required).
  - **Delivery Distance**: In-memory spherical **Haversine formula** calculated directly in PHP (`DeliveryService`).
  - **Instant Alerts**: Telegram Bot API via `@BotFather` + WhatsApp notifications via CallMeBot gateway + WhatsApp 1-click customer chat.
  - **Document Generation**: High-fidelity PDF Invoices rendered using `barryvdh/laravel-dompdf`.
  - **Catalog Spreadsheets**: Bulk product catalog import & export via `maatwebsite/excel`.
- **Wholesale Bulk Carton Engine**:
  - Dedicated pack/carton size fields (e.g., `12 x 1.25L Bottles`, `24 Cans / Carton`, `10kg Sack`).
  - Real-time minimum order value validation (default `RM 150.00`).
  - Carton unit descriptors and packaging volume display.
- **Smart Delivery & Floor Surcharge System**:
  - **Zone 1 (Free Delivery)**: Radius $\le 5\text{ km}$ (RM 0.00 delivery fee).
  - **Zone 2 (Paid Delivery)**: Next 2 km ($5.01\text{ km} - 7.00\text{ km}$) with configurable fixed or per-km surcharge (default RM 15.00).
  - **Zone 3 (Out of Coverage)**: Outside maximum radius ($> 7\text{ km}$) — checkout is safely blocked.
  - **Floor Surcharge**: Extra manual handling fees for carry-up deliveries above the free floor limit (e.g., Level 4 without lift = 3 floors $\times$ RM 10 = RM 30); **automatically waived** if building has an elevator.
- **Advance Deposit Payment Gate (Anti-Abuse)**:
  - Requires customers to transfer a deposit (default 10%) via DuitNow QR, Touch 'n Go eWallet, or Bank Transfer.
  - Payment proof screenshots are stored on an isolated **private disk** (`storage/app/private/payment_proofs/`) accessible exclusively to verified administrators through temporary signed URLs.
  - Inventory is deducted only upon **payment verification** by admin staff. Rejection restores stock automatically with audit logging.
- **Technical & On-Page SEO System**:
  - Full Google SERP Snippet Preview with live character length counters in admin.
  - Dynamic `/sitemap.xml` generated automatically from active categories and products.
  - Dynamic `/robots.txt` manageable from the admin dashboard.
  - Rich JSON-LD Structured Data: `Product`, `WholesaleStore`, and `BreadcrumbList`.
- **Live Theme & Appearance Engine**:
  - Visual color picker updating CSS variables (`--color-primary`, `--color-accent`, etc.) dynamically without requiring frontend build steps.
- **Compliance & Legal Policy Auditing**:
  - 10+ pre-seeded compliance pages (Terms, Privacy, Refund, PDPA Data Request, Cookie Policy).
  - Version history tracking with **1-click rollback** to earlier policy versions.
  - Dynamic placeholder tags (e.g. `{store_name}`, `{free_delivery_radius}`, `{admin_phone}`) replaced dynamically with live settings.

---

## 🛠 Tech Stack

- **Backend**: Laravel 12.x, PHP 8.2+, MySQL 8.x
- **Frontend**: Blade Components, Tailwind CSS 3.4, Alpine.js 3.x
- **Authentication & RBAC**: Laravel Breeze (Roles: `admin`, `staff`, `customer`, and Guest Checkout)
- **Document Engines**: `barryvdh/laravel-dompdf:^3.1`, `maatwebsite/excel:^3.1`
- **Mapping**: Leaflet 1.9.4 & OpenStreetMap
- **Job Queues**: Database queue driver for emails & notifications

---

## 🚀 Quick Start & Installation

### 1. Prerequisites
- PHP 8.2 or higher (with extensions: `pdo_mysql`, `curl`, `mbstring`, `openssl`, `gd`, `zip`)
- Composer 2.x
- Node.js 18+ & NPM
- MySQL 8.x or MariaDB 10.4+

### 2. Clone & Install Dependencies
```bash
# Clone the repository
git clone https://github.com/your-username/nabeel-store.git
cd nabeel-store

# Install PHP dependencies
composer install

# Install & compile frontend assets
npm install
npm run build
```

### 3. Environment Setup
```bash
# Copy sample environment configuration
cp .env.example .env

# Generate application encryption key
php artisan key:generate
```

Configure your `.env` file with your MySQL database details:
```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nabeel_store
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Database Migration & Demo Data Seeding
Run the database migrations and seeders to populate categories, 40 wholesale carton products, default system settings, legal policies, and admin accounts:
```bash
php artisan migrate --seed
```

### 5. Create Storage Symlink
Link the public storage directory for product thumbnails and banners:
```bash
php artisan storage:link
```

### 6. Start Development Server & Queue Worker
```bash
# Terminal 1: Application server
php artisan serve

# Terminal 2: Notification & email queue worker
php artisan queue:work --tries=3
```
Visit the storefront at **http://127.0.0.1:8000** and the admin panel at **http://127.0.0.1:8000/admin**.

---

## 🔐 Default Demo Accounts

| Role | Email | Password | Access |
| :--- | :--- | :--- | :--- |
| **Super Admin** | `admin@99bulksales.my` | `Admin@12345!` | Full Admin Panel & Settings Access |
| **Warehouse Staff** | `staff@99bulksales.my` | `Staff@12345!` | Orders, Inventory & Verification |
| **Wholesale Buyer** | `customer@gmail.com` | `Customer@123!` | Storefront Customer Dashboard |

---

## 📡 Free Notification Integrations Guide

### 1. Free Telegram Bot Alerts (Instant Phone Pings)
1. Open Telegram and start a chat with **[@BotFather](https://t.me/BotFather)**.
2. Send `/newbot`, provide a name, and copy the generated **API Token**.
3. Message **[@userinfobot](https://t.me/userinfobot)** or add your bot to your admin channel to get your **Chat ID**.
4. In Admin Panel, navigate to **Settings $\rightarrow$ Notifications**, check **Enable Telegram**, and enter your Token and Chat ID.

### 2. Free WhatsApp Alerts via CallMeBot
1. Save `+34 644 44 24 99` to your mobile phone contacts as **CallMeBot**.
2. Send the exact WhatsApp message: `I allow callmebot to send me messages` to the contact.
3. CallMeBot will reply with your personal API Key.
4. In Admin Panel, enter your phone number in international format (e.g. `+60123456789`) and your API Key.

---

## 🧪 Automated Test Suite

The project includes feature tests covering Haversine distance calculations, multi-zone delivery evaluation, floor surcharge logic, atomic stock deductions, and sitemap generation:

```bash
# Run the core business rules and delivery engine tests
php artisan test --filter="DeliveryAndOrderTest|SeoAndTechnicalTest"
```

All 11 feature tests with 49 assertions run in under 4 seconds.

---

## 📁 Key Directory Structure

```
app/
├── Exports/                     # Excel product export templates
├── Helpers/settings.php         # Global setting() & format_currency() helpers
├── Http/
│   ├── Controllers/             # Storefront controllers (Cart, Checkout, etc.)
│   │   └── Admin/               # 12 Dedicated admin controllers
│   ├── Middleware/              # AdminMiddleware, SecurityHeadersMiddleware
├── Imports/                     # Excel bulk product importer
├── Mail/                        # Customer & admin queued notification mailables
├── Models/                      # 19 Eloquent Models with relationships & scopes
└── Services/
    ├── CartService.php          # Session cart management & subtotal recalculation
    ├── DeliveryService.php      # Haversine distance, zones & floor fee engine
    ├── NotificationService.php  # Telegram, WhatsApp (CallMeBot) & Mail dispatch
    ├── OrderService.php         # DB transactions, locking, proof storage & stock
    └── SeoService.php           # Breadcrumbs, Product & Organization JSON-LD
resources/
└── views/
    ├── admin/                   # Comprehensive management dashboards & settings
    ├── emails/                  # Responsive HTML order mail templates
    ├── layouts/                 # Storefront & Admin Blade layouts
    └── storefront/              # Product catalog, cart drawer & checkout map
tests/
└── Feature/
    ├── DeliveryAndOrderTest.php # Haversine math, floor fees & order transactions
    └── SeoAndTechnicalTest.php  # Sitemap.xml, robots.txt & schema validation
```

---

## 🔒 Security Best Practices Implemented

- **Private Disk Storage**: Customer bank transfer screenshots are kept strictly outside `public/` and served via signed, expiring admin routes.
- **Race Condition Prevention**: Orders lock inventory rows with `lockForUpdate()` inside atomic database transactions (`DB::transaction`).
- **Server-Side Truth**: All pricing, delivery zones, floor fees, and deposit amounts are recalculated server-side; client form values are never trusted.
- **Idempotency Protection**: Every checkout submission includes an idempotency key to prevent double-charges or duplicate order creation on network retries.
- **Rate Limiting**: Throttles placed on public search, contact forms, and order creation endpoints.
- **Security Headers**: HSTS, `X-Frame-Options: SAMEORIGIN`, `X-Content-Type-Options: nosniff`, and `Referrer-Policy` automatically injected on every HTTP response.

---

## 📄 License
Open-sourced software licensed under the [MIT License](LICENSE).

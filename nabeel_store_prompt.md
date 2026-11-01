# PROMPT FOR ANTIGRAVITY — Laravel Bulk Grocery Store (Professional, Fully Dynamic)

> Copy everything below this line and paste it into Antigravity. If it is too long for one message, give it phase by phase (Phase 1, then 2, ...). Each phase is written so it can be pasted on its own, after Phase 0.

---

## ROLE

You are a senior Laravel architect and full-stack developer. Build a complete, production-ready **bulk-sales e-commerce store** in **Laravel** with a full **admin panel**. Write clean, commented, well-structured code. After each phase, run migrations/seeders, test the feature, and tell me what to check.

## REFERENCE

Reference store: https://www.99bulksales.my/
That store sells groceries and household items in bulk cartons (for example "FANTA 12*1.25L"). It has a category mega-menu, a homepage with a banner slider, promotion and "New" product sections, a "How it works" section, FAQs, a cart, and a checkout.

**Build something similar but better:** more professional, cleaner design, faster, mobile-first, easier to use, and **everything manageable from the admin panel with no hard-coded values.**

---

## PHASE 0 — TECH STACK AND PROJECT SETUP

- Laravel 11 or latest stable, PHP 8.2+, MySQL.
- Frontend: Blade + Tailwind CSS + Alpine.js (lightweight, no heavy SPA). Livewire is allowed for cart/checkout if it makes the code simpler.
- Admin panel: build a custom admin panel with a clean sidebar layout. If you prefer, you may use Filament (free) as long as every requirement below is met. Pick one and stay consistent.
- Authentication: Laravel Breeze (customer login/register, admin login). Use roles: `admin`, `customer`. Customers may also check out as a guest.
- Use **Eloquent relationships, Form Requests for validation, Policies/Middleware for admin access, Queues (database driver) for emails and notifications, Seeders and Factories** with demo data.
- **Use only FREE services.** No paid APIs anywhere. See the Free Services section.
- Folder structure must be clean (Services, Actions, Repositories where useful). Put business logic (delivery calculation, order creation) in **Service classes**, not in controllers.
- Provide a `README.md` with install steps: `composer install`, `.env` setup, `php artisan migrate --seed`, `php artisan storage:link`, `php artisan queue:work`.

---

## PHASE 1 — STOREFRONT (Customer Side)

Design: modern, clean, trustworthy, fast, fully responsive (mobile first). Avoid cluttered layouts. Use clear typography, spacing, and consistent buttons.

### Pages
1. **Home**
   - Header: logo, search bar (live search suggestions), Categories mega-menu, account menu, cart icon with item count and total.
   - Banner/slider (managed from admin, separate desktop and mobile images, optional link).
   - Category grid (images + product count).
   - Sections: Promotions, New Arrivals, Featured, Best Sellers. Each section can be turned on or off, and its title and ordering edited, from admin.
   - "How it works" steps section (editable from admin).
   - Benefits section (editable from admin).
   - FAQ accordion (managed from admin).
   - Footer: contact, working hours, links, social links, all from admin settings.
2. **Category page** with product grid, filters (price range, brand, in-stock), sorting (price, newest, popular), pagination.
3. **Product page**: image gallery, name, SKU, carton/pack size, price, sale price, stock status, quantity selector, add to cart, description, related products.
4. **Search results page.**
5. **Cart page** (slide-out mini cart plus a full page): change quantity, remove item, subtotal, and a clear message if the minimum order value is not met (minimum order value is dynamic from admin).
6. **Checkout page** (see Phase 3).
7. **Order success page** and **Order tracking page** (by order number plus phone).
8. **Customer account:** orders list, order details, saved addresses.
9. **Static/CMS pages:** About, FAQs, Contact, plus all legal and policy pages listed in Phase 8 (all editable from admin).

---

## PHASE 2 — ADMIN PANEL (Everything Dynamic)

Admin dashboard with: today's orders, revenue, pending payments, pending orders, low stock, recent orders, a simple sales chart.

### Modules
- **Categories:** name, slug, image, parent category (nested), status, sort order, **SEO fields** (see Phase 5).
- **Brands** (optional but recommended).
- **Products:** name, slug, SKU, category, brand, short and full description, price, sale price, stock quantity, unit/carton info, weight/volume (optional), multiple images with a featured image, status, flags (featured, new, promo), **SEO fields**.
- **Bulk product import/export via CSV/Excel** (very important for a store with hundreds of products). Use `maatwebsite/excel` (free).
- **Orders:** list with filters (status, date, payment status), order details, change status, add internal notes, print invoice (PDF using `barryvdh/laravel-dompdf`, free), verify or reject advance payment.
- **Customers.**
- **Banners/Sliders.**
- **CMS Pages, FAQs, "How it works" steps, Benefits.**
- **Delivery Settings** (Phase 3).
- **Payment Settings** (Phase 3).
- **Notification Settings** (Phase 4).
- **Theme/Appearance Settings** (Phase 6).
- **Settings → Master SEO** and **Page SEO** (Phase 5).
- **Roles & permissions** for staff (simple: admin and staff).
- **Activity log** for important changes (optional).

All settings must be stored in a `settings` table (key/value, cached) with a helper `setting('key', 'default')`. Clear the cache automatically when settings are saved.

---

## PHASE 3 — DELIVERY, CHECKOUT AND PAYMENT LOGIC (MOST IMPORTANT)

### 3.1 Admin → Delivery Settings module
Fields (all editable, with validation and helper text):

| Field | Description |
|---|---|
| Store Location | Choose on a map (Leaflet + OpenStreetMap, **free**). Admin can search an address, drag a pin, and the latitude and longitude are saved. Also allow typing lat/lng manually. |
| Free Delivery Radius (km) | Example: 5 means free delivery within 5 km of the store. |
| Paid Delivery Radius (km) | **Starts where the free radius ends.** Example: free = 5, paid = 2 means paid delivery from 5 km to 7 km. |
| Paid Delivery Charge | A charge for the paid zone. Support two modes selectable by admin: **Fixed amount**, or **Per km** (charge × km beyond the free radius). |
| Free Floor Limit | The highest floor with free delivery (default 1, meaning ground floor and 1st floor are free). |
| Extra Floor Charge | Charge **per floor** above the free floor limit. Example: free up to floor 1, charge 10 per extra floor. Floor 4 = 3 extra floors × 10 = 30. |
| Minimum Order Value | Dynamic. |
| Maximum Floor Allowed | Optional limit. |
| Currency symbol and decimals | Dynamic. |
| Delivery enable or disable | A master switch. |

### 3.2 Distance and zone calculation
- Calculate the distance between the store and the customer location using the **Haversine formula** (straight-line distance in km). Put this in a `DeliveryService` class with unit tests.
- Zones:
  - `distance <= free_radius` → **Free delivery**
  - `free_radius < distance <= free_radius + paid_radius` → **Paid delivery** (charge per admin settings)
  - `distance > free_radius + paid_radius` → **Show error: "Delivery not available in your area."** and block checkout.
- Example for testing: free = 5, paid = 2. A customer at 4 km is free, 6 km is paid, and 8 km gets "not available".

### 3.3 Customer location at checkout
- The customer picks their delivery location on a **Leaflet + OpenStreetMap** map: "Use my current location" (browser geolocation), search address (Nominatim, free; respect its usage policy and add debounce and a proper User-Agent), or drag a pin.
- On every pin change, call a backend endpoint that returns: distance, zone, delivery charge, and a message. Update the order summary instantly (AJAX/Livewire, no page reload).
- Validate the zone again on the server when the order is placed. Never trust frontend values.

### 3.4 Checkout form
Fields: full name, phone (with country code), email, full address, area/city, **Floor (REQUIRED)**, landmark (optional), order notes (optional), map location (required).

**Floor field rules:**
- Required, numeric, 0 = ground floor.
- Directly under the field, show a dynamic helper text built from admin settings, for example:
  > "Up to floor {free_floor_limit}: **Free delivery**. Above floor {free_floor_limit}: **{extra_floor_charge} per extra floor**."
- Floor charge = `max(0, floor - free_floor_limit) × extra_floor_charge`. Update live as the customer types.

### 3.5 Order summary (shown on the checkout page, always visible)
Show every price line clearly:
1. Cart items (image, name, qty, price, line total)
2. **Subtotal**
3. **Floor charges**
4. **Delivery charges** (show "Free" if free)
5. **Grand Total**
6. **Advance Payment Required (X% of Grand Total)**: X comes from admin.
7. **Remaining balance to pay on delivery**

### 3.6 Advance payment (QR) — dynamic
- Admin → Payment Settings: **Advance payment percentage** (default 10), **QR code image upload**, account name/bank/number details text, payment instructions text, and "Require advance payment" on/off.
- Advance amount = `grand_total × percentage / 100` (rounded per admin currency settings).
- On checkout, the customer sees the QR code, the exact amount to pay, and the instructions.
- **The order must only be created AFTER the customer submits payment proof.** Flow:
  1. Customer fills the form, and the summary shows the advance amount.
  2. Customer scans the QR and pays.
  3. Customer uploads a **payment screenshot** and enters a **transaction/reference ID** (both required).
  4. Only then does the system create the order with status `Pending Payment Verification`.
  5. Admin checks the screenshot and clicks **Verify Payment** (order becomes `Confirmed`) or **Reject** (with a reason, and the customer is notified).
- Important: a plain QR code has no automatic payment confirmation (no free API for it), so manual verification by admin with a screenshot is the correct free approach. Make the verification screen fast and easy: large screenshot preview, zoom, one-click approve or reject.
- Do not save the order or deduct stock before proof is submitted. Use a draft/session cart until then. Prevent duplicate transaction IDs.

### 3.7 Order statuses
`Pending Payment Verification → Confirmed → Processing → Out for Delivery → Delivered`, plus `Cancelled` and `Payment Rejected`. Keep a status history log with timestamps. Order numbers must be unique and human friendly (example: `BS-2026-000123`).

---

## PHASE 4 — NOTIFICATIONS (FREE ONLY — NO PAID SERVICES)

When a new order is created:

1. **Email to the customer** (order confirmation with the full order summary) and **email to the admin** (new order alert with details and a link to the admin order page).
   - Use Laravel Mail with SMTP configured from `.env` (free options: Gmail SMTP with an App Password, or Brevo/Mailgun free tiers). Document the setup in the README.
   - Send via **queue** so checkout is never slow. Use nice responsive HTML email templates (Markdown mailables).
   - Also send emails when the status changes (payment verified/rejected, out for delivery, delivered).
2. **Notification to the admin's phone number (free)**. Implement these in order of preference, and let admin choose in Notification Settings:
   - **A) Telegram Bot (recommended, 100% free and fully automatic):** the admin creates a bot via BotFather and puts the bot token and chat ID into admin settings. On every new order, send a formatted message (order number, customer name, phone, total, advance paid, address, map link, admin link).
   - **B) CallMeBot WhatsApp (free, unofficial, automatic, small limits):** admin enters their phone number and API key in settings. Send the order text through the CallMeBot HTTP API. Add a clear note that it is unofficial and can have limits.
   - **C) WhatsApp click-to-chat fallback (always works, free):** on the admin order page and the success page, add a "Send on WhatsApp" button that opens `https://wa.me/<admin_number>?text=<prefilled order details>`. This one is manual because the person must tap Send.
   - Explain honestly in the README that the official WhatsApp Business API is not fully free for this, which is why options A to C are used.
3. Admin can toggle each channel on or off, edit the admin phone number, admin email(s), and edit the message templates (with placeholders like `{order_number}`, `{customer_name}`, `{total}`) from the admin panel.
4. Add an **in-app admin notification bell** with a new-order sound or badge (polling every 30 seconds is fine).
5. Log every notification attempt and its result so failures are visible to the admin, and retry failed jobs.

---

## PHASE 5 — SEO SYSTEM

### 5.1 SEO fields for Categories and Products
Each category and product has: Meta Title, Meta Description, Meta Keywords, Canonical URL, Robots (index/noindex, follow/nofollow), Open Graph title/description/image, Twitter card fields, Focus keyword, and Schema type. Show a **character counter** and a **Google snippet preview** in the admin form. Auto-generate defaults from the name and description if left empty.

### 5.2 Master SEO module (Admin → Settings → Master SEO)
Global defaults: site name, default meta title template (example: `%title% | %site_name%`), default description, default OG image, favicon, default robots, Google Search Console and Bing verification tags, Google Analytics / Tag Manager IDs, Facebook Pixel ID, social profile links, organization/local-business schema data, robots.txt editor, and custom header/footer script boxes. These apply wherever a page-level value is empty.

### 5.3 Page SEO module
- Admin → Page SEO: a dropdown to **select a page** (Home, Category listing, Cart, Checkout, About, FAQs, Contact, Terms, Privacy, Login, Register, and any CMS page) and add that page's SEO fields (same fields as above). Admin can edit or reset to default.
- Fallback priority: **Page/Item SEO → Master SEO defaults → auto-generated**.

### 5.4 Technical SEO
- Auto-generated **XML sitemap** (`/sitemap.xml`) covering products, categories, and pages; **robots.txt**; canonical tags; clean slugs; breadcrumbs with BreadcrumbList schema; **Product JSON-LD** (price, availability, SKU, brand), Organization and WebSite schema; lazy-loaded optimised images (WebP) with alt text fields; 301 redirect manager in admin (optional).

---

## PHASE 6 — DYNAMIC THEME / COLOR SCHEME

- Admin → Appearance: color pickers for primary, secondary, accent, background, text, header background, footer background, button colors, and link colors, plus font choice (a few Google Fonts or system fonts), border radius, and logo/favicon upload.
- Implement with **CSS variables** injected into the layout from settings (for example `--color-primary`). Tailwind config should reference these variables so no rebuild is needed when colors change.
- Include a **live preview** and a "Reset to default" button, and check text/background contrast so unreadable combinations get a warning.
- Optional: light and dark mode toggle.

---

## PHASE 7 — SECURITY, QUALITY AND PERFORMANCE

### 7.1 Application security (implement all of these)
- **Authentication:** Laravel Breeze/Fortify with strong password rules (min 10 chars, mixed), password hashing (bcrypt/argon2), email verification for customers, password reset with expiring signed links, and **login throttling and lockout** after repeated failures.
- **Admin protection:** separate admin login URL (configurable, not the obvious `/admin` only), role-based access (admin, staff) via Policies and Gates, optional **two-factor authentication (2FA/TOTP)** for admin accounts, session timeout for admin, and "log out other devices".
- **Request protection:** CSRF tokens on all forms, **rate limiting** on login, register, checkout, search, and payment-proof upload, and honeypot or free CAPTCHA (Cloudflare Turnstile or hCaptcha free tier) on register/contact forms.
- **Input and output safety:** server-side validation with Form Requests everywhere, escape all output (Blade `{{ }}`), sanitize rich-text from admin (HTMLPurifier), use Eloquent/query bindings only (no raw SQL with user input), and mass-assignment protection (`$fillable`).
- **File upload security:** allow-list file types (jpg, png, webp, pdf), max size limits, rename files randomly, validate real MIME type, never execute uploads, and store **payment screenshots on a private disk** served only to authorised admins through a signed/controlled route.
- **Price and order integrity:** never trust prices, totals, delivery charge, floor charge, or advance amount sent from the browser. Recalculate everything on the server at order time. Use database transactions, row locking for stock, and prevent duplicate orders and duplicate transaction IDs (idempotency token).
- **Security headers and transport:** force HTTPS, HSTS, Content-Security-Policy (allow the map tile and CDN domains in use), `X-Frame-Options`, `X-Content-Type-Options`, `Referrer-Policy`, `Permissions-Policy` (allow geolocation only for the store itself), secure and HttpOnly cookies with SameSite.
- **Secrets and config:** all keys (SMTP, Telegram token, CallMeBot key) stored in `.env` or encrypted settings in the database (use Laravel `Crypt`/encrypted casts), never printed in logs or shown in plain text in admin (mask them).
- **Data protection:** encrypt sensitive fields where appropriate, minimise the personal data stored, provide **customer data export and account deletion request** (see Phase 8), and add a data-retention setting for old payment screenshots.
- **Monitoring:** admin **activity/audit log** (who changed what and when: prices, settings, order status, payment verification), login history, failed-login log, and Laravel error logging with no sensitive data in logs.
- **Dependencies and backups:** pin dependency versions, run `composer audit`, set `APP_DEBUG=false` in production, and document a daily database and uploads backup routine (free: scheduled `mysqldump` plus Laravel Backup package).
- **Fraud and abuse controls:** optional admin blocklist (phone, email, IP), limit repeated unverified orders from the same phone or IP, and flag suspicious orders.

### 7.2 Quality and performance
- Performance: eager loading to avoid N+1 queries, caching for settings/categories/menus, image optimisation, pagination everywhere.
- Add feature tests for: delivery zone calculation (free/paid/unavailable), floor charges, advance percentage calculation, order creation only after payment proof, server-side price recalculation, admin-only access to payment screenshots, and rate limiting.
- Accessibility basics and clean error pages (404, 419, 429, 500, 503 maintenance page).
- Seed demo data: about 10 categories and 40 products so I can test right away, plus default settings (free radius 5 km, paid radius 2 km, advance 10%, free floor limit 1).

---

## PHASE 8 — POLICY AND LEGAL PAGES (ALL DYNAMIC)

Create a **Legal & Policy Pages module** in the admin panel. Each policy is a CMS page with: title, slug, rich-text content (WYSIWYG editor), status (published/draft), "show in footer" toggle, "show at checkout" toggle, sort order, last-updated date, **version history** (keep previous versions and allow restore), and its own SEO fields (ties into the Page SEO module in Phase 5).

### 8.1 Pages to create (seed each with a professional default template)

| Page | URL slug | Purpose |
|---|---|---|
| Privacy Policy | `/privacy-policy` | What personal data is collected (name, phone, email, address, location, payment proof), why, how long it is kept, who it is shared with, user rights, cookies, contact for privacy requests |
| Terms & Conditions | `/terms-and-conditions` | Rules of using the store, account responsibility, pricing and stock disclaimers, order acceptance rights, limitation of liability, governing law |
| Refund & Return Policy | `/refund-and-return-policy` | Which items can be returned, time window, condition rules, damaged or wrong item process, refund method and timeline, non-returnable items |
| Shipping & Delivery Policy | `/shipping-and-delivery-policy` | Delivery areas, free and paid radius explanation, floor charges, delivery times and cut-off time, failed delivery rules. **Auto-fill the numbers (free radius, paid radius, floor rules) from Delivery Settings using placeholders** so the page never goes out of date |
| Payment Policy | `/payment-policy` | Advance payment percentage (auto-filled from Payment Settings), QR payment steps, payment verification time, balance on delivery, what happens if payment is rejected |
| Cancellation Policy | `/cancellation-policy` | When customers can cancel, advance payment treatment on cancellation, how to request it |
| Cookie Policy | `/cookie-policy` | Types of cookies used, purposes, how to manage or withdraw consent |
| Security Policy | `/security-policy` | Plain-language summary of how customer data and payments are protected, and how to report a security issue |
| Disclaimer | `/disclaimer` | Product image and description accuracy, price changes, availability, third-party links |
| Order Tracking / Help | `/track-order`, `/help` | Order tracking and help centre links |
| FAQs | `/faqs` | Managed from the FAQ module |
| About Us and Contact Us | `/about`, `/contact` | Company information and a contact form that saves messages in admin and emails the admin |
| Accessibility Statement (optional) | `/accessibility` | Basic accessibility commitment |
| Data Request Page | `/data-request` | Customers can request a copy of their data or deletion of their account (creates a request in admin) |

### 8.2 Dynamic placeholders in policy content
Support placeholders that are replaced automatically: `{store_name}`, `{store_email}`, `{store_phone}`, `{store_address}`, `{company_registration_no}`, `{free_delivery_radius}`, `{paid_delivery_radius}`, `{free_floor_limit}`, `{extra_floor_charge}`, `{advance_percentage}`, `{min_order_value}`, `{order_cutoff_time}`, `{return_window_days}`, `{current_year}`, `{last_updated}`. Show the list of available placeholders beside the editor.

### 8.3 Compliance features
- **Cookie consent banner** (free, built in-house): Accept all, Reject non-essential, and Customize. Store the choice in a cookie and load analytics or pixel scripts (Google Analytics, Facebook Pixel from Master SEO) **only after consent**. Banner text, colors, and position are editable from admin.
- **Terms acceptance at checkout and registration:** a required checkbox "I agree to the Terms & Conditions, Privacy Policy, and Refund Policy" with links (which pages appear is chosen in admin). Save the accepted policy versions, timestamp, and IP address on the order or user record as proof of consent.
- **Footer links** to all policies marked "show in footer", grouped under "Legal" and "Customer Care".
- **Policy update notice:** when admin publishes a new version of a policy, optionally show a banner or email registered customers.
- **Customer data tools:** from the account page, the customer can download their data (JSON/CSV) and request account deletion; admin reviews the request and anonymises personal data while keeping the order records that the law requires.
- Add a short **admin note**: default templates are general-purpose starting points and must be reviewed and adapted by a qualified person to match the local laws of the country where the store operates (for example consumer protection and data protection laws).

---

## DELIVERABLE CHECKLIST (verify each before finishing)

- [ ] Floor field is required and shows the dynamic free-or-charge helper text.
- [ ] Floor charge, free radius, paid radius, paid charge, and free floor limit are all editable in Admin → Delivery Settings.
- [ ] Store location is set on a map in admin; the customer picks a location on a map in checkout.
- [ ] Free zone, paid zone, and "Delivery not available in your area" all work and are enforced on the server.
- [ ] Checkout shows subtotal, floor charges, delivery charges, grand total, and advance amount (percentage from admin).
- [ ] The order is created only after QR payment proof is submitted; admin can verify or reject.
- [ ] Customer and admin both receive email on a new order; admin also gets a Telegram, CallMeBot, or WhatsApp-link notification, all free.
- [ ] Store colors, logo, and fonts change from admin and apply site-wide.
- [ ] SEO fields exist on categories and products; Master SEO and Page SEO modules work; sitemap, robots, and schema output are correct.
- [ ] Everything (texts, banners, FAQs, steps, settings) is editable from admin with nothing hard-coded.
- [ ] All security items in Phase 7.1 are implemented (rate limits, CSRF, private payment screenshots, server-side price recalculation, security headers, admin 2FA, audit log).
- [ ] All policy pages in Phase 8.1 exist, are editable from admin with version history, and use dynamic placeholders.
- [ ] Cookie consent banner works and blocks analytics/pixels until consent is given.
- [ ] Terms acceptance checkbox appears at checkout and registration, and the accepted version, time, and IP are saved.
- [ ] Customers can request data export or account deletion; admin can process the request.
- [ ] README with setup steps and how to configure SMTP, Telegram, and CallMeBot, plus a backup and security checklist for going live.

Start with Phase 0 and Phase 1, then continue phase by phase. After each phase, summarize what was built and what I should test.

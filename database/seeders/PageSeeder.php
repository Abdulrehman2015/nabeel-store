<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\PageVersion;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'show_in_footer' => true,
                'show_at_checkout' => true,
                'sort_order' => 1,
                'content' => <<<HTML
<h2>Privacy Policy for {store_name}</h2>
<p><strong>Last Updated: {last_updated}</strong></p>
<p>{store_name} (Company Registration No: {company_registration_no}) is committed to safeguarding the personal data of our customers and visitors in compliance with applicable Personal Data Protection laws.</p>

<h3>1. Data We Collect</h3>
<p>To process wholesale bulk orders and arrange doorstep carton logistics, we collect:</p>
<ul>
    <li><strong>Identity & Contact Information:</strong> Full name, phone number, and email address.</li>
    <li><strong>Delivery Information:</strong> Physical delivery address, city, floor level, landmarks, and geographic coordinates selected on our interactive map.</li>
    <li><strong>Payment Verification Data:</strong> Bank transaction reference IDs and payment confirmation screenshot receipts uploaded during checkout.</li>
    <li><strong>Device & Session Data:</strong> IP address, browser type, and cookie preferences.</li>
</ul>

<h3>2. Purpose of Collection</h3>
<p>We process your data strictly to verify advance payments, fulfill and dispatch carton deliveries to your floor, communicate order status updates, prevent fraudulent submissions, and meet statutory accounting regulations.</p>

<h3>3. Data Storage & Protection</h3>
<p>Payment receipt screenshots are maintained on private, non-public storage directories accessible solely by authorised administrators via cryptographically signed temporary tokens. Personal data is never sold, traded, or shared with third-party advertising brokers.</p>

<h3>4. Your Rights & Data Requests</h3>
<p>You possess the right to access your personal data, request corrections, export your purchase history, or request account deletion via our <a href="/data-request">Data Request Page</a>.</p>

<h3>5. Contact Us</h3>
<p>For inquiries regarding this policy, contact our Data Privacy Officer at <a href="mailto:{store_email}">{store_email}</a> or phone {store_phone}.</p>
HTML
            ],
            [
                'title' => 'Terms & Conditions',
                'slug' => 'terms-and-conditions',
                'show_in_footer' => true,
                'show_at_checkout' => true,
                'sort_order' => 2,
                'content' => <<<HTML
<h2>Terms and Conditions</h2>
<p><strong>Effective Date: {last_updated}</strong></p>
<p>Welcome to {store_name}. By accessing our bulk ordering platform or placing an order, you agree to comply with and be bound by the following Terms and Conditions.</p>

<h3>1. Wholesale Carton Supply</h3>
<p>Products on {store_name} are sold in manufacturer carton or case packaging as indicated in each product description (e.g., "12*1.25L", "24*320ML"). Individual unit splitting is not supported unless explicitly noted.</p>

<h3>2. Minimum Order Value</h3>
<p>The minimum order requirement across our store is {min_order_value}. Orders below this value cannot be processed through checkout.</p>

<h3>3. Order Placement & Advance Deposit</h3>
<p>Orders are officially placed only upon submission of valid advance payment proof (representing {advance_percentage} of the total order value). Submitting a falsified or duplicated payment receipt constitutes breach of terms and may lead to cancellation and blacklisting.</p>

<h3>4. Price & Availability</h3>
<p>While we make every endeavor to maintain accurate live inventory and wholesale pricing, we reserve the right to correct manifest clerical errors and notify you prior to dispatch.</p>

<h3>5. Governing Law</h3>
<p>These terms shall be governed by and construed in accordance with the laws of Malaysia, and parties submit to the exclusive jurisdiction of the competent courts.</p>
HTML
            ],
            [
                'title' => 'Refund & Return Policy',
                'slug' => 'refund-and-return-policy',
                'show_in_footer' => true,
                'show_at_checkout' => true,
                'sort_order' => 3,
                'content' => <<<HTML
<h2>Refund and Return Policy</h2>
<p><strong>Last Updated: {last_updated}</strong></p>
<p>At {store_name}, customer satisfaction with our wholesale goods is our highest priority.</p>

<h3>1. Inspection Upon Delivery</h3>
<p>Because bulk goods are delivered via dedicated ground delivery, customers or their authorized representatives must inspect outer carton seals upon receipt before settling the final cash or transfer balance.</p>

<h3>2. Return Window</h3>
<p>Claims for missing items, damaged packaging during transit, or expired stock must be reported within <strong>{return_window_days}</strong> of delivery with clear photographic evidence.</p>

<h3>3. Non-Returnable Goods</h3>
<p>Perishable food items, opened cartons where individual units have been consumed or removed, and items damaged due to inappropriate storage after delivery cannot be returned.</p>

<h3>4. Refund Method</h3>
<p>Approved refunds for verified defective or non-delivered carton goods are processed via direct bank transfer or instant DuitNow back to the originating account within 3 to 5 business days.</p>
HTML
            ],
            [
                'title' => 'Shipping & Delivery Policy',
                'slug' => 'shipping-and-delivery-policy',
                'show_in_footer' => true,
                'show_at_checkout' => false,
                'sort_order' => 4,
                'content' => <<<HTML
<h2>Shipping and Delivery Policy</h2>
<p><strong>Last Updated: {last_updated}</strong></p>
<p>{store_name} operates specialized logistics vehicles suited for transporting heavy wholesale food and grocery cartons.</p>

<h3>1. Delivery Zones & Distance Calculations</h3>
<p>Delivery distance is calculated dynamically using straight-line geographical coordinates from our central fulfillment hub at {store_address}:</p>
<ul>
    <li><strong>Free Delivery Zone:</strong> Deliveries within <strong>{free_delivery_radius}</strong> receive 100% Free Shipping.</li>
    <li><strong>Paid Delivery Zone:</strong> Deliveries from <strong>{free_delivery_radius} up to {paid_delivery_radius}</strong> additional radius are serviced at nominal delivery rates calculated at checkout.</li>
    <li><strong>Beyond Serviceable Area:</strong> Addresses beyond our maximum serviceable radius cannot be accommodated via standard delivery.</li>
</ul>

<h3>2. Floor Level Policy & Charges</h3>
<p>Due to the heavy physical weight of wholesale cartons, floor level rules apply:</p>
<ul>
    <li><strong>Up to {free_floor_limit}:</strong> Completely <strong>FREE delivery</strong>.</li>
    <li><strong>Above {free_floor_limit}:</strong> Surcharge of <strong>{extra_floor_charge}</strong> per extra floor level.</li>
</ul>

<h3>3. Order Cut-Off Time</h3>
<p>Orders confirmed and payment-verified before <strong>{order_cutoff_time}</strong> are scheduled for next-day dispatch. Orders received after this cut-off time are batched for the subsequent dispatch cycle.</p>
HTML
            ],
            [
                'title' => 'Payment Policy',
                'slug' => 'payment-policy',
                'show_in_footer' => true,
                'show_at_checkout' => false,
                'sort_order' => 5,
                'content' => <<<HTML
<h2>Payment Policy</h2>
<p><strong>Last Updated: {last_updated}</strong></p>
<p>To provide high wholesale value and eliminate third-party gateway fee markups, {store_name} utilizes a streamlined QR advance payment model.</p>

<h3>1. Advance Deposit Requirement</h3>
<p>Customers are required to transfer an advance deposit of <strong>{advance_percentage}</strong> of the grand total at the time of checkout. This deposit confirms your booking and reserves heavy warehouse inventory.</p>

<h3>2. How to Pay Advance</h3>
<ol>
    <li>Scan the displayed DuitNow QR code or initiate an instant bank transfer using your mobile banking application.</li>
    <li>Input the exact advance figure stated on your order summary.</li>
    <li>Save or screenshot the completed transaction confirmation.</li>
    <li>Submit the screenshot and unique Reference ID into the checkout portal.</li>
</ol>

<h3>3. Balance Payment on Delivery</h3>
<p>The remaining balance is payable upon delivery directly to our delivery team via Cash, DuitNow QR on delivery, or instant transfer upon receipt of cartons.</p>

<h3>4. Payment Rejection</h3>
<p>If a submitted payment screenshot is unreadable, invalid, or shows a mismatched transaction amount, our administrators will flag the order as <em>Payment Rejected</em> with a written reason. You may re-submit proof or contact customer care at {store_phone}.</p>
HTML
            ],
            [
                'title' => 'Cancellation Policy',
                'slug' => 'cancellation-policy',
                'show_in_footer' => true,
                'show_at_checkout' => false,
                'sort_order' => 6,
                'content' => <<<HTML
<h2>Cancellation Policy</h2>
<p><strong>Last Updated: {last_updated}</strong></p>
<p>We understand plans change. Here is our policy regarding order cancellations:</p>

<h3>1. Cancellation Before Verification</h3>
<p>If your order is in <em>Pending Payment Verification</em> status and you wish to cancel, you may contact our customer support team immediately to cancel with full advance refund.</p>

<h3>2. Cancellation After Dispatch</h3>
<p>Once an order has been marked as <em>Out for Delivery</em>, the delivery run cannot be halted in transit. Cancellations at the doorstep may be subject to a logistics handling fee to cover vehicle dispatch and driver costs.</p>
HTML
            ],
            [
                'title' => 'Cookie Policy',
                'slug' => 'cookie-policy',
                'show_in_footer' => true,
                'show_at_checkout' => false,
                'sort_order' => 7,
                'content' => <<<HTML
<h2>Cookie Policy</h2>
<p><strong>Last Updated: {last_updated}</strong></p>
<p>{store_name} uses cookies and related browser storage technologies to deliver a secure, responsive browsing experience.</p>

<h3>1. Essential Cookies</h3>
<p>These cookies are technically indispensable for operating the website, preserving items in your shopping cart across pages, and protecting against CSRF attacks. They do not store personally identifiable promotional profiles.</p>

<h3>2. Analytics & Performance Cookies</h3>
<p>Non-essential analytics cookies (such as Google Analytics or visitor statistics) are loaded <strong>only after you grant explicit consent</strong> via our Cookie Consent banner.</p>

<h3>3. Managing Cookies</h3>
<p>You can customize your preferences or withdraw consent at any time via your browser settings or our cookie preference dialog.</p>
HTML
            ],
            [
                'title' => 'Security Policy',
                'slug' => 'security-policy',
                'show_in_footer' => true,
                'show_at_checkout' => false,
                'sort_order' => 8,
                'content' => <<<HTML
<h2>Security Policy</h2>
<p><strong>Last Updated: {last_updated}</strong></p>
<p>{store_name} implements defense-in-depth security principles to protect our customer transactions and infrastructure:</p>
<ul>
    <li><strong>Strict Access Controls:</strong> Payment screenshots and customer delivery addresses are stored in private storage partitions accessible solely by authenticated staff members through cryptographically signed temporary routes.</li>
    <li><strong>Encrypted Transport:</strong> All platform traffic is encrypted end-to-end via TLS/HTTPS with modern cipher suites and HSTS enforcement.</li>
    <li><strong>Server-Side Pricing Integrity:</strong> All product prices, delivery zone calculations, floor fees, and deposit requirements are strictly computed on the server during order creation to prevent client-side parameter tampering.</li>
    <li><strong>Rate Limiting & Abuse Prevention:</strong> Authentication, search queries, and payment proof submissions are throttled to avert brute-force and credential stuffing attacks.</li>
</ul>
HTML
            ],
            [
                'title' => 'Disclaimer',
                'slug' => 'disclaimer',
                'show_in_footer' => true,
                'show_at_checkout' => false,
                'sort_order' => 9,
                'content' => <<<HTML
<h2>Website & Product Disclaimer</h2>
<p><strong>Last Updated: {last_updated}</strong></p>
<p>The information and product imagery presented on {store_name} are provided in good faith. Product carton packaging graphics and minor manufacturer reformulations may vary slightly from displayed stock photos without altering the core specification or carton unit count.</p>
HTML
            ],
            [
                'title' => 'About Us',
                'slug' => 'about',
                'show_in_footer' => true,
                'show_at_checkout' => false,
                'sort_order' => 10,
                'content' => <<<HTML
<h2>About {store_name}</h2>
<p>{store_name} is a premier bulk grocery and household supplies distributor headquartered in Puchong, Selangor. Established to bridge the gap between heavy FMCG manufacturers and end-users, we empower small eateries, sundry grocers, offices, and large households to acquire everyday carton essentials at direct wholesale volume pricing.</p>

<h3>Our Mission</h3>
<p>To make wholesale bulk grocery shopping effortless, transparent, and economical through streamlined digital ordering and reliable local fleet delivery.</p>

<h3>Visit or Contact Our Distribution Center</h3>
<p>
    <strong>Address:</strong> {store_address}<br>
    <strong>Telephone:</strong> {store_phone}<br>
    <strong>Email:</strong> {store_email}<br>
    <strong>Business Hours:</strong> Monday through Saturday, 8:00 AM – 7:00 PM
</p>
HTML
            ],
        ];

        foreach ($pages as $p) {
            $page = Page::updateOrCreate(
                ['slug' => $p['slug']],
                [
                    'title' => $p['title'],
                    'content' => $p['content'],
                    'is_published' => true,
                    'show_in_footer' => $p['show_in_footer'],
                    'show_at_checkout' => $p['show_at_checkout'],
                    'sort_order' => $p['sort_order'],
                    'current_version' => 1,
                    'meta_title' => $p['title'] . ' | {store_name}',
                    'meta_description' => strip_tags(substr($p['content'], 0, 160)),
                    'robots' => 'index, follow',
                    'schema_type' => 'WebPage',
                ]
            );

            // Record initial version
            PageVersion::updateOrCreate(
                ['page_id' => $page->id, 'version_number' => 1],
                [
                    'title' => $page->title,
                    'content' => $page->content,
                    'change_summary' => 'Initial seeded template',
                ]
            );
        }
    }
}


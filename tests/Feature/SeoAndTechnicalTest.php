<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use App\Services\SeoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoAndTechnicalTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Setting::set('site_name', '99BulkSales Malaysia', 'seo');
        Setting::set('seo_meta_title_template', '{title} — {site_name}', 'seo');
        Setting::set('seo_default_description', 'Direct bulk carton wholesale grocery delivery.', 'seo');
        Setting::set('robots_txt_content', "User-agent: *\nDisallow: /admin/\nAllow: /", 'seo');
    }

    public function test_sitemap_xml_renders_correctly(): void
    {
        $category = Category::create([
            'name' => 'Soft Drinks',
            'slug' => 'soft-drinks',
            'is_active' => true,
        ]);

        Product::create([
            'category_id' => $category->id,
            'name' => 'FANTA 12*1.25L',
            'slug' => 'fanta-12-125l',
            'sku' => 'SKU-FANTA-1',
            'pack_size' => '12 x 1.25L',
            'price' => 45.00,
            'stock_quantity' => 50,
            'is_active' => true,
        ]);

        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
        $this->assertStringContainsString('application/xml', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('<urlset', $response->getContent());
        $this->assertStringContainsString('/category/soft-drinks', $response->getContent());
        $this->assertStringContainsString('/product/fanta-12-125l', $response->getContent());
    }

    public function test_robots_txt_renders_custom_content(): void
    {
        $response = $this->get('/robots.txt');

        $response->assertStatus(200);
        $this->assertStringContainsString('text/plain', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('Disallow: /admin/', $response->getContent());
    }

    public function test_seo_service_product_json_ld(): void
    {
        $category = Category::create([
            'name' => 'Biscuits',
            'slug' => 'biscuits',
            'is_active' => true,
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Oreo 24x120g Carton',
            'slug' => 'oreo-24x120g-carton',
            'sku' => 'OREO-24-120',
            'pack_size' => '24 Packs / Carton',
            'price' => 50.00,
            'sale_price' => 45.00,
            'stock_quantity' => 25,
            'is_active' => true,
        ]);

        $seo = app(SeoService::class);
        $jsonString = $seo->generateProductJsonLd($product);
        $jsonLd = json_decode($jsonString, true);

        $this->assertArrayHasKey('@context', $jsonLd);
        $this->assertEquals('https://schema.org', $jsonLd['@context']);
        $this->assertEquals('Product', $jsonLd['@type']);
        $this->assertEquals('Oreo 24x120g Carton', $jsonLd['name']);
        $this->assertEquals('45.00', $jsonLd['offers']['price']);
        $this->assertEquals('https://schema.org/InStock', $jsonLd['offers']['availability']);
    }
}

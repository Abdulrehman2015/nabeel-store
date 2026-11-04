<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Page;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class SeoService
{
    /**
     * Resolve final SEO metadata with fallback priority:
     * 1. Item / Page explicit SEO
     * 2. Master SEO default
     * 3. Auto-generated fallback
     */
    public function resolveMetadata(?Model $model = null, ?string $pageKey = null, array $custom = []): array
    {
        $siteName = setting('site_name', 'Bulk Mart');
        $titleTemplate = setting('seo_meta_title_template', '%title% | %site_name%');

        $rawTitle = $custom['title'] ?? null;
        $rawDescription = $custom['description'] ?? null;
        $rawKeywords = $custom['keywords'] ?? null;
        $canonical = $custom['canonical'] ?? url()->current();
        $robots = $custom['robots'] ?? setting('seo_default_robots', 'index, follow');
        $ogImage = $custom['og_image'] ?? setting('seo_default_og_image');
        $schemaType = $custom['schema_type'] ?? 'WebSite';

        // 1. If explicit Model (Product, Category, Page)
        if ($model) {
            $rawTitle = $model->meta_title ?: ($rawTitle ?: $model->name ?? $model->title ?? '');
            $rawDescription = $model->meta_description ?: ($rawDescription ?: ($model->short_description ?? substr(strip_tags($model->description ?? $model->content ?? ''), 0, 160)));
            $rawKeywords = $model->meta_keywords ?: $rawKeywords;
            $canonical = $model->canonical_url ?: $canonical;
            $robots = $model->robots ?: $robots;
            $ogImage = $model->og_image ?: ($model->featured_image ? $model->image_url : $ogImage);
            $schemaType = $model->schema_type ?: $schemaType;
        } elseif ($pageKey) {
            // Check Page SEO saved in settings (e.g. page_seo_home, page_seo_cart, etc.)
            $savedPageSeo = setting('page_seo_' . $pageKey);
            if (!empty($savedPageSeo) && is_string($savedPageSeo)) {
                $savedPageSeo = json_decode($savedPageSeo, true);
            }

            if (is_array($savedPageSeo)) {
                $rawTitle = $savedPageSeo['meta_title'] ?? $rawTitle;
                $rawDescription = $savedPageSeo['meta_description'] ?? $rawDescription;
                $rawKeywords = $savedPageSeo['meta_keywords'] ?? $rawKeywords;
                $canonical = $savedPageSeo['canonical_url'] ?? $canonical;
                $robots = $savedPageSeo['robots'] ?? $robots;
                $ogImage = $savedPageSeo['og_image'] ?? $ogImage;
                $schemaType = $savedPageSeo['schema_type'] ?? $schemaType;
            }
        }

        // Apply Master SEO fallbacks
        if (empty($rawTitle)) {
            $rawTitle = setting('seo_default_title', 'Groceries & Household Essentials - N&J Mini Mart');
        }

        if (empty($rawDescription)) {
            $rawDescription = setting('seo_default_description', 'Buy groceries, beverages, snacks and household goods with fast local delivery.');
        }

        if (empty($rawKeywords)) {
            $rawKeywords = setting('seo_default_keywords', 'groceries, mini mart, food staples, beverages, puchong mart');
        }

        if (empty($ogImage)) {
            $ogImage = asset('images/og-default.jpg');
        }

        // Generate formatted meta title using template
        $metaTitle = str_replace(['%title%', '%site_name%'], [$rawTitle, $siteName], $titleTemplate);

        return [
            'site_name' => $siteName,
            'title' => $metaTitle,
            'description' => $rawDescription,
            'keywords' => $rawKeywords,
            'canonical' => $canonical,
            'robots' => $robots,
            'og_title' => $custom['og_title'] ?? $metaTitle,
            'og_description' => $custom['og_description'] ?? $rawDescription,
            'og_image' => $ogImage,
            'twitter_card' => $custom['twitter_card'] ?? 'summary_large_image',
            'schema_type' => $schemaType,
        ];
    }

    /**
     * Generate Breadcrumbs Schema (BreadcrumbList).
     */
    public function generateBreadcrumbsJsonLd(array $breadcrumbs): string
    {
        $itemList = [];
        $position = 1;

        foreach ($breadcrumbs as $name => $url) {
            $itemList[] = [
                '@type' => 'ListItem',
                'position' => $position++,
                'name' => $name,
                'item' => $url,
            ];
        }

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $itemList,
        ];

        return json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    /**
     * Generate Product Schema (Product JSON-LD).
     */
    public function generateProductJsonLd(Product $product): string
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product->name,
            'image' => [$product->image_url],
            'description' => $product->short_description ?: strip_tags(substr($product->description ?? '', 0, 300)),
            'sku' => $product->sku,
            'offers' => [
                '@type' => 'Offer',
                'url' => route('product.show', $product->slug),
                'priceCurrency' => setting('currency_code', 'MYR'),
                'price' => number_format($product->current_price, 2, '.', ''),
                'availability' => $product->in_stock ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'itemCondition' => 'https://schema.org/NewCondition',
            ],
        ];

        if ($product->brand) {
            $schema['brand'] = [
                '@type' => 'Brand',
                'name' => $product->brand->name,
            ];
        }

        return json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    /**
     * Generate Organization & LocalBusiness JSON-LD schema.
     */
    public function generateOrganizationJsonLd(): string
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'GroceryStore',
            'name' => setting('site_name', 'N&J Mini Mart'),
            'url' => url('/'),
            'logo' => setting('site_logo') ? asset('storage/' . setting('site_logo')) : asset('images/logo.png'),
            'telephone' => setting('store_phone', '+60 12-345 6789'),
            'email' => setting('store_email', 'support@nabeelstore.my'),
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => setting('store_address', 'Puchong, Selangor'),
                'addressLocality' => 'Kuala Lumpur',
                'addressCountry' => 'MY',
            ],
            'geo' => [
                '@type' => 'GeoCoordinates',
                'latitude' => setting('store_latitude', 3.1390),
                'longitude' => setting('store_longitude', 101.6869),
            ],
            'priceRange' => '$$',
        ];

        return json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }
}


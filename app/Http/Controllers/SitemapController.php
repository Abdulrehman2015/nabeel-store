<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Page;
use App\Models\Product;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Generate dynamic XML sitemap.
     */
    public function sitemap(): Response
    {
        $products = Product::where('is_active', true)->select('slug', 'updated_at')->get();
        $categories = Category::where('is_active', true)->select('slug', 'updated_at')->get();
        $pages = Page::where('is_published', true)->select('slug', 'updated_at')->get();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        // Homepage
        $xml .= '<url>';
        $xml .= '<loc>' . url('/') . '</loc>';
        $xml .= '<lastmod>' . now()->toAtomString() . '</lastmod>';
        $xml .= '<changefreq>daily</changefreq>';
        $xml .= '<priority>1.0</priority>';
        $xml .= '</url>';

        // Categories Index
        $xml .= '<url>';
        $xml .= '<loc>' . route('category.index') . '</loc>';
        $xml .= '<lastmod>' . now()->toAtomString() . '</lastmod>';
        $xml .= '<changefreq>weekly</changefreq>';
        $xml .= '<priority>0.9</priority>';
        $xml .= '</url>';

        // Categories
        foreach ($categories as $cat) {
            $xml .= '<url>';
            $xml .= '<loc>' . route('category.show', $cat->slug) . '</loc>';
            $xml .= '<lastmod>' . $cat->updated_at->toAtomString() . '</lastmod>';
            $xml .= '<changefreq>weekly</changefreq>';
            $xml .= '<priority>0.8</priority>';
            $xml .= '</url>';
        }

        // Products
        foreach ($products as $prod) {
            $xml .= '<url>';
            $xml .= '<loc>' . route('product.show', $prod->slug) . '</loc>';
            $xml .= '<lastmod>' . $prod->updated_at->toAtomString() . '</lastmod>';
            $xml .= '<changefreq>daily</changefreq>';
            $xml .= '<priority>0.8</priority>';
            $xml .= '</url>';
        }

        // Static / Legal Pages
        foreach ($pages as $p) {
            $xml .= '<url>';
            $xml .= '<loc>' . route('page.show', $p->slug) . '</loc>';
            $xml .= '<lastmod>' . $p->updated_at->toAtomString() . '</lastmod>';
            $xml .= '<changefreq>monthly</changefreq>';
            $xml .= '<priority>0.5</priority>';
            $xml .= '</url>';
        }

        $xml .= '</urlset>';

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }

    /**
     * Dynamic robots.txt
     */
    public function robots(): Response
    {
        $content = setting('robots_txt_content');

        if (empty($content)) {
            $content = "User-agent: *\nAllow: /\nDisallow: /admin/\nDisallow: /account/\nDisallow: /checkout/\n\nSitemap: " . url('/sitemap.xml');
        }

        return response($content, 200, ['Content-Type' => 'text/plain']);
    }
}


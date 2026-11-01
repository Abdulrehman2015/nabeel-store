<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Benefit;
use App\Models\Category;
use App\Models\Faq;
use App\Models\HomeSection;
use App\Models\HowItWorksStep;
use App\Models\Product;
use App\Services\SeoService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct(protected SeoService $seoService) {}

    public function index()
    {
        // 1. Active Banners
        $banners = Banner::active()->get();

        // 2. Active Categories with product count
        $categories = Category::active()
            ->withCount(['products' => function ($query) {
                $query->where('is_active', true);
            }])
            ->orderBy('sort_order')
            ->get();

        // 3. Dynamic Homepage Sections
        $sections = HomeSection::active()->get()->keyBy('key');

        // 4. Products for sections
        $promoProducts = Product::promo()
            ->with(['category', 'brand'])
            ->latest()
            ->take(8)
            ->get();

        $newArrivalProducts = Product::newArrivals()
            ->with(['category', 'brand'])
            ->latest()
            ->take(8)
            ->get();

        $featuredProducts = Product::featured()
            ->with(['category', 'brand'])
            ->latest()
            ->take(8)
            ->get();

        $bestSellers = Product::active()
            ->with(['category', 'brand'])
            ->orderBy('stock_quantity', 'desc')
            ->take(8)
            ->get();

        // 5. Value content
        $steps = HowItWorksStep::active()->get();
        $benefits = Benefit::active()->get();
        $faqs = Faq::active()->take(6)->get();

        // 6. SEO Metadata for Home
        $seo = $this->seoService->resolveMetadata(null, 'home', [
            'schema_type' => 'Store',
        ]);

        return view('storefront.home', compact(
            'banners',
            'categories',
            'sections',
            'promoProducts',
            'newArrivalProducts',
            'featuredProducts',
            'bestSellers',
            'steps',
            'benefits',
            'faqs',
            'seo'
        ));
    }
}


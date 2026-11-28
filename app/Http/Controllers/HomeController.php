<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Benefit;
use App\Models\Category;
use App\Models\Faq;
use App\Models\HowItWorksStep;
use App\Models\Product;
use App\Services\SeoService;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function __construct(protected SeoService $seoService) {}

    public function index()
    {
        $data = Cache::remember('storefront_home_data', 60, function () {
            $banners = Banner::active()->get();

            $categories = Category::active()
                ->withCount(['products' => function ($query) {
                    $query->where('is_active', true);
                }])
                ->orderBy('sort_order')
                ->get();

            $promoProducts = Product::promo()
                ->with(['category', 'brand'])
                ->latest()
                ->take(8)
                ->get();

            $featuredProducts = Product::featured()
                ->with(['category', 'brand'])
                ->latest()
                ->take(20)
                ->get();

            if ($featuredProducts->count() < 10) {
                $featuredProducts = Product::active()
                    ->with(['category', 'brand'])
                    ->latest()
                    ->take(20)
                    ->get();
            }

            $steps = HowItWorksStep::active()->get();
            $benefits = Benefit::active()->get();
            $faqs = Faq::active()->take(6)->get();

            return [
                'banners' => $banners,
                'categories' => $categories,
                'promoProducts' => $promoProducts,
                'featuredProducts' => $featuredProducts,
                'allProducts' => $featuredProducts,
                'steps' => $steps,
                'benefits' => $benefits,
                'faqs' => $faqs,
            ];
        });

        $seo = $this->seoService->resolveMetadata(null, 'home', [
            'schema_type' => 'Store',
        ]);

        return view('storefront.home', array_merge($data, ['seo' => $seo]));
    }
}

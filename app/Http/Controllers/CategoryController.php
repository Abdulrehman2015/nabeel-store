<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Services\SeoService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(protected SeoService $seoService) {}

    public function show(Request $request, string $slug)
    {
        $category = Category::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $query = Product::where('category_id', $category->id)
            ->where('is_active', true)
            ->with(['brand', 'category']);

        // Filter: Brand
        if ($request->filled('brand')) {
            $brand = Brand::where('slug', $request->query('brand'))->first();
            if ($brand) {
                $query->where('brand_id', $brand->id);
            }
        }

        // Filter: Price Range
        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float) $request->query('min_price'));
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float) $request->query('max_price'));
        }

        // Filter: In Stock
        if ($request->boolean('in_stock')) {
            $query->where('stock_quantity', '>', 0);
        }

        // Sorting
        $sort = $request->query('sort', 'newest');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'popular':
                $query->orderBy('stock_quantity', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'newest':
            default:
                $query->latest('id');
                break;
        }

        $products = $query->paginate(12)->withQueryString();

        // Available brands in this category for the sidebar filter
        $availableBrands = Brand::whereHas('products', function ($q) use ($category) {
            $q->where('category_id', $category->id)->where('is_active', true);
        })->withCount(['products' => function ($q) use ($category) {
            $q->where('category_id', $category->id)->where('is_active', true);
        }])->orderBy('name')->get();

        // Fallback if no brands relation matched directly
        if ($availableBrands->isEmpty()) {
            $brandIds = Product::where('category_id', $category->id)
                ->whereNotNull('brand_id')
                ->distinct()
                ->pluck('brand_id');
            $availableBrands = Brand::whereIn('id', $brandIds)->orderBy('name')->get();
        }

        // Price min/max for category
        $minPrice = Product::where('category_id', $category->id)->where('is_active', true)->min('price') ?? 0;
        $maxPrice = Product::where('category_id', $category->id)->where('is_active', true)->max('price') ?? 500;

        // All active categories with active product counts for sidebar & navigation
        $allCategories = Category::active()
            ->withCount(['products' => function ($q) {
                $q->where('is_active', true);
            }])
            ->orderBy('sort_order')
            ->get();

        // Total active products across catalog
        $totalWholesaleProducts = Product::where('is_active', true)->count();

        // Count of applied active filters
        $activeFiltersCount = 0;
        if ($request->filled('brand')) $activeFiltersCount++;
        if ($request->filled('min_price')) $activeFiltersCount++;
        if ($request->filled('max_price')) $activeFiltersCount++;
        if ($request->boolean('in_stock')) $activeFiltersCount++;

        // SEO & Schema
        $seo = $this->seoService->resolveMetadata($category, 'category');
        $breadcrumbs = [
            'Home' => url('/'),
            'Categories' => route('category.index'),
            $category->name => route('category.show', $category->slug),
        ];
        $breadcrumbsJsonLd = $this->seoService->generateBreadcrumbsJsonLd($breadcrumbs);

        return view('storefront.category', compact(
            'category',
            'allCategories',
            'totalWholesaleProducts',
            'products',
            'availableBrands',
            'minPrice',
            'maxPrice',
            'sort',
            'activeFiltersCount',
            'seo',
            'breadcrumbs',
            'breadcrumbsJsonLd'
        ));
    }

    public function index()
    {
        $categories = Category::active()
            ->withCount(['products' => function ($query) {
                $query->where('is_active', true);
            }])
            ->orderBy('sort_order')
            ->get();

        $seo = $this->seoService->resolveMetadata(null, 'categories', [
            'title' => 'All Categories',
            'description' => 'Browse our complete catalog of food essentials, beverages, snacks, and household supply categories.',
        ]);

        return view('storefront.categories_index', compact('categories', 'seo'));
    }
}


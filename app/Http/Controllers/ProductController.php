<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Services\SeoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(protected SeoService $seoService) {}

    public function show(string $slug)
    {
        $product = Product::where('slug', $slug)
            ->where('is_active', true)
            ->with(['category', 'brand', 'images'])
            ->firstOrFail();

        // Related products in the same category
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->with(['brand', 'category'])
            ->take(4)
            ->get();

        // SEO & Structured Data
        $seo = $this->seoService->resolveMetadata($product);
        $breadcrumbs = [
            'Home' => url('/'),
            $product->category->name => route('category.show', $product->category->slug),
            $product->name => route('product.show', $product->slug),
        ];
        $breadcrumbsJsonLd = $this->seoService->generateBreadcrumbsJsonLd($breadcrumbs);
        $productJsonLd = $this->seoService->generateProductJsonLd($product);

        return view('storefront.product', compact(
            'product',
            'relatedProducts',
            'seo',
            'breadcrumbs',
            'breadcrumbsJsonLd',
            'productJsonLd'
        ));
    }

    /**
     * Live search suggestions JSON endpoint for instant header search.
     */
    public function liveSearch(Request $request): JsonResponse
    {
        $query = trim($request->query('q', ''));

        if (strlen($query) < 2) {
            return response()->json(['results' => []]);
        }

        $products = Product::where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                    ->orWhere('sku', 'LIKE', "%{$query}%")
                    ->orWhere('pack_size', 'LIKE', "%{$query}%");
            })
            ->with('category')
            ->take(6)
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'pack_size' => $p->pack_size,
                    'sku' => $p->sku,
                    'category' => $p->category->name,
                    'price' => format_currency($p->current_price),
                    'original_price' => $p->is_on_sale ? format_currency($p->price) : null,
                    'image' => $p->image_url,
                    'url' => route('product.show', $p->slug),
                    'in_stock' => $p->in_stock,
                ];
            });

        return response()->json(['results' => $products]);
    }

    /**
     * Search results page.
     */
    public function search(Request $request)
    {
        $search = trim($request->query('q', ''));

        $query = Product::where('is_active', true)->with(['category', 'brand']);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('sku', 'LIKE', "%{$search}%")
                    ->orWhere('pack_size', 'LIKE', "%{$search}%")
                    ->orWhere('short_description', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $cat = Category::where('slug', $request->query('category'))->first();
            if ($cat) {
                $query->where('category_id', $cat->id);
            }
        }

        $sort = $request->query('sort', 'newest');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
            default:
                $query->latest('id');
                break;
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::active()->get();

        $seo = $this->seoService->resolveMetadata(null, 'search', [
            'title' => 'Search Results for "' . $search . '"',
            'robots' => 'noindex, follow',
        ]);

        return view('storefront.search', compact('products', 'search', 'categories', 'sort', 'seo'));
    }
}

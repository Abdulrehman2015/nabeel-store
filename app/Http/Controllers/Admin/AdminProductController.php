<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand']);

        if ($request->filled('search')) {
            $s = $request->query('search');
            $query->where(function ($q) use ($s) {
                $q->where('name', 'LIKE', "%{$s}%")
                    ->orWhere('sku', 'LIKE', "%{$s}%")
                    ->orWhere('pack_size', 'LIKE', "%{$s}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->query('category'));
        }

        if ($request->filled('brand')) {
            $query->where('brand_id', $request->query('brand'));
        }

        $hasThreshold = \Illuminate\Support\Facades\Schema::hasColumn('products', 'low_stock_threshold');
        $lowCondition = $hasThreshold ? 'stock_quantity <= COALESCE(low_stock_threshold, 10) AND stock_quantity > 0' : 'stock_quantity <= 10 AND stock_quantity > 0';
        $inStockCondition = $hasThreshold ? 'stock_quantity > COALESCE(low_stock_threshold, 10)' : 'stock_quantity > 10';

        if ($request->filled('stock_status')) {
            if ($request->query('stock_status') === 'low') {
                $query->whereRaw($lowCondition);
            } elseif ($request->query('stock_status') === 'out') {
                $query->where('stock_quantity', '<=', 0);
            } elseif ($request->query('stock_status') === 'in_stock') {
                $query->whereRaw($inStockCondition);
            }
        }

        if ($request->filled('badge')) {
            $badge = $request->query('badge');
            if ($badge === 'promo') {
                $query->where('is_promo', true);
            } elseif ($badge === 'new') {
                $query->where('is_new', true);
            } elseif ($badge === 'featured') {
                $query->where('is_featured', true);
            }
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->query('status') === 'active');
        }

        $products = $query->latest('id')->paginate(15)->withQueryString();
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();

        $stats = [
            'total' => Product::count(),
            'active' => Product::where('is_active', true)->count(),
            'low_stock' => Product::where('is_active', true)->whereRaw($lowCondition)->count(),
            'out_of_stock' => Product::where('stock_quantity', '<=', 0)->count(),
            'promo' => Product::where('is_promo', true)->count(),
        ];

        if ($request->ajax() || $request->has('table_only')) {
            return view('admin.products._table', compact('products'));
        }

        return view('admin.products.index', compact('products', 'categories', 'brands', 'stats'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();

        return view('admin.products.create', compact('categories', 'brands'));
    }

    public function show(Product $product)
    {
        return redirect()->route('admin.products.edit', $product->id);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'name' => 'required|string|max:200',
            'slug' => 'nullable|string|max:200|unique:products,slug',
            'sku' => 'required|string|max:50|unique:products,sku',
            'pack_size' => 'nullable|string|max:100',
            'unit_description' => 'nullable|string|max:150',
            'weight_volume' => 'nullable|string|max:50',
            'price' => 'required|numeric|min:0.01',
            'purchase_price' => 'nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|lt:price',
            'stock_quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'featured_image' => 'nullable|image|max:3072',
            'gallery_images.*' => 'nullable|image|max:3072',
            'is_active' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'is_new' => 'nullable|boolean',
            'is_promo' => 'nullable|boolean',
            // SEO
            'meta_title' => 'nullable|string|max:150',
            'meta_description' => 'nullable|string|max:300',
            'meta_keywords' => 'nullable|string|max:300',
            'canonical_url' => 'nullable|url|max:255',
            'robots' => 'nullable|string|max:50',
            'og_title' => 'nullable|string|max:150',
            'og_description' => 'nullable|string|max:300',
            'focus_keyword' => 'nullable|string|max:100',
            'schema_type' => 'nullable|string|max:50',
        ]);

        $validated['slug'] = !empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['name']);
        $validated['pack_size'] = $validated['pack_size'] ?? '';
        $validated['unit_description'] = $validated['unit_description'] ?? '';
        $validated['weight_volume'] = $validated['weight_volume'] ?? '';
        $validated['short_description'] = $validated['short_description'] ?? '';
        $validated['description'] = $validated['description'] ?? '';
        $validated['meta_title'] = $validated['meta_title'] ?? '';
        $validated['meta_description'] = $validated['meta_description'] ?? '';
        $validated['meta_keywords'] = $validated['meta_keywords'] ?? '';
        $validated['focus_keyword'] = $validated['focus_keyword'] ?? '';
        $validated['low_stock_threshold'] = $request->filled('low_stock_threshold') ? max(0, (int) $request->input('low_stock_threshold')) : 10;
        $validated['purchase_price'] = $request->filled('purchase_price') ? (float) $request->input('purchase_price') : 0.00;
        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_new'] = $request->boolean('is_new');
        $validated['is_promo'] = $request->boolean('is_promo');

        try {
            if ($request->hasFile('featured_image')) {
                $path = $request->file('featured_image')->store('products', 'public');
                $validated['featured_image'] = $path;
                try {
                    $target = public_path('storage/' . $path);
                    @mkdir(dirname($target), 0755, true);
                    @copy(storage_path('app/public/' . $path), $target);
                } catch (\Throwable $e) {}
            }

            $product = Product::create($validated);

            // Upload Gallery Images
            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $idx => $img) {
                    $path = $img->store('products/gallery', 'public');
                    try {
                        $target = public_path('storage/' . $path);
                        @mkdir(dirname($target), 0755, true);
                        @copy(storage_path('app/public/' . $path), $target);
                    } catch (\Throwable $e) {}
                    $product->images()->create([
                        'image_path' => $path,
                        'sort_order' => $idx,
                    ]);
                }
            }

            ActivityLog::log('product.created', $product, ['name' => $product->name, 'sku' => $product->sku]);

            return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Product creation failed: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Could not create product: ' . $e->getMessage());
        }
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();
        $product->load('images');

        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'name' => 'required|string|max:200',
            'slug' => 'nullable|string|max:200|unique:products,slug,' . $product->id,
            'sku' => 'required|string|max:50|unique:products,sku,' . $product->id,
            'pack_size' => 'nullable|string|max:100',
            'unit_description' => 'nullable|string|max:150',
            'weight_volume' => 'nullable|string|max:50',
            'price' => 'required|numeric|min:0.01',
            'purchase_price' => 'nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|lt:price',
            'stock_quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'featured_image' => 'nullable|image|max:3072',
            'gallery_images.*' => 'nullable|image|max:3072',
            'is_active' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'is_new' => 'nullable|boolean',
            'is_promo' => 'nullable|boolean',
            // SEO
            'meta_title' => 'nullable|string|max:150',
            'meta_description' => 'nullable|string|max:300',
            'meta_keywords' => 'nullable|string|max:300',
            'canonical_url' => 'nullable|url|max:255',
            'robots' => 'nullable|string|max:50',
            'og_title' => 'nullable|string|max:150',
            'og_description' => 'nullable|string|max:300',
            'focus_keyword' => 'nullable|string|max:100',
            'schema_type' => 'nullable|string|max:50',
        ]);

        $validated['slug'] = !empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['name']);
        $validated['pack_size'] = $validated['pack_size'] ?? '';
        $validated['unit_description'] = $validated['unit_description'] ?? '';
        $validated['weight_volume'] = $validated['weight_volume'] ?? '';
        $validated['short_description'] = $validated['short_description'] ?? '';
        $validated['description'] = $validated['description'] ?? '';
        $validated['meta_title'] = $validated['meta_title'] ?? '';
        $validated['meta_description'] = $validated['meta_description'] ?? '';
        $validated['meta_keywords'] = $validated['meta_keywords'] ?? '';
        $validated['focus_keyword'] = $validated['focus_keyword'] ?? '';
        $validated['low_stock_threshold'] = $request->filled('low_stock_threshold') ? max(0, (int) $request->input('low_stock_threshold')) : 10;
        $validated['purchase_price'] = $request->filled('purchase_price') ? (float) $request->input('purchase_price') : 0.00;
        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_new'] = $request->boolean('is_new');
        $validated['is_promo'] = $request->boolean('is_promo');

        try {
            if ($request->hasFile('featured_image')) {
                $path = $request->file('featured_image')->store('products', 'public');
                $validated['featured_image'] = $path;
                try {
                    $target = public_path('storage/' . $path);
                    @mkdir(dirname($target), 0755, true);
                    @copy(storage_path('app/public/' . $path), $target);
                } catch (\Throwable $e) {}
            }

            $product->update($validated);

            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $idx => $img) {
                    $path = $img->store('products/gallery', 'public');
                    try {
                        $target = public_path('storage/' . $path);
                        @mkdir(dirname($target), 0755, true);
                        @copy(storage_path('app/public/' . $path), $target);
                    } catch (\Throwable $e) {}
                    $product->images()->create([
                        'image_path' => $path,
                        'sort_order' => $idx + $product->images()->count(),
                    ]);
                }
            }

            ActivityLog::log('product.updated', $product, ['name' => $product->name, 'sku' => $product->sku]);

            return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Product update failed: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Could not update product: ' . $e->getMessage());
        }
    }

    public function destroy(Product $product)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action: Cashiers and staff do not have permission to delete products.');
        }

        ActivityLog::log('product.deleted', $product, ['name' => $product->name, 'sku' => $product->sku]);
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted.');
    }

    /**
     * Live search suggestions for products catalog.
     */
    public function suggestions(Request $request): JsonResponse
    {
        $q = trim((string) $request->input('q', ''));
        if ($q === '') {
            return response()->json(['success' => true, 'products' => []]);
        }

        try {
            $products = Product::with(['category', 'brand'])
                ->where(function ($query) use ($q) {
                    $query->where('name', 'LIKE', "%{$q}%")
                        ->orWhere('sku', 'LIKE', "%{$q}%")
                        ->orWhere('pack_size', 'LIKE', "%{$q}%");
                })
                ->orderBy('name', 'asc')
                ->limit(10)
                ->get()
                ->map(function ($p) {
                    return [
                        'id' => $p->id,
                        'name' => $p->name,
                        'sku' => $p->sku ?: '-',
                        'category' => $p->category?->name ?? 'Uncategorized',
                        'brand' => $p->brand?->name ?? '',
                        'price' => (float) ($p->sale_price ?? $p->price),
                        'stock_quantity' => (int) $p->stock_quantity,
                        'is_active' => (bool) $p->is_active,
                        'image_url' => $p->featured_image
                            ? (str_starts_with($p->featured_image, 'http') ? $p->featured_image : asset('storage/' . $p->featured_image))
                            : asset('images/default-product.png'),
                        'edit_url' => route('admin.products.edit', $p->id),
                    ];
                });

            return response()->json([
                'success' => true,
                'products' => $products,
            ]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Product suggestions error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'products' => [],
            ], 200);
        }
    }
}


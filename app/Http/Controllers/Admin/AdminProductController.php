<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
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

        if ($request->filled('stock_status')) {
            if ($request->query('stock_status') === 'low') {
                $query->where('stock_quantity', '<=', 20);
            } elseif ($request->query('stock_status') === 'out') {
                $query->where('stock_quantity', '<=', 0);
            }
        }

        $products = $query->latest('id')->paginate(15)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();

        return view('admin.products.create', compact('categories', 'brands'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'name' => 'required|string|max:200',
            'slug' => 'nullable|string|max:200|unique:products,slug',
            'sku' => 'required|string|max:50|unique:products,sku',
            'pack_size' => 'required|string|max:100',
            'unit_description' => 'nullable|string|max:150',
            'weight_volume' => 'nullable|string|max:50',
            'price' => 'required|numeric|min:0.01',
            'sale_price' => 'nullable|numeric|lt:price',
            'stock_quantity' => 'required|integer|min:0',
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
        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_new'] = $request->boolean('is_new');
        $validated['is_promo'] = $request->boolean('is_promo');

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('products', 'public');
        }

        $product = Product::create($validated);

        // Upload Gallery Images
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $idx => $img) {
                $path = $img->store('products/gallery', 'public');
                $product->images()->create([
                    'image_path' => $path,
                    'sort_order' => $idx,
                ]);
            }
        }

        ActivityLog::log('product.created', $product, ['name' => $product->name, 'sku' => $product->sku]);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
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
            'pack_size' => 'required|string|max:100',
            'unit_description' => 'nullable|string|max:150',
            'weight_volume' => 'nullable|string|max:50',
            'price' => 'required|numeric|min:0.01',
            'sale_price' => 'nullable|numeric|lt:price',
            'stock_quantity' => 'required|integer|min:0',
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
        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_new'] = $request->boolean('is_new');
        $validated['is_promo'] = $request->boolean('is_promo');

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('products', 'public');
        }

        $product->update($validated);

        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $idx => $img) {
                $path = $img->store('products/gallery', 'public');
                $product->images()->create([
                    'image_path' => $path,
                    'sort_order' => $idx + $product->images()->count(),
                ]);
            }
        }

        ActivityLog::log('product.updated', $product, ['name' => $product->name, 'sku' => $product->sku]);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        ActivityLog::log('product.deleted', $product, ['name' => $product->name, 'sku' => $product->sku]);
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted.');
    }
}


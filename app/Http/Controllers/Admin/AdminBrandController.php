<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminBrandController extends Controller
{
    public function index()
    {
        $brands = Brand::withCount('products')->latest()->paginate(15);
        return view('admin.brands.index', compact('brands'));
    }

    public function create()
    {
        return view('admin.brands.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'nullable|string|max:100|unique:brands,slug',
            'logo' => 'nullable|image|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['slug'] = !empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['name']);
        $validated['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('brands', 'public');
        }

        $brand = Brand::create($validated);
        ActivityLog::log('brand.created', $brand, ['name' => $brand->name]);

        return redirect()->route('admin.brands.index')->with('success', 'Brand created successfully.');
    }

    public function edit(Brand $brand)
    {
        return view('admin.brands.edit', compact('brand'));
    }

    public function update(Request $request, Brand $brand)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'nullable|string|max:100|unique:brands,slug,' . $brand->id,
            'logo' => 'nullable|image|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['slug'] = !empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['name']);
        $validated['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('brands', 'public');
        }

        $brand->update($validated);
        ActivityLog::log('brand.updated', $brand, ['name' => $brand->name]);

        return redirect()->route('admin.brands.index')->with('success', 'Brand updated successfully.');
    }

    public function destroy(Brand $brand)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action: Cashiers and staff cannot delete brands.');
        }

        ActivityLog::log('brand.deleted', $brand, ['name' => $brand->name]);
        $brand->delete();

        return redirect()->route('admin.brands.index')->with('success', 'Brand deleted.');
    }

    public function deleteAll(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action: Cashiers and staff cannot delete brands.');
        }

        $count = Brand::count();

        Brand::query()->each(function ($brand) {
            if ($brand->logo && !str_starts_with($brand->logo, 'http')) {
                @Storage::disk('public')->delete($brand->logo);
            }
            $brand->delete();
        });

        DB::table('brands')->delete();

        ActivityLog::log('brands.deleted_all', null, ['count' => $count]);

        return redirect()->route('admin.brands.index')
            ->with('success', "All {$count} brands have been permanently deleted.");
    }
}


<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use App\Services\SeoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(
        protected CartService $cartService,
        protected SeoService $seoService
    ) {}

    public function index()
    {
        $summary = $this->cartService->getSummary();
        $seo = $this->seoService->resolveMetadata(null, 'cart', [
            'title' => 'Shopping Cart',
            'robots' => 'noindex, nofollow',
        ]);

        return view('storefront.cart', compact('summary', 'seo'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $summary = $this->cartService->add(
            (int) $request->input('product_id'),
            (int) ($request->input('quantity', 1))
        );

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Product added to cart!',
                'summary' => $summary,
            ]);
        }

        return redirect()->back()->with('success', 'Item added to your cart.');
    }

    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',
            'quantity' => 'required|integer|min:0',
        ]);

        $summary = $this->cartService->update(
            (int) $request->input('product_id'),
            (int) $request->input('quantity')
        );

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Cart updated.',
                'summary' => $summary,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Cart updated successfully.');
    }

    public function remove(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',
        ]);

        $summary = $this->cartService->remove((int) $request->input('product_id'));

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart.',
                'summary' => $summary,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Item removed from cart.');
    }

    public function clear()
    {
        $this->cartService->clear();
        return redirect()->route('cart.index')->with('success', 'Cart emptied.');
    }

    public function summary(): JsonResponse
    {
        return response()->json($this->cartService->getSummary());
    }
}


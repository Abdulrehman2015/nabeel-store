<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Product;
use App\Models\ProductExpense;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminProductExpenseController extends Controller
{
    /**
     * Display the Store Internal Consumption & Expense Dashboard.
     */
    public function index(Request $request): View
    {
        ProductExpense::ensureTableExists();

        $query = ProductExpense::with('product')->latest('expense_date')->latest('id');

        // Month / Period Filter
        $period = $request->query('period', 'this_month');
        $customMonth = $request->query('month'); // e.g. '2026-11'

        if ($customMonth) {
            try {
                $mDate = Carbon::createFromFormat('Y-m', $customMonth);
                $query->whereYear('expense_date', $mDate->year)
                      ->whereMonth('expense_date', $mDate->month);
            } catch (\Throwable $e) {}
        } else {
            if ($period === 'today') {
                $query->whereDate('expense_date', Carbon::today());
            } elseif ($period === 'yesterday') {
                $query->whereDate('expense_date', Carbon::yesterday());
            } elseif ($period === 'this_month') {
                $query->whereYear('expense_date', Carbon::now()->year)
                      ->whereMonth('expense_date', Carbon::now()->month);
            } elseif ($period === 'last_month') {
                $lastMonth = Carbon::now()->subMonth();
                $query->whereYear('expense_date', $lastMonth->year)
                      ->whereMonth('expense_date', $lastMonth->month);
            }
        }

        // Category Filter
        if ($request->filled('category') && $request->query('category') !== 'all') {
            $query->where('category', $request->query('category'));
        }

        // Staff Name Filter
        if ($request->filled('staff') && $request->query('staff') !== 'all') {
            $query->where('staff_name', $request->query('staff'));
        }

        // Search Filter (Product name, SKU, or Reason)
        if ($request->filled('search')) {
            $search = trim($request->query('search'));
            $query->where(function ($q) use ($search) {
                $q->where('product_name', 'LIKE', "%{$search}%")
                  ->orWhere('product_sku', 'LIKE', "%{$search}%")
                  ->orWhere('staff_name', 'LIKE', "%{$search}%")
                  ->orWhere('reason', 'LIKE', "%{$search}%");
            });
        }

        $expenses = $query->paginate(25)->withQueryString();

        // Calculate Monthly & Daily KPIs
        $thisMonth = Carbon::now();
        $monthlyTotalCost = (float) ProductExpense::whereYear('expense_date', $thisMonth->year)
            ->whereMonth('expense_date', $thisMonth->month)
            ->sum('total_cost');

        $monthlyTotalQty = (int) ProductExpense::whereYear('expense_date', $thisMonth->year)
            ->whereMonth('expense_date', $thisMonth->month)
            ->sum('quantity');

        $todayTotalCost = (float) ProductExpense::whereDate('expense_date', Carbon::today())
            ->sum('total_cost');

        $todayTotalQty = (int) ProductExpense::whereDate('expense_date', Carbon::today())
            ->sum('quantity');

        // Top Consumed Products this month
        $topConsumed = ProductExpense::selectRaw('product_name, SUM(quantity) as total_qty, SUM(total_cost) as total_spent')
            ->whereYear('expense_date', $thisMonth->year)
            ->whereMonth('expense_date', $thisMonth->month)
            ->groupBy('product_name')
            ->orderByDesc('total_spent')
            ->take(5)
            ->get();

        // Distinct staff list for filter
        $staffList = ProductExpense::distinct()->whereNotNull('staff_name')->pluck('staff_name');

        // Products for Quick-Add Modal (with current stock & cost)
        $products = Product::where('is_active', true)
            ->orderBy('name', 'asc')
            ->get(['id', 'name', 'sku', 'price', 'purchase_price', 'stock_quantity']);

        return view('admin.expenses.index', compact(
            'expenses',
            'period',
            'customMonth',
            'monthlyTotalCost',
            'monthlyTotalQty',
            'todayTotalCost',
            'todayTotalQty',
            'topConsumed',
            'staffList',
            'products'
        ));
    }

    /**
     * Record a new product consumption expense and deduct inventory stock.
     */
    public function store(Request $request)
    {
        ProductExpense::ensureTableExists();

        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1|max:1000',
            'category' => 'required|string|in:staff_consumption,store_use,damaged_expired,other',
            'staff_name' => 'nullable|string|max:100',
            'unit_cost' => 'nullable|numeric|min:0',
            'reason' => 'nullable|string|max:255',
            'expense_date' => 'nullable|date',
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $oldStock = (int) $product->stock_quantity;

        $expense = ProductExpense::recordConsumption($validated);

        $product->refresh();
        $newStock = (int) $product->stock_quantity;

        ActivityLog::log('product_expense.created', $product, [
            'expense_id' => $expense->id,
            'quantity' => $expense->quantity,
            'unit_cost' => $expense->unit_cost,
            'total_cost' => $expense->total_cost,
            'category' => $expense->category,
            'staff_name' => $expense->staff_name,
            'stock_before' => $oldStock,
            'stock_after' => $newStock,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Recorded {$expense->quantity}x {$expense->product_name} for internal use. Remaining stock: {$newStock}.",
                'expense' => $expense,
                'new_stock' => $newStock,
            ]);
        }

        return redirect()->back()->with(
            'success',
            "Recorded {$expense->quantity}x '{$expense->product_name}' (RM " . number_format($expense->total_cost, 2) . "). Inventory deducted from {$oldStock} to {$newStock} units."
        );
    }

    /**
     * Delete an expense record and restore product inventory.
     */
    public function destroy(ProductExpense $expense): RedirectResponse
    {
        $qty = $expense->quantity;
        $name = $expense->product_name;

        // Restore stock to product
        if ($expense->product_id) {
            $product = Product::find($expense->product_id);
            if ($product) {
                $product->increment('stock_quantity', $qty);
            }
        }

        ActivityLog::log('product_expense.deleted', null, [
            'expense_id' => $expense->id,
            'product_name' => $name,
            'restored_quantity' => $qty,
        ]);

        $expense->delete();

        return redirect()->back()->with(
            'success',
            "Expense record for '{$name}' deleted. Restored {$qty} unit(s) back to inventory stock."
        );
    }

    /**
     * Export Expenses CSV Report.
     */
    public function export(Request $request): StreamedResponse
    {
        $month = $request->query('month', date('Y-m'));
        $fileName = "nabeel-store-internal-expenses-{$month}.csv";

        return response()->streamDownload(function () use ($month) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Date', 'Product Name', 'SKU', 'Category', 'Quantity', 'Unit Cost (RM)', 'Total Expense (RM)', 'Used By / Staff', 'Reason / Note']);

            $query = ProductExpense::latest('expense_date');
            if ($month !== 'all') {
                try {
                    $mDate = Carbon::createFromFormat('Y-m', $month);
                    $query->whereYear('expense_date', $mDate->year)
                          ->whereMonth('expense_date', $mDate->month);
                } catch (\Throwable $e) {}
            }

            foreach ($query->cursor() as $row) {
                fputcsv($handle, [
                    $row->id,
                    $row->expense_date?->format('Y-m-d') ?? '',
                    $row->product_name,
                    $row->product_sku ?? '',
                    ucwords(str_replace('_', ' ', $row->category)),
                    $row->quantity,
                    number_format((float) $row->unit_cost, 2),
                    number_format((float) $row->total_cost, 2),
                    $row->staff_name ?? '',
                    $row->reason ?? '',
                ]);
            }

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ]);
    }
}

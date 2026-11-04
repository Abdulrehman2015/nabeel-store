<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ProductsExport;
use App\Http\Controllers\Controller;
use App\Imports\ProductsImport;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AdminProductImportExportController extends Controller
{
    public function export()
    {
        ActivityLog::log('products.exported');
        $fileName = 'products-' . date('Y-m-d') . '.csv';
        return Excel::download(new ProductsExport, $fileName);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx,xls|max:10240',
        ]);

        try {
            Excel::import(new ProductsImport, $request->file('file'));
            ActivityLog::log('products.imported');
            return redirect()->back()->with('success', 'Products imported and synchronized successfully.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }
}


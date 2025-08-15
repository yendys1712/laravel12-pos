<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SalesSummaryExport;
use Barryvdh\DomPDF\Facade\Pdf;



class DashboardController extends Controller
{
    //
        public function index()
        {
            $totalSales = Sale::sum(DB::raw('quantity * total_price'));
            $totalItemsSold = Sale::sum('quantity');

            $topItem = Sale::select('id', DB::raw('SUM(quantity) as total'))
                ->groupBy('id')
                ->orderByDesc('total')
                ->with('items')
                ->first();

            $summary = [
                'totalSales'     => $totalSales ?? 0,
                'totalItemsSold' => $totalItemsSold ?? 0,
                'topItem'        => $topItem?->item?->name ?? 'N/A',
            ];
           

             // 🎯 Item Sales Summary: grouped by item
            $itemSales = Sale::select('id', DB::raw('SUM(quantity) as qty_sold'), DB::raw('SUM(total_price) as total_earned'))
                ->groupBy('id')
                ->with('items')
                ->get();
          
            return view('admin.dashboard', compact('summary', 'itemSales'));

            // $totalItemsSold = \App\Models\Sale::sum('quantity');
            // $totalSales = \App\Models\Sale::sum(DB::raw('quantity * total_price'));
            // $topItem = \App\Models\Sale::select('id', DB::raw('SUM(quantity) as total'))
            //     ->groupBy('id')
            //     ->orderByDesc('total')
            //     ->with('item')
            //     ->first();

            // $summary = [
            //     'totalItemsSold' => $totalItemsSold ?? 0,
            //     'totalSales'     => $totalSales ?? 0,
            //     'topItem'        => $topItem?->item?->name ?? 'N/A',
            // ];

            // return view('admin.dashboard', compact('summary'));
        }

        public function exportExcel()
        {
            return Excel::download(new SalesSummaryExport, 'sales_summary.xlsx');
        }

        public function exportPDF()
        {
        //   $salesSummary = Sale::select(
        //         'id',
        //         DB::raw('SUM(quantity) as total_qty'),
        //         DB::raw('SUM(total_price) as total_sales'),
        //         DB::raw('MAX(created_at) as last_sold_at')
        //     )
        //     ->groupBy('id')
        //     ->with('items')
        //     ->get()
        //     ->filter(fn ($sale) => $sale->items); // only existing items

        //     $pdf = Pdf::loadView('pdf.sales-summary', compact('salesSummary'));

        //     return $pdf->download('item_sales_summary.pdf');
         $items = Item::all(); // or your summary/sales data

        $pdf = Pdf::loadView('pdf.sales-summary', compact('items'));
        return $pdf->download('item_sales_summary.pdf');

    }
}

<?php

namespace App\Http\Controllers;
use App\Models\Item;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AdminController extends Controller
{
    //

    public function index()
    {
         $summary = [
                'totalItemsSold' => DB::table('item_sale')->sum('quantity'),
                
                'totalSales' => DB::table('item_sale')
                    ->select(DB::raw('SUM((price - discount) * quantity) as total'))
                    ->value('total'),

                'topItem' => DB::table('item_sale')
                    ->select('id', DB::raw('SUM(quantity) as total_qty'))
                    ->groupBy('id')
                    ->orderByDesc('total_qty')
                    ->first(),
            ];
            $topItemName = 'N/A';
            if ($summary['topItem']) {
                $topItem = \App\Models\Item::find($summary['topItem']->id);
                $topItemName = $topItem?->name ?? 'N/A';
            }
            $salesSummary = DB::table('item_sale')->select(
                                'id',
                                DB::raw('SUM(quantity) as total_qty'),
                                DB::raw('SUM((price - discount) * quantity) as total_sales'),
                                DB::raw('MAX(created_at) as last_sold_at')
                )->groupBy('id')
                ->get()
                ->map(function ($row) {
                    $item = \App\Models\Item::find($row->id);
                    return [
                        'name' => $item?->name ?? 'Deleted Item',
                        'quantity_sold' => $row->total_qty,
                        'total_sales' => $row->total_sales,
                        'last_sold_at' => $row->last_sold_at,
                    ];
                });


        return view('admin.dashboard', compact('summary', 'topItemName', 'salesSummary'));
    }
    public function dashboard()
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

    }

     public function getData()
    {
        $saleSum = DB::table('item_sale')->select(
                                'id',
                                DB::raw('SUM(quantity) as total_qty'),
                                DB::raw('SUM((price - discount) * quantity) as total_sales'),
                                DB::raw('MAX(created_at) as last_sold_at')
                )->groupBy('id')
                ->get()
                ->map(function ($row) {
                    $item = \App\Models\Item::find($row->id);
                    return [
                        'name' => $item?->name ?? 'Deleted Item',
                        'quantity_sold' => $row->total_qty,
                        'total_sales' => $row->total_sales,
                        'last_sold_at' => $row->last_sold_at,
                        'cashier' => $row->cashier ?? 'N/A',
                    ];
                });
        // dd( $saleSum);
        return DataTables::of($saleSum)
            //->rawColumns(['image']) // allow raw HTML rendering
            ->make(true); // this is for AJAX
    }
    public function perItemSalesSummary()
    {
        $summary = DB::table('sales')
            ->join('items', 'sales.id', '=', 'items.id')
            ->select(
                'items.id',
                'items.name',
                'items.barcode',
                'items.price',
                DB::raw('SUM(sales.quantity) as total_quantity'),
                DB::raw('SUM(sales.total_price) as total_revenue'),
                DB::raw('MAX(sales.created_at) as last_sold')
            )
            ->groupBy('items.id', 'items.name', 'items.barcode', 'items.price')
            ->orderByDesc('total_quantity')
            ->get();

        return view('admin.per-item-sales-summary', compact('summary'));
    }
    public function salesChartData()
    {
        $sales = Sale::select('id', DB::raw('SUM(quantity) as total_sold'))
            ->groupBy('id')
            ->with('items')
            ->get();

        $labels = $sales->map(function ($sale) {
            return $sale->items ? $sale->items->name : 'Unknown';
        });

        $data = $sales->pluck('total_sold');

        return response()->json([
            'labels' => $labels,
            'data' => $data
        ]);
    }

    public function salesChart()
    {
        $salesData = Sale::select('id', DB::raw('SUM(quantity) as total_sold'))
            ->groupBy('id')
            ->with('items')
            ->orderByDesc('total_sold')
            ->get();

        $labels = $salesData->pluck('item.name');
        $data = $salesData->pluck('total_sold');

        return response()->json([
            'labels' => $labels,
            'data' => $data
        ]);
    }
    public function topItemsChart()
    {
        $topItems = DB::table('item_sale')
            ->join('items', 'item_sale.item_id', '=', 'items.id')
            ->select(
                'items.name',
                DB::raw('SUM(item_sale.quantity) as total_quantity'),
                DB::raw('SUM(item_sale.quantity * item_sale.price) as total_sales')
            )
            ->groupBy('item_sale.item_id', 'items.name')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get();

        return response()->json($topItems);
    }

    public function listCashiers()
    {
        return User::select('id', 'name')->get(); // Assuming cashiers are users
    }

     public function topItemsCharts(Request $request)
    {
        // Optional: filters (date range & cashier)
        $from = $request->input('from');
        $to = $request->input('to');
        $cashier = $request->input('cashier');

        $query = DB::table('item_sale')
            ->join('items', 'item_sale.item_id', '=', 'items.id')
            ->join('sales', 'item_sale.sale_id', '=', 'sales.id')
            ->select('items.name', DB::raw('SUM(item_sale.quantity) as total_sold'))
            ->groupBy('items.name')
            ->orderByDesc('total_sold');

        if ($from && $to) {
            $query->whereBetween('sales.created_at', [$from . ' 00:00:00', $to . ' 23:59:59']);
        }
        if ($cashier) {
            $query->where('sales.cashier_id', $cashier);
        }

        $topItems = $query->limit(10)->get();

        return response()->json($topItems);
    }

   public function topItemsByCashier(Request $request)
    {
        $userId = $request->query('user_id');
        $from = $request->query('from');
        $to   = $request->query('to');

        $query = DB::table('item_sale')
            ->join('sales', 'item_sale.sale_id', '=', 'sales.id')
            ->join('items', 'item_sale.item_id', '=', 'items.id')
            ->select(
                'items.name',
                DB::raw('SUM(item_sale.quantity) as total_quantity'),
                DB::raw('SUM(item_sale.quantity * item_sale.price) as total_sales')
            )
            ->when($userId, fn($q) => $q->where('sales.user_id', $userId))
            ->when($from, fn($q) => $q->whereDate('sales.created_at', '>=', $from))
            ->when($to, fn($q) => $q->whereDate('sales.created_at', '<=', $to))
            ->groupBy('items.name')
            ->orderByDesc('total_quantity')
            ->limit(5);

        return response()->json($query->get());
    }
}

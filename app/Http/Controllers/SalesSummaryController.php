<?php
namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SalesSummaryController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->from ?? now()->startOfMonth();
        $to = $request->to ?? now();
        $cashierId = $request->cashier_id;

        $salesQuery = Sale::with(['items', 'cashier'])
            ->whereBetween('created_at', [$from, $to]);

        if ($cashierId) {
            $salesQuery->where('user_id', $cashierId);
        }

        $sales = $salesQuery->get();

        // Group sales by item
        $summary = $sales->flatMap->items->groupBy('id')->map(function ($items) {
            $totalQty = $items->sum('pivot.quantity');
            $totalSales = $items->sum(function ($item) {
                return ($item->pivot->price - ($item->pivot->discount ?? 0)) * $item->pivot->quantity;
            });
            return [
                'name' => $items->first()->name,
                'total_qty' => $totalQty,
                'total_sales' => $totalSales
            ];
        });

        $totalSalesAmount = $summary->sum('total_sales');
        $cashiers = User::all();

        // Prepare data for chart
        $chartData = [
            'labels' => $summary->pluck('name'),
            'quantities' => $summary->pluck('total_qty'),
            'sales' => $summary->pluck('total_sales')
        ];

        return view('sales.summary', compact('summary', 'totalSalesAmount', 'from', 'to', 'cashiers', 'cashierId', 'chartData'));
    }

   public function topItemsCharts(Request $request)
    {
        $topItems = DB::table('item_sale')
            ->join('items', 'item_sale.item_id', '=', 'items.id')
            ->join('sales', 'item_sale.sale_id', '=', 'sales.id')
            ->select('items.name', DB::raw('SUM(item_sale.quantity) as total_sold'))
            ->when($request->cashier_id, fn($q) => $q->where('sales.user_id', $request->cashier_id))
            ->when($request->from && $request->to, fn($q) => $q->whereBetween('sales.created_at', [$request->from, $request->to]))
            ->groupBy('items.name')
            ->orderByDesc('total_sold')
            ->limit(10)
            ->get();
      
        return response()->json($topItems);
    }

     public function filter_sales(Request $request)
    {

        //$sales = Sale::with('items')->latest()->get();
         $query = Sale::with(['items', 'user'])->latest();

         if (!$request->has('from') || !$request->has('to')) {
            $today = now()->format('Y-m-d');
            $request->merge([
                'from' => $today,
                'to' => $today,
            ]);
        }
        // 👤 Filter by user (cashier)
        if ($request->has('user_id') && $request->user_id != '') {
            $query->where('user_id', $request->user_id);
        }

        // 📅 Filter by date range
        if ($request->has('from') && $request->has('to')) {
            $query->whereBetween('created_at', [$request->from . ' 00:00:00', $request->to .' 23:59:59']);
        }
        $sales = $query->get();
        $data = [];

        foreach ($sales as $sale) {
            foreach ($sale->items as $item) {
                $data[] = [
                    'sale_id'     => $sale->id,
                    'item_name'   => $item->name,
                    'quantity'    => $item->pivot->quantity,
                    'total'       => ($item->pivot->price - $item->pivot->discount) * $item->pivot->quantity,
                    
                ];
            }
        }
        return DataTables::of($data)->make(true); // this is for AJAX
    }

    public function getCashiers()
    {
        return User::where('role', 'cashier')->select('id', 'name')->get();
    }

       public function salesChart()
    {
        $sales = DB::table('sales')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_price) as total_sales'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $dates = $sales->pluck('date');
        $totals = $sales->pluck('total_sales');

        return view('sales.chart', compact('dates', 'totals'));
    }

    public function getSalesData()
    {
        $sales = DB::table('sales')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_price) as total_sales'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json([
            'dates' => $sales->pluck('date'),
            'totals' => $sales->pluck('total_sales')
        ]);

    }   


}

<?php

namespace App\Http\Controllers;
use App\Models\Item;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;
use App\Exports\SalesExport;
use Maatwebsite\Excel\Facades\Excel;
use PDF;


class SaleController extends Controller
{
    //
    public function index()
    {
        $sales = Sale::with('items')->latest()->get();
        $items = \App\Models\Item::all(); // 👈 this line is missing in your case
       
        $resultsCount = $sales->count();
        return view('sales.index', compact('sales', 'items','resultsCount'));
    }

    public function getData(Request $request)
    {

       
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
     
        $cashiers = \App\Models\User::where('role', 'cashier')->get();
        $data = [];

        foreach ($sales as $sale) {
            foreach ($sale->items as $item) {
                $data[] = [
                    'sale_id'     => $sale->id,
                    'item_name'   => $item->name,
                    'quantity'    => $item->pivot->quantity,
                    'total'       => ($item->pivot->price - $item->pivot->discount) * $item->pivot->quantity,
                    'sold_at'     => $sale->created_at->format('Y-m-d H:i:s'),
                    'cashier'     => $sale->cashier,
                  
                ];
            }
        }
        return DataTables::of($data)
            ->addColumn('action', function ($data) {
                return '
                    <td class="p-2 border">
                    <div class="d-flex gap-2">
                        <button  onclick="editItem('.$data['sale_id'].')" class="btn btn-sm btn-warning" data-bs-toggle="modal" >✏️ Edit</button>
                        <button onclick="deleteItem('.$data['sale_id'] . ')"  class="btn btn-sm btn-danger"> 🗑️ Delete</button>
                    
                    </td>
                    ';
            })
            ->rawColumns(['action'])
            //->rawColumns(['image']) // allow raw HTML rendering
            ->make(true); // this is for AJAX
    }

    // public function export(Request $request)
    // {
    //     $type = $request->type;

    //     if ($type === 'excel') {
    //         return Excel::download(new SalesExport($request), 'sales_summary.xlsx');
    //     } elseif ($type === 'pdf') {
    //         $data = (new SalesExport($request))->collection(); // you can reuse same export
    //         $pdf = PDF::loadView('pdf.sales-summary', ['sales' => $data]);
    //         return $pdf->download('sales_summary.pdf');
    //     }

    //     return back();
    // }

    public function store(Request $request)
    {
        $request->validate([
        'id' => 'required|exists:items,id',
        'quantity' => 'required|integer|min:1',
        ]);

        $item = \App\Models\Item::findOrFail($request->id);

        // 🔒 Check if quantity is enough
        if ($request->quantity > $item->quantity) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Not enough stock. Available quantity: ' . $item->quantity);
        }

        // ✅ Create sale
        Sale::create([
            'id' => $item->id,
            'quantity' => $request->quantity,
            'total_price' => $item->price * $request->quantity,
        ]);
        

        // ✅ Reduce stock
        $item->decrement('quantity', $request->quantity);

        return redirect()->back()->with('success', 'Sale recorded successfully!');
    }

    public function checkout(Request $request)
    {
        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return back()->with('error', 'Cart is empty.');
        }

        $totalPrice = 0;
        $validatedItems = [];

        foreach ($cart as $itemId => $data) {
            $item = Item::find($itemId);

            if (!$item) {
                return back()->with('error', "Item ID {$itemId} not found.");
            }

            if ($data['quantity'] > $item->quantity) {
                return back()->with('error', "Not enough stock for {$item->name}. Available: {$item->quantity}");
            }

            $validatedItems[] = [
                'items' => $item,
                'quantity' => $data['quantity'],
                'price' => $data['price'],
                'subtotal' => $data['quantity'] * $data['price'],
            ];

            $totalPrice += $data['quantity'] * $data['price'];
        }

        // Create sale
        $sale = Sale::create([
            'total_price' => $totalPrice,
        ]);

        // Attach items to sale and deduct stock
        foreach ($validatedItems as $data) {
            $sale->items()->attach($data['items']->id, [
                'quantity' => $data['quantity'],
                'price' => $data['price'],
            ]);

            $data['items']->decrement('quantity', $data['quantity']);
        }

        // Clear cart
        Session::forget('cart');

        // Redirect to receipt
        return redirect()->route('sales.receipt', $sale->id);
    }

    public function receipt($id)
    {
        // $sale->load('items'); // if Sale has related items
        // return view('sales.receipt', compact('sale'));
        if (empty($id)) {
            return back()->with('error_empty_cart', 'Cart is empty.');
        }
        $sale = Sale::with('items')->findOrFail($id);
        session()->forget('cart');
        return view('cart.receipt', compact('sale'));
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
<?php

namespace App\Http\Controllers;
use App\Models\Item;
use Illuminate\Http\Request;
use App\Models\Sale;
use Illuminate\Support\Facades\Session; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AddToCartController extends Controller
{
    //
    public function view()
    {   
        $cart = session('cart', []);
        $totalItems = collect($cart)->sum('quantity');
        return view('cart', compact('cart','totalItems'));
        //return view('cart'); // assumes you have resources/views/cart.blade.php
    }
    public function addToCart(Request $request)
    {
        $item = \App\Models\Item::findOrFail($request->id);
        $cart = session()->get('cart', []);

        if (isset($cart[$item->id])) {
            $cart[$item->id]['quantity'] += $request->quantity;
        } else {
            $cart[$item->id] = [
                'name' => $item->name,
                'price' => $item->price,
                'quantity' => $request->quantity,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Item added to cart');
    }
    public function ajaxAdd(Request $request)
    {
        $item = Item::findOrFail($request->id);
        $cart = session()->get('cart', []);
        if($request['quantity'] > $item->quantity) {
                return response()->json(['error' => "Not enough stock for {$item->name}. Only {$item->quantity} available."], 404);
        }
        if (isset($cart[$item->id])) {
            $cart[$item->id]['quantity'] += 1;
        } else {
            $cart[$item->id] = [
                'id'       => $item->id,
                'name'     => $item->name,
                'price'    => $item->price,
                'quantity' => 1,
            ];
        }

        session(['cart' => $cart]);

        // Render updated cart partial view
        $totalItems = collect($cart)->sum('quantity');
        $cartView = view('cart._table', ['cart' => $cart])->render();

        return response()->json([
            'success' => true,
            'cartView' => $cartView,
        ]);
    }
    public function add(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $item = \App\Models\Item::find($request->id);
        if ($request['quantity'] > $item->quantity) {
            return redirect()->back()->with('error', "Not enough stock for {$item->name}. Only {$item->quantity} available.");
        }
        
        $cart = session()->get('cart', []);

        if (isset($cart[$item->id])) {
            $cart[$item->id]['quantity'] += $request->quantity;
        } else {
            $cart[$item->id] = [
                'name' => $item->name,
                'price' => $item->price,
                'quantity' => $request->quantity,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->route('cart.view')->with('success', 'Item added to cart!');
    }

        public function search(Request $request)
    {
         $query = $request->input('query');

        $items = \App\Models\Item::where('name', 'like', "%{$query}%")
                    ->orWhere('barcode', 'like', "%{$query}%")
                    ->limit(10)
                    ->get();

        return response()->json($items);
    }
    public function checkout(Request $request)
    {
        $cart = session('cart', []); // Get cart from session
        $current_user = Auth::user()->name ?? 'Cashier';
        $current_user_id = Auth::user()->id ?? '1';
     
        if (empty($cart)) {
                return redirect()->back()->with('error_empty', 'Empty cart.');
        }
        foreach ($cart as $itemId => $cartItem) {
            $item = \App\Models\Item::find($itemId);

            if (!$item) {
                return redirect()->back()->with('error', 'Item not found.');
            }

            if ($cartItem['quantity'] > $item->quantity) {
                return redirect()->back()->with('error', "Not enough stock for {$item->name}. Only {$item->quantity} available.");
            }
           
            // Record sale
           
            $sale = Sale::create([
                    'item_name' => $item->name,
                    'item_id'   => $item->id,
                    'quantity'    => $cartItem['quantity'],
                    'total_price' => $item->price * $cartItem['quantity'],
                    'discount' => 0, 
                    'cashier' => $current_user,
                    'user_id' => $current_user_id,
            ]);
         
            foreach (array_values($cart) as $item) {
                  $sale->items()->attach($item['id'], [
                        'item_name' => $item['name'],
                        'item_id' => $item['id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'discount' => $item['discount'] ?? 0,
                        'cashier' => $current_user,
                        'user_id' => $current_user_id,
                    ]);
            }

            // Update stock
            $dbItem = Item::find($item['id']);
            if ($dbItem) {
                $dbItem->decrement('quantity', $item['quantity']);
            }
         
        }
       // return view('sales.receipt_new', compact('sale'));
        return redirect()->route('cart.view')->with('sale_id', $sale->id);


    }

    public function refresh()
    {
        session()->forget('cart');
        return response()->json(['message' => 'Cart refreshed']);
    }
   
    public function clear(Request $request)
        {   
            if(empty('cart')){
                 return redirect()->back()->with('success_no_item', 'No item added yet!.');
            }    
            session()->forget('cart'); // if using session
            // or Cart::truncate(); if using DB
            return redirect()->back()->with('success_clear_item', 'Cart has been cleared.');
        }
    public function receipt(Sale $sale)
    {
      //  $sale->load('items'); // if Sale has related items
        $sale = Sale::with('items')->findOrFail($sale->id);
        session()->forget('cart');
        return view('cart.receipt', compact('sale'));
    }

    public function update(Request $request)
    {
        $itemId = $request->input('id');
        $quantity = max(1, (int)$request->input('quantity')); // Minimum 1
        $item = Item::find($itemId);
        if ($quantity > $item->quantity) {
             return response()->json([
                    'error' => true,
                     'text' => "Not enough stock for {$item->name}. Only {$item->quantity} available.",
                ]);
              ///  return redirect()->back()->with('error', "Not enough stock for {$item->name}. Only {$item->quantity} available.");
        }
        $cart = session()->get('cart', []);

        if (isset($cart[$itemId])) {
            $cart[$itemId]['quantity'] = $quantity;
            session(['cart' => $cart]);
        }

        $cartView = view('cart._table', ['cart' => $cart])->render();

        return response()->json([
            'success' => true,
            'cartView' => $cartView,
        ]);
    }

    // public function remove(Request $request)
    // {
    //     $cart = session()->get('cart', []);
    //     $id = $request->id;

    //     if (isset($cart[$id])) {
    //         unset($cart[$id]);
    //         session()->put('cart', $cart);
    //     }

    //     return back()->with('success', 'Item removed from cart.');
    // }

    public function remove(Request $request)
    {
        $itemId = $request->input('id');

        // Get current cart from session
        $cart = session()->get('cart', []);
        $item = Item::where('id', $itemId)->first();
        // Remove item if it exists
        if (isset($cart[$itemId])) {
            unset($cart[$itemId]);
            session(['cart' => $cart]);
        }

         return back()->with('success-cart-remove', 'Item  '.$item['name'].' remove successfully.');
    }

    public function addByBarcode(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string',
            'quantity' => 'required|integer|min:1',
        ]);

        $item = \App\Models\Item::where('barcode', $request->barcode)->first();

        if (!$item) {
            return back()->with('error', 'Item not found by barcode.');
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$item->id])) {
            $cart[$item->id]['quantity'] += $request->quantity;
        } else {
            $cart[$item->id] = [
                'name' => $item->name,
                'price' => $item->price,
                'quantity' => $request->quantity,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->route('cart.view')->with('success', 'Item added by barcode!');
    }

    public function scanBarcode(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string'
        ]);

        $item = Item::where('barcode', $request->barcode)->first();

        if (!$item) {
            return back()->with('error', 'Item not found for barcode: ' . $request->barcode);
        }

        $cart = Session::get('cart', []);

        if (isset($cart[$item->id])) {
            $cart[$item->id]['quantity'] += 1;
        } else {
            $cart[$item->id] = [
                'name' => $item->name,
                'price' => $item->price,
                'quantity' => 1
            ];
        }

            Session::put('cart', $cart);
            return redirect()->route('cart.view')->with('success', 'Item added by barcode');
        }
        public function ajaxScan(Request $request)
        {

                $item = Item::where('barcode', $request->barcode)->first();
                if (!$item) {
                    return response()->json(['success' => false, 'message' => 'Item not found.']);
                }

                // Add to cart logic here...
                $cart = session()->get('cart', []);
                $id = $item->id;

                if (isset($cart[$id])) {
                    $cart[$id]['quantity'] += 1;
                } else {
                    $cart[$id] = [
                        'id' => $item->id,
                        'name' => $item->name,
                        'price' => $item->price,
                        'quantity' => 1,
                    ];
                }

                session()->put('cart', $cart);

                return response()->json(['success' => true]);

        }
        public function scanAndAddToCart($barcode)
        {
            $item = Item::where('barcode', $barcode)->first();

            if (!$item) {
                return response()->json(['error' => 'Item not found'], 404);
            }

            // Get current cart from session
            $cart = session()->get('cart', []);

            // If item already in cart, increment quantity
            if (isset($cart[$item->id])) {
                if ($cart[$item->id]['quantity'] < $item->quantity) {
                    $cart[$item->id]['quantity'] += 1;
                } else {
                    return response()->json(['error' => 'Not enough stock'], 400);
                }
            } else {
                // Add new item to cart
                $cart[$item->id] = [
                    'id' => $item->id,
                    'name' => $item->name,
                    'price' => $item->price,
                    'quantity' => 1,
                    'barcode' => $item->barcode,
                ];
            }

            // Save cart back to session
            session()->put('cart', $cart);

            return response()->json([
                'message' => 'Item added to cart',
                'item' => $item,
                'cart' => array_values($cart)
            ]);
        }

        public function getByBarcode($barcode)
        {
            $item = Item::where('barcode', $barcode)->first();

            if (!$item) {
                return response()->json(['error' => 'Item not found'], 404);
            }

            return response()->json($item);
        }

}

<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;


class CartController extends Controller
{
      public function index()
    {
        $cart = Session::get('cart', []);
        return view('cart.index', compact('cart'));
    }

    public function add(Request $request)
    {
        $item = Item::findOrFail($request->id);
        $cart = Session::get('cart', []);

        if (isset($cart[$item->id])) {
            $cart[$item->id]['quantity']++;
        } else {
            $cart[$item->id] = [
                'name' => $item->name,
                'price' => $item->price,
                'quantity' => 1
            ];
        }

        Session::put('cart', $cart);
        return back()->with('success', 'Item added to cart');
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
    public function checkout()
    {
        //dd($request->all());
        $cart = session('cart', []); // Get cart from session
       /// dd($cart);
        foreach ($cart as $itemId => $cartItem) {
            $item = Item::findOrFail($itemId);
            if (!$item) {
                return redirect()->back()->with('error', 'Item not found.');
            }
          
            if ($cartItem['quantity'] > $item->quantity) {
               
                return redirect()->back()->with('error', "Not enough stock for {$item->name}. Only {$item->quantity} available.");
            }
             // Record sale
            // $item = Sale::create([
            //         'id'     => $item->id,
            //         'quantity'    => $cartItem['quantity'],
            //         'total_price' => $item->price * $cartItem['quantity'],
            // ]);
            //   dd("okay ra". $item);
            // Update stock
            $item->decrement('quantity', $cartItem['quantity']);
            
        }
   
        // 4. Clear cart
        session()->forget('cart');

       return redirect()->route('index')->with('success-checkout', '✅ Checkout completed and cart cleared!');

    }

    public function search(Request $request)
    {
       $term = $request->term;
        $items = Item::where('name', 'like', "%$term%")
            ->orWhere('barcode', 'like', "%$term%")
            ->limit(10)
            ->get(['id', 'name', 'price', 'barcode','quantity']);

        return response()->json($items);
    }
    
    public function update(Request $request)
    {
        $cart = session()->get('cart', []);
        $id = $request->id;

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = (int) $request->quantity;
            session()->put('cart', $cart);
        }

        return back()->with('success', 'Cart updated.');
    }

    public function remove(Request $request)
    {
        $cart = session()->get('cart', []);
        $id = $request->id;

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return back()->with('success', 'Item removed from cart.');
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

        return redirect()->route('index')->with('success', 'Item added by barcode!');
    }

}


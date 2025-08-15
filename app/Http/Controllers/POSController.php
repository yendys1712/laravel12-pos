<?php

namespace App\Http\Controllers;
use App\Models\Item;
use App\Models\Sale;

use Illuminate\Http\Request;

class POSController extends Controller
{
    //
    public function addByBarcode(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string'
        ]);

        $item = Item::where('barcode', $request->barcode)->first();

        if (!$item) {
            return redirect()->back()->with('error', 'Item not found.');
        }

        // Check stock
        if ($item->quantity < 1) {
            return redirect()->back()->with('error', 'Item is out of stock.');
        }

        // Add to session cart
        $cart = session()->get('cart', []);

        // If already in cart, increment quantity
        if (isset($cart[$item->id])) {
            if ($cart[$item->id]['quantity'] + 1 > $item->quantity) {
                return redirect()->back()->with('error', 'Not enough stock.');
            }

            $cart[$item->id]['quantity'] += 1;
        } else {
            $cart[$item->id] = [
                'name' => $item->name,
                'price' => $item->price,
                'quantity' => 1,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', "{$item->name} added to cart.");
    }

    public function searchBarcode(Request $request)
        {
            $item = Item::where('barcode', $request->barcode)->first();

            if ($item) {
                // return item data or add to cart, etc.
            } else {
                return back()->with('error', 'Item not found');
            }
        }
}

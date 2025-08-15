<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ItemHistory;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ItemHistoryController extends Controller
{
    //
     // Show history
    public function index()
    {
        $histories = ItemHistory::with(['item', 'user'])
            ->latest()
            ->paginate(20);

        return view('item_histories.index', compact('histories'));
    }

    // Store new history entry
    public function store(array $data)
    {
        return ItemHistory::create($data);
    }

    // Helper method to log history  'user_id' => User::auth()->id()
    public static function log($item_name, $action,$current_user, $quantity = null, $price = null, $notes = null)
    {
         
        return ItemHistory::create([
            'item_name' => $item_name,
            'action'    => $action,
            'user_id'   => $current_user,
            'quantity'  => $quantity,
            'price'     => $price,
            'notes'     => $notes
        ]);
    }

}

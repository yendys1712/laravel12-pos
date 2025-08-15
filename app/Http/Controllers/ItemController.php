<?php

namespace App\Http\Controllers;
use App\Models\Item;
use App\Models\ItemHistory;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use DNS1D;

use Illuminate\Http\Request;

class ItemController extends Controller
{
    
   
      public function index()
    {
         $items = Item::orderBy('created_at','desc')->paginate(10);
       
         return view('items.index', compact('items'));
          
    }

    public function getData()
    {
        $item = Item::query();

        return DataTables::of($item)
            ->addColumn('action', function ($item) {
                return '
                       <td class="p-2 border">
                       <div class="d-flex gap-2">
                        <button  onclick="editItem('.$item->id.')" class="btn btn-sm btn-warning" data-bs-toggle="modal" >✏️ Edit</button>
                        <button onclick="deleteItem(' . $item->id . ')"  class="btn btn-sm btn-danger"> 🗑️ Delete</button>
                     
                      </td>
                       ';
            })
             ->addColumn('image', function ($item) {
                if ($item->image) {
                        return '<div style="text-align: center;">
                                <img src="' . asset($item->image) . '" width="70" height="70" style="object-fit: cover; border-radius: 5px;" />
                                </div>
                                ';
                    } else {
                        return '<div style="text-align: center;"> No Image </div>';
                    }
            })
            ->rawColumns(['action','image'])
            //->rawColumns(['image']) // allow raw HTML rendering
            ->make(true); // this is for AJAX
    }
    public function show($id)
     {
        $item = Item::findOrFail($id);
        $item->image_url = $item->image ? asset($item->image) : null;
        return response()->json($item);
       
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

    public function update(Request $request, $id)
    {
       $item = Item::findOrFail($id);

       if (!$item) {
        Log::warning("Item ID $id not found for update.");
        return response()->json(['message' => 'Item not found'], 404);
        }
        // ✅ Validate first
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'barcode' => "required|string|unique:items,barcode,{$id}",
            'price' => 'nullable|numeric',
            'quantity' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // ✅ Assign validated values
        $item->name = $validated['name'];
        $item->barcode = $validated['barcode'];
        $item->price = $validated['price'];
        $item->quantity = $validated['quantity'];
        $note = 'Edit' ?? 'Edit manually';

        // ✅ Save image if uploaded
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images'), $filename);
            $item->image = 'images/' . $filename;
        }
        $item->save();
         ItemHistoryController::log(
                       $item->name,
                       'edit',
                       Auth::id(),
                       $item->quantity,
                       $item->price,
                       $note
          );
        // ItemHistory::create([
        //         'item_name'  => $item->name,
        //         'action'    => 'edit',
        //         'user_id'   => Auth::id(),
        //         'quantity'   => $item->quantity,
               
        // ]);
        Log::info('Updated item:', $item->toArray());

       return response()->json(['message' => 'Item updated successfully']);
     
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

    // Store new item
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'price'    => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'barcode'  => 'nullable|string|unique:items,barcode',
            'image'    => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $item = new Item($request->only(['name', 'price', 'quantity', 'barcode','created_at']));
        $item->created_at;

        // ✅ Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/items'), $imageName);
            $item->image = 'uploads/items/' . $imageName;
        }
        // Item::create($item);
        $item['created_at'] = Carbon::now();
        $item->save();
        $note = 'Added manually' ?? '';
        ItemHistory::create([
                'item_name' => $item->name,
                'action'    => 'add',
                'price'    => $item->price,
                'quantity'    => $item->quantity,
                'user_id'   => Auth::id(),
                'created_at' => Carbon::now(),
                 'notes' =>  $note,
        ]);

        return redirect()->route('items.index')->with('success-item', 'Item created successfully.');
    }
    public function history()
    {
        $histories = ItemHistory::with('user')->latest()->paginate(15);
        return view('items.history', compact('histories'));
    }

    // Delete an item
    public function destroy($id)
    {
        $item = Item::findOrFail($id);
        ItemHistory::create([
            'item_name' => $item->name,
            'action'    => 'delete',
            'user_id'   => Auth::id(),
            'created_at' => Carbon::now(),
        ]);
        $item->delete();
        return response()->json(['message' => 'Item deleted successfully.']);
        //return redirect()->route('items.index')->with('success-delete', 'Item deleted successfully!');
    }
    public function deleteSelected(Request $request)
    {
        $ids = $request->input('id');
       
        if (empty($ids)) {
            return redirect()->back()->with('error', 'No items selected.');
        }
        Item::whereIn('id', $ids)->delete();
         
        return redirect()->route('items.index')->with('success-item-deted', 'Selected items deleted successfully.');
       
    }

        public function deleteAll(Request $request)
    {
        $ids = $request->input('id');

        if (!$ids || !is_array($ids)) {
            return redirect()->back()->with('error', 'No items selected.');
        }

        Item::whereIn('id', $ids)->delete();

        return redirect()->back()->with('success', 'Selected items deleted.');
    }

     public function scanAddForm()
    {
        return view('items.scan_add');
        
    }

    public function storeFromBarcode(Request $request)
    {
        $current_date =   Carbon::now();
        $item = Item::where('barcode', $request->barcode)->first();

        if ($item) {
            return response()->json([
                'message' => '📦 Item already exists.',
                'item' => $item['barcode'],
                'error' => true,
            ], 200);
        }

        $validated=  $request->validate([
            'barcode' => 'required|unique:items,barcode',
            'name'    => 'required|string|max:255',
            'price'   => 'required|numeric|min:0',
            'quantity'=> 'required|integer|min:0',
        ]);
         // Check if item already exists
           
        $newItem   = Item::create(['barcode' => $validated['barcode'],
                                    'name' => $validated['name'],
                                    'price' =>$validated['price'],
                                    'quantity' => $validated['quantity'],
                                    'created_at' => Carbon::now()
                                    
        ]);
        // $barcodeData = DNS1D::getBarcodePNG($item->barcode, 'C128');
        // file_put_contents(public_path("barcodes/{$item->name}.png"), base64_decode($barcodeData));
        ItemHistory::create([
                'item_name' => $validated['name'],
                'action'    => 'add',
                'user_id'   => Auth::id(),
        ]);
    //     return view('items.confirmation', compact('newItem')); 
    //  }
        return response()->json([
                    'message' => '📦 Item added successfully.',
                    'success' => true,
                    'item' => $newItem]);
            }

}

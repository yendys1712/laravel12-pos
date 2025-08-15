<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ItemsImport;
use App\Models\Item;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\Auth;

class ItemImportController extends Controller
{
    //
    public function showForm()
    {
   
        return view('items.import_items');
    }

    public function preview(Request $request)
    {
        
        $request->validate([
        'file' => 'required|mimes:xlsx,csv|max:2048'
        ]);

        $fileName = time() . '-' . $request->file('file')->getClientOriginalName();
        $path = $request->file('file')->storeAs('imports', $fileName);
        $rows = Excel::toArray([], $path)[0] ?? [];
        Session::put('import_data', $rows,$path);
        return view('items.import-preview', compact('rows', 'path'));
      
    }

    public function confirm(Request $request)
    {   

        $path = $request->input('file_path');

        if (!$path || !Storage::exists($path)) {
            return back()->withErrors(['Uploaded file could not be found.']);
        }

        Excel::import(new ItemsImport, $path);
        $rows = Excel::toArray([], $path)[0] ?? [];
        // Remove header row if exists
        if (isset($rows[0]) && strtolower($rows[0][0]) === 'barcode') {
            array_shift($rows);
        }

        foreach ($rows as $row) {
            $name    = trim($row[0] ?? '');
            $barcode = trim($row[1] ?? '');
            $price   = floatval($row[2] ?? 0);
            $quantity = floatval($row[3] ?? 0);
            //$date = Date::excelToDateTimeObject(floatval($row[4]))->format('Y-m-d H:i:s');
            $date = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d h:i:s');
            if ($barcode && !Item::where('barcode', $barcode)->exists()) {
             $DFF =   Item::create([
                    'name'    => $name,
                    'barcode' => $barcode,
                    'price'   => $price,
                    'created_at' => $date,
                    'quantity' => $quantity
                ]);
                $current_user = Auth::user()->id ?? '1';
                $note = 'Bulk added' ?? 'Added manually';
                ItemHistoryController::log(
                        $name,
                        'add',
                        $current_user,
                        $quantity,
                        $price,
                        $note
                    );
            }else{
                  $error_item_bulk_failed = '<label style="color:red;">' . e($name) . '</label>';
                  return redirect()->route('items.index')->with('error_item_bulk_failed', 'Barcode already exist on this item '.$error_item_bulk_failed );
            }
        }

        return redirect()->route('items.index')->with('success-item-bulk', 'Bulk Item Created Successfully.');

    }
       
}



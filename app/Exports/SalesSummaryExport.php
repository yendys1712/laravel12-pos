<?php

namespace App\Exports;

use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;


class SalesSummaryExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Sale::select(
                'id',
                DB::raw('SUM(quantity) as total_qty'),
                DB::raw('SUM(total_price) as total_sales'),
                DB::raw('MAX(created_at) as last_sold_at')
            )
            ->groupBy('id')
            ->with('items')
            ->get()
            ->filter(fn($sale) => $sale->item) // Filter out deleted items
            ->map(function($sale) {
                return [
                    'Item Name'    => $sale->item->name,
                    'Total Qty'    => $sale->total_qty,
                    'Total Sales'  => number_format($sale->total_sales, 2),
                    'Last Sold At' => \Carbon\Carbon::parse($sale->last_sold_at)->timezone('Asia/Manila')->format('M d, Y h:i A'),
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Item Name',
            'Total Qty Sold',
            'Total Sales (₱)',
            'Last Sold Date & Time',
        ];
    }
}

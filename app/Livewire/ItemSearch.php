<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Item;

class ItemSearch extends Component
{
    public $search = '';
    public function render()
    {
        $items = Item::where('name', 'like', '%' . $this->search . '%')
                     ->orderBy('name')
                     ->take(50) // optional limit for performance
                     ->get();

        return view('livewire.item-search', [
            'items' => $items
        ]);
    }
}

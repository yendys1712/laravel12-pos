<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // ✅ Correct namespace

class ItemHistory extends Model
{
    use HasFactory;
    //
   protected $fillable = [
        'item_name',
        'action',
        'user_id',
        'quantity',
        'price',
        'notes'
       
    ];
    
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
    
    public function user() {
        return $this->belongsTo(User::class);
    }
}

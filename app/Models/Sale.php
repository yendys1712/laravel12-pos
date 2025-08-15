<?php

namespace App\Models;
use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'id',
        'quantity',
        'total_price',
        'customer_id',
        'cashier',
        'user_id',
    ];

    public function items()
    {
       // return $this->belongsTo(Item::class);
       // return $this->belongsToMany(Item::class)->withPivot('quantity', 'price');
        return $this->belongsToMany(Item::class)
                ->withPivot('quantity', 'price','discount')
                ->withTimestamps();
    }
    // Cashier (User)
    public function cashier()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}

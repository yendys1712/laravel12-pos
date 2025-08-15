<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    //
    protected $table = 'items';
    protected $primaryKey = 'id'; // 👈 use this if 'id' column is missing
    public $timestamps = false;

    protected $fillable = ['name','price','created_at', 'quantity', 'barcode','image','image_url'];

     public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}

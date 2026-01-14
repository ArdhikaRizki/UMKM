<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockPurchase extends Model
{
    protected $fillable = [
        'product_id',
        'quantity',
        'purchase_price',
        'total_cost',
        'user_id',
        'notes'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

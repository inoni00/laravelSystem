<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use app\Http\Controllers\SalesController;

class Sale extends Model
{
    protected $fillable = [
        'product_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

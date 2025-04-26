<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'description', 'quantity', 'rate'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

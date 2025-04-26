<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'revision',
        'product_id',
        'description',
        'quantity',
        'rate',
        'amount',
        'delete',
        'created_by',
        'updated_by',
        'created_by',
        'updated_by',
        'deleted_by',

    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

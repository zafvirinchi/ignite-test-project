<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'quantity',
        'waste_percentage',
        'labour_percentage',
        'equipment_cost',
        'other_percentage',
        'margin_percentage',
        'revision',
        'material_items',
        'material_cost',
        'waste_amount',
        'labour_amount',
        'equipment_cost',
        'other_amount',
        'margin_amount',
        'sub_total',
        'amount',
        'created_by',    // 🔥 must be here
        'updated_by',    // 🔥 must be here (optional if you update later)
    ];

    public function materials()
    {
        return $this->hasMany(ProductMaterial::class);
    }

    // 🔥 Automatically set created_by and updated_by
    protected static function booted()
    {
        static::creating(function ($model) {
            $model->created_by = $model->created_by ?? Auth::id();
            $model->updated_by = $model->updated_by ?? Auth::id();
        });

        static::updating(function ($model) {
            $model->updated_by = Auth::id();
        });
    }
}

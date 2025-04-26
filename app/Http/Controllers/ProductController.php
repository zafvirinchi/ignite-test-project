<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function store(Request $request)
    {
        Auth::shouldUse('api'); // 👈 Force using 'api' guard
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:1',
            'waste_percentage' => 'required|numeric|min:0',
            'labour_percentage' => 'required|numeric|min:0',
            'equipment_cost' => 'required|numeric|min:0',
            'other_percentage' => 'required|numeric|min:0',
            'margin_percentage' => 'required|numeric|min:0',
            'materials' => 'required|array|min:1',
            'materials.*.description' => 'required|string',
            'materials.*.quantity' => 'required|numeric|min:0',
            'materials.*.rate' => 'required|numeric|min:0',
        ]);

        return DB::transaction(function () use ($request) {
            // Create product
            $product = Product::create([
                'name' => $request->name,
                'description' => $request->description,
                'quantity' => $request->quantity,
                'waste_percentage' => $request->waste_percentage,
                'labour_percentage' => $request->labour_percentage,
                'equipment_cost' => $request->equipment_cost,
                'other_percentage' => $request->other_percentage,
                'margin_percentage' => $request->margin_percentage,
                'revision' => 0,
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);

            $totalMaterialCost = 0;
            $materialCount = 0;

            foreach ($request->materials as $material) {
                $amount = $material['rate'] * $material['quantity'];
                $totalMaterialCost += $amount;
                $materialCount++;

                ProductMaterial::create([
                    'product_id' => $product->id,
                    'description' => $material['description'],
                    'quantity' => $material['quantity'],
                    'rate' => $material['rate'],
                    'amount' => $amount,
                    'revision' => 0,
                    'created_by' => auth()->id(),
                ]);
            }

            // Calculate costs
            $waste = ($product->waste_percentage / 100) * $totalMaterialCost;
            $labour = ($product->labour_percentage / 100) * ($totalMaterialCost + $waste);
            $other = ($product->other_percentage / 100) * ($product->equipment_cost + $labour + $totalMaterialCost + $waste);
            $margin = ($product->margin_percentage / 100) * ($other + $product->equipment_cost + $labour + $totalMaterialCost + $waste);

            $subTotal = $margin + $other + $product->equipment_cost + $labour + $totalMaterialCost + $waste;
            $amount = $product->quantity * $subTotal;

            // Update product with calculated values
            $product->update([
                'material_items' => $materialCount,
                'material_cost' => $totalMaterialCost,
                'waste_amount' => $waste,
                'labour_amount' => $labour,
                'other_amount' => $other,
                'margin_amount' => $margin,
                'sub_total' => $subTotal,
                'amount' => $amount,
                'updated_by' => auth()->id(),
            ]);

            return response()->json($product->load('materials'), 201);
        });
    }
}

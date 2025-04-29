<?php

namespace App\Http\Controllers;

use App\Models\ProductMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductMaterialController extends Controller
{
    public function store(Request $request)
    {
        Auth::shouldUse('api');

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'description' => 'required|string',
            'quantity' => 'required|numeric|min:0',
            'rate' => 'required|numeric|min:0',
        ]);

        $amount = $request->rate * $request->quantity;

        $material = ProductMaterial::create([
            'product_id' => $request->product_id,
            'description' => $request->description,
            'quantity' => $request->quantity,
            'rate' => $request->rate,
            'amount' => $amount,
            'revision' => 0,
            'delete' => false,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        return response()->json($material, 201);
    }

    public function update(Request $request, $id)
    {
        Auth::shouldUse('api');

        $material = ProductMaterial::findOrFail($id);

        $request->validate([
            'description' => 'required|string',
            'quantity' => 'required|numeric|min:0',
            'rate' => 'required|numeric|min:0',
        ]);

        $amount = $request->rate * $request->quantity;

        $material->update([
            'description' => $request->description,
            'quantity' => $request->quantity,
            'rate' => $request->rate,
            'amount' => $amount,
            'revision' => $material->revision + 1,
            'updated_by' => auth()->id(),
        ]);

        return response()->json($material, 200);
    }

    public function destroy($id)
    {
        Auth::shouldUse('api');

        $material = ProductMaterial::findOrFail($id);

        $material->update([
            'delete' => true,
            'deleted_by' => auth()->id(),
        ]);

        return response()->json(['message' => 'Product Material deleted successfully'], 200);
    }

}

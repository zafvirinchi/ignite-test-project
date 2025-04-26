<?php

namespace App\Http\Controllers;

use App\Models\ProductMaterial;
use Illuminate\Http\Request;

class ProductMaterialController extends Controller
{
    public function update(Request $request, $id)
    {
        $material = ProductMaterial::findOrFail($id);

        $request->validate([
            'description' => 'required|string',
            'quantity' => 'required|numeric|min:0',
            'rate' => 'required|numeric|min:0',
        ]);

        $material->update([
            'description' => $request->description,
            'quantity' => $request->quantity,
            'rate' => $request->rate,
            'amount' => $request->quantity * $request->rate,
            'revision' => $material->revision + 1,
            'updated_by' => auth()->id(),
        ]);

        return response()->json($material, 200);
    }
}

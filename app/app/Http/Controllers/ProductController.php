<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductResquest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        return response()->json($products, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductResquest $productResquest)
    {
        if ($productResquest->hasFile('photo')) {
            $image = $productResquest->file('photo');
            $filename = $image->getClientOriginalName();
            $path = Storage::disk('public')->putFileAs('images', $image, $filename);
            $productResquest['photo'] = $path;
        }

        $product = Product::create([
            'name' => $productResquest->name,
            'price' => $productResquest->price,
            'photo' => isset($path) ? $path : $productResquest->photo,
        ]);

        if ($product)
            return response()->json($product, 201);

        return response()->json("Product not created", 400);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        return response()->json($product, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(string $id, ProductResquest $productResquest)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        $product->update($productResquest->all());
        return response()->json($product, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);
        if ($product) {
            $delete = $product->delete();
            if ($delete)
                return response()->json('Product deleted', 204);

            return response()->json('Product not deleted', 400);
        }
        return response()->json('Product not found', 404);
    }
}

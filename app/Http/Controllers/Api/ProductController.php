<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Traits\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{
    use ApiResponse;

    /**
     * Display a paginated listing of products.
     */
    public function index()
    {
        $products = Product::paginate(10);
        return self::success(['products' => $products]);
    }

    /**
     * Store a newly created product.
     */
    public function store(StoreProductRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $imagePath;
        }

        $product = Product::create($validated);

        return self::created($product, 'Product created successfully.');
    }

    /**
     * Display a specific product.
     */
    public function show(Product $product)
    {
        return self::success(['product' => $product]);
    }

    /**
     * Update a specific product.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }

            $imagePath = $request->file('image')->store('products', 'public');
            $data['image'] = $imagePath;
        }

        $product->update($data);

        return self::success($product, 'Product updated successfully.');
    }

    /**
     * Delete a specific product.
     */
    public function destroy(Product $product)
    {
        // Delete image from storage
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return self::deleted('Product deleted successfully.');
    }
}

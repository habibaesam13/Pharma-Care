<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Policies\ProductPolicy;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProductController extends Controller
{
    use ApiResponse, AuthorizesRequests;

    /**
     * Display a paginated listing of products.
     */
public function index(Request $request)
{
    $query = Product::query();

    //Filter: Product name search
    if ($request->filled('search')) {
        $search = trim($request->search);
        $query->where('name', 'like', '%' . $search . '%');
    }

    //Filter: Category name
    if ($request->filled('category_name')) {
        $categoryName = trim($request->category_name);
        $query->whereHas('category', function ($q) use ($categoryName) {
            $q->where('name', 'like', '%' . $categoryName . '%');
        });
    }

    //Filter: Category ID (or no category)
    if ($request->filled('category_id')) {
        $categoryId = $request->category_id;
        if ($categoryId === 'null' || is_null($categoryId)) {
            $query->whereNull('category_id'); // Products without category
        } else {
            $query->where('category_id', $categoryId);
        }
    }

    //Filter: Price range
    if ($request->filled('min_price')) {
        $query->where('price', '>=', $request->min_price);
    }

    if ($request->filled('max_price')) {
        $query->where('price', '<=', $request->max_price);
    }

    //Filter: Brand
    if ($request->filled('brand')) {
        $query->where('brand', $request->brand);
    }

    //Filter: Discounted products
    if ($request->filled('discounted') && $request->discounted) {
        $query->whereNotNull('discount_amount')->where('discount_amount', '>', 0);
    }

    $products = $query->with('category')->paginate(10);

    return self::success([
        'filters' => $request->all(),
        'products' => $products,
    ]);
}

    /**
     * Display a specific product.
     */
    public function show(Product $product)
    {
        return self::success(['product' => $product]);
    }
    public function store(StoreProductRequest $request)
{
    $this->authorize('create', Product::class);

    $validated = $request->validated();

    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('products', 'public');
        $validated['image'] = $imagePath;
    }

    $product = Product::create($validated);

    return self::created($product, 'Product created successfully.');
}

public function update(UpdateProductRequest $request, Product $product)
{
    $this->authorize('update', $product);

    $data = $request->validated();

    if ($request->hasFile('image')) {
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $imagePath = $request->file('image')->store('products', 'public');
        $data['image'] = $imagePath;
    }

    $product->update($data);

    return self::success($product, 'Product updated successfully.');
}

public function destroy(Product $product)
{
    $this->authorize('delete', $product);

    if ($product->image && Storage::disk('public')->exists($product->image)) {
        Storage::disk('public')->delete($product->image);
    }

    $product->delete();

    return self::deleted('Product deleted successfully.');
}

}

<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use App\Traits\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class CategoryController extends Controller
{
    use ApiResponse, AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::paginate(10);
        return self::success(['categories' => $categories]);
    }
     /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return self::success(['category' => $category]);
    }
    public function store(StoreCategoryRequest $request)
{
    $this->authorize('create', Category::class);

    $validated = $request->validated();
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('categories', 'public');
        $validated['image'] = $imagePath;
    }

    $category = Category::create($validated);

    return self::created($category, 'Category created successfully.');
}

public function update(UpdateCategoryRequest $request, Category $category)
{
    $this->authorize('update', $category);

    $validated = $request->validated();

    if ($request->hasFile('image')) {
        if ($category->image && Storage::disk('public')->exists($category->image)) {
            Storage::disk('public')->delete($category->image);
        }

        $imagePath = $request->file('image')->store('categories', 'public');
        $validated['image'] = $imagePath;
    }

    $category->update($validated);

    return self::success($category->fresh(), 'Category updated successfully.');
}
    public function products(Category $category)
    {
        $products = $category->products()->paginate(10); 

        return self::success([
            'category' => $category->name,
            'products' => $products,
        ]);
    }

public function destroy(Category $category)
{
    $this->authorize('delete', $category);

    if ($category->image && Storage::disk('public')->exists($category->image)) {
        Storage::disk('public')->delete($category->image);
    }

    $category->delete();

    return self::deleted('Category deleted successfully.');
}

}

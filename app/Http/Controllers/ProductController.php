<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Get the authenticated user's restaurant.
     */
    private function getRestaurant()
    {
        return auth()->user()->restaurant;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $restaurant = $this->getRestaurant();
        
        $products = $restaurant->products()
            ->with('category')
            ->latest()
            ->get();

        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $restaurant = $this->getRestaurant();
        $categories = $restaurant->categories()->orderBy('sort_order')->get();

        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request): RedirectResponse
    {
        $restaurant = $this->getRestaurant();

        // Ensure category belongs to this restaurant
        $category = $restaurant->categories()->findOrFail($request->category_id);

        $data = $request->validated();
        $data['restaurant_id'] = $restaurant->id;
        $data['is_available'] = $request->has('is_available');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $restaurant->products()->create($data);

        return redirect()->route('products.index')
            ->with('success', 'تم إضافة المنتج بنجاح.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): View
    {
        $restaurant = $this->getRestaurant();
        
        $product = $restaurant->products()->with('category')->findOrFail($id);

        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        $restaurant = $this->getRestaurant();
        
        $product = $restaurant->products()->findOrFail($id);
        $categories = $restaurant->categories()->orderBy('sort_order')->get();

        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, string $id): RedirectResponse
    {
        $restaurant = $this->getRestaurant();
        
        $product = $restaurant->products()->findOrFail($id);

        // Ensure category belongs to this restaurant
        $category = $restaurant->categories()->findOrFail($request->category_id);

        $data = $request->validated();
        $data['is_available'] = $request->has('is_available');

        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('products.index')
            ->with('success', 'تم تحديث المنتج بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        $restaurant = $this->getRestaurant();
        
        $product = $restaurant->products()->findOrFail($id);

        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'تم حذف المنتج بنجاح.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Get the authenticated user's restaurant.
     */
    private function getRestaurant()
    {
        return auth()->user()->getOrCreateRestaurant();
    }

    /**
     * Display a listing of the restaurant's categories.
     */
    public function index(): View
    {
        $restaurant = $this->getRestaurant();

        $categories = $restaurant->categories()
            ->orderBy('sort_order')
            ->get();

        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create(): View
    {
        return view('categories.create');
    }

    /**
     * Store a newly created category linked to the user's restaurant.
     */
    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        $restaurant = $this->getRestaurant();

        $restaurant->categories()->create([
            'name'       => $request->name,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'تم إضافة القسم بنجاح.');
    }

    /**
     * Display the specified category (scoped to the user's restaurant).
     */
    public function show(string $id): View
    {
        $restaurant = $this->getRestaurant();

        $category = $restaurant->categories()->findOrFail($id);

        return view('categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified category (scoped to the user's restaurant).
     */
    public function edit(string $id): View
    {
        $restaurant = $this->getRestaurant();

        $category = $restaurant->categories()->findOrFail($id);

        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified category in storage.
     */
    public function update(UpdateCategoryRequest $request, string $id): RedirectResponse
    {
        $restaurant = $this->getRestaurant();

        $category = $restaurant->categories()->findOrFail($id);

        $category->update([
            'name'       => $request->name,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'تم تحديث القسم بنجاح.');
    }

    /**
     * Remove the specified category (scoped to the user's restaurant).
     */
    public function destroy(string $id): RedirectResponse
    {
        $restaurant = $this->getRestaurant();

        $category = $restaurant->categories()->findOrFail($id);

        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'تم حذف القسم بنجاح.');
    }
}

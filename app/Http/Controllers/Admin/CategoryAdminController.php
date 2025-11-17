<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::withCount('documents')
                              ->ordered()
                              ->paginate(config('wiki.items_per_page', 15));

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $parentCategories = Category::rootCategories()->ordered()->get();
        return view('admin.categories.create', compact('parentCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:100',
            'color' => 'nullable|string|max:7',
            'parent_id' => 'nullable|exists:categories,id',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        // Set defaults
        $validated['is_active'] = $request->has('is_active');
        $validated['order'] = $validated['order'] ?? 0;

        Category::create($validated);

        return redirect()->route('admin.categories.index')
                         ->with('success', 'Category created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $category->load(['documents' => function ($query) {
            $query->latest()->take(10);
        }, 'children']);

        return view('admin.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        $parentCategories = Category::rootCategories()
                                    ->where('id', '!=', $category->id)
                                    ->ordered()
                                    ->get();

        return view('admin.categories.edit', compact('category', 'parentCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('categories')->ignore($category->id)],
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:100',
            'color' => 'nullable|string|max:7',
            'parent_id' => [
                'nullable',
                'exists:categories,id',
                function ($attribute, $value, $fail) use ($category) {
                    // Prevent setting self as parent
                    if ($value == $category->id) {
                        $fail('A category cannot be its own parent.');
                    }
                },
            ],
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['order'] = $validated['order'] ?? 0;

        $category->update($validated);

        return redirect()->route('admin.categories.index')
                         ->with('success', 'Category updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        // Check if category has documents
        if ($category->documents()->count() > 0) {
            return back()->with('error', 'Cannot delete category with existing documents. Move or delete documents first.');
        }

        // Check if category has children
        if ($category->children()->count() > 0) {
            return back()->with('error', 'Cannot delete category with subcategories. Delete or reassign subcategories first.');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
                         ->with('success', 'Category deleted successfully!');
    }
}

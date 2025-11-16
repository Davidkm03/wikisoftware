<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Document;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show(Category $category)
    {
        $documents = Document::published()
            ->where('category_id', $category->id)
            ->with(['author', 'tags'])
            ->latest()
            ->paginate(config('wiki.pagination.per_page'));

        return view('categories.show', compact('category', 'documents'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Document;
use App\Models\Tag;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $query = $request->get('q');
        $categoryId = $request->get('category');
        $tagId = $request->get('tag');
        $sort = $request->get('sort', 'relevance');

        if (strlen($query) < config('wiki.search.min_query_length', 3)) {
            return redirect()->back()->with('error', 'Search query must be at least 3 characters.');
        }

        // Build the query
        $documentsQuery = Document::published()
            ->with(['category', 'author', 'tags'])
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhere('excerpt', 'like', "%{$query}%")
                    ->orWhere('content', 'like', "%{$query}%")
                    ->orWhereHas('tags', function ($tagQuery) use ($query) {
                        $tagQuery->where('name', 'like', "%{$query}%");
                    });
            });

        // Apply category filter
        if ($categoryId) {
            $documentsQuery->where('category_id', $categoryId);
        }

        // Apply tag filter
        if ($tagId) {
            $documentsQuery->whereHas('tags', function ($q) use ($tagId) {
                $q->where('tags.id', $tagId);
            });
        }

        // Apply sorting
        switch ($sort) {
            case 'date_desc':
                $documentsQuery->latest();
                break;
            case 'date_asc':
                $documentsQuery->oldest();
                break;
            case 'title':
                $documentsQuery->orderBy('title', 'asc');
                break;
            case 'views':
                $documentsQuery->withCount('views')->orderBy('views_count', 'desc');
                break;
            default: // relevance
                // Order by title match first, then content
                $documentsQuery->orderByRaw("CASE
                    WHEN title LIKE ? THEN 1
                    WHEN excerpt LIKE ? THEN 2
                    ELSE 3
                END", ["%{$query}%", "%{$query}%"]);
                break;
        }

        $documents = $documentsQuery->paginate(config('wiki.pagination.per_page'));

        // Get categories and tags for filters
        $categories = Category::active()->ordered()->get();
        $tags = Tag::popular(20)->get();

        return view('search.results', compact('documents', 'query', 'categories', 'tags', 'categoryId', 'tagId', 'sort'));
    }
}

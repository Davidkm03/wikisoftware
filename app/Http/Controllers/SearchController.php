<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $query = $request->get('q');

        if (strlen($query) < config('wiki.search.min_query_length', 3)) {
            return redirect()->back()->with('error', 'Search query must be at least 3 characters.');
        }

        $documents = Document::published()
            ->with(['category', 'author', 'tags'])
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhere('excerpt', 'like', "%{$query}%")
                    ->orWhere('content', 'like', "%{$query}%");
            })
            ->paginate(config('wiki.pagination.per_page'));

        return view('search.results', compact('documents', 'query'));
    }
}

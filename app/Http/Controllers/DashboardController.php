<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Document;
use App\Models\DocumentView;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Recent documents
        $recentDocuments = Document::published()
            ->with(['category', 'author'])
            ->latest()
            ->take(6)
            ->get();

        // Popular documents
        $popularDocuments = Document::published()
            ->with(['category', 'author'])
            ->orderBy('views_count', 'desc')
            ->take(6)
            ->get();

        // User's favorites
        $favoriteDocuments = $user->favorites()
            ->with(['category', 'author'])
            ->latest('favorites.created_at')
            ->take(6)
            ->get();

        // User's recent views
        $recentlyViewed = DocumentView::where('user_id', $user->id)
            ->with(['document.category', 'document.author'])
            ->latest()
            ->take(6)
            ->get()
            ->pluck('document')
            ->unique('id');

        // Categories with document counts
        $categories = Category::active()
            ->ordered()
            ->withCount(['documents' => function ($query) {
                $query->where('status', 'published');
            }])
            ->get();

        // Statistics
        $stats = [
            'total_documents' => Document::published()->count(),
            'total_categories' => Category::active()->count(),
            'my_documents' => Document::where('user_id', $user->id)->count(),
            'my_favorites' => $user->favorites()->count(),
        ];

        return view('dashboard', compact(
            'recentDocuments',
            'popularDocuments',
            'favoriteDocuments',
            'recentlyViewed',
            'categories',
            'stats'
        ));
    }
}

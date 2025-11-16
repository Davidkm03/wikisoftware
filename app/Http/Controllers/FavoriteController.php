<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function toggle(Request $request, Document $document)
    {
        $user = $request->user();

        if ($user->favorites()->where('document_id', $document->id)->exists()) {
            $user->favorites()->detach($document->id);
            $message = 'Document removed from favorites.';
        } else {
            $user->favorites()->attach($document->id);
            $message = 'Document added to favorites.';
        }

        return redirect()->back()->with('success', $message);
    }

    public function index(Request $request)
    {
        $favorites = $request->user()
            ->favorites()
            ->with(['category', 'author'])
            ->latest('favorites.created_at')
            ->paginate(config('wiki.pagination.per_page'));

        return view('favorites.index', compact('favorites'));
    }
}

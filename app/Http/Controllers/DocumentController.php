<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Document;
use App\Models\DocumentView;
use App\Models\Tag;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $query = Document::with(['category', 'author', 'tags']);

        // Filter by category
        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter by tag
        if ($request->has('tag')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('tags.id', $request->tag);
            });
        }

        // Filter by status (admin/editor only)
        if ($request->user()->canEdit() && $request->has('status')) {
            $query->where('status', $request->status);
        } else {
            $query->published();
        }

        $documents = $query->latest()->paginate(config('wiki.pagination.per_page'));
        $categories = Category::active()->ordered()->get();
        $tags = Tag::popular(20)->get();

        return view('documents.index', compact('documents', 'categories', 'tags'));
    }

    public function create()
    {
        $this->authorize('create', Document::class);

        $categories = Category::active()->ordered()->get();
        $tags = Tag::all();

        return view('documents.create', compact('categories', 'tags'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Document::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|in:draft,published',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        $document = Document::create([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'excerpt' => $validated['excerpt'],
            'content' => $validated['content'],
            'category_id' => $validated['category_id'],
            'status' => $validated['status'],
            'user_id' => $request->user()->id,
        ]);

        if (!empty($validated['tags'])) {
            $document->tags()->attach($validated['tags']);
        }

        // Create initial version
        $document->createVersion($request->user(), 'Initial version');

        return redirect()->route('documents.show', $document)
            ->with('success', 'Document created successfully!');
    }

    public function show(Document $document)
    {
        $this->authorize('view', $document);

        $document->load(['category', 'author', 'tags', 'attachments', 'versions']);

        // Track view
        DocumentView::create([
            'document_id' => $document->id,
            'user_id' => auth()->id(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        $document->incrementViews();

        return view('documents.show', compact('document'));
    }

    public function edit(Document $document)
    {
        $this->authorize('update', $document);

        $categories = Category::active()->ordered()->get();
        $tags = Tag::all();

        return view('documents.edit', compact('document', 'categories', 'tags'));
    }

    public function update(Request $request, Document $document)
    {
        $this->authorize('update', $document);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|in:draft,published,archived',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'change_summary' => 'nullable|string',
        ]);

        // Create version before updating
        if ($document->content !== $validated['content'] || $document->title !== $validated['title']) {
            $document->createVersion($request->user(), $validated['change_summary'] ?? 'Document updated');
        }

        $document->update([
            'title' => $validated['title'],
            'excerpt' => $validated['excerpt'],
            'content' => $validated['content'],
            'category_id' => $validated['category_id'],
            'status' => $validated['status'],
            'updated_by' => $request->user()->id,
        ]);

        $document->tags()->sync($validated['tags'] ?? []);

        return redirect()->route('documents.show', $document)
            ->with('success', 'Document updated successfully!');
    }

    public function destroy(Document $document)
    {
        $this->authorize('delete', $document);

        $document->delete();

        return redirect()->route('documents.index')
            ->with('success', 'Document deleted successfully!');
    }

    public function exportPdf(Document $document)
    {
        $this->authorize('view', $document);

        // Load relationships
        $document->load(['category', 'author', 'tags', 'attachments']);

        // Generate PDF
        $pdf = Pdf::loadView('documents.pdf', compact('document'));

        // Download with proper filename
        $filename = Str::slug($document->title) . '.pdf';

        return $pdf->download($filename);
    }
}

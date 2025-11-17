@extends('layouts.app')

@section('title', 'Search Results')

@section('content')
<div class="py-6">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900">Search Results</h2>
            <p class="mt-2 text-sm text-gray-600">
                Found <strong>{{ $documents->total() }}</strong> result(s) for "<strong>{{ $query }}</strong>"
            </p>
        </div>

        <!-- Search Results -->
        <div class="space-y-4">
            @forelse($documents as $document)
                <div class="bg-white shadow rounded-lg p-6 hover:shadow-md transition">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <a href="{{ route('documents.show', $document) }}" class="group">
                                <h3 class="text-lg font-semibold text-gray-900 group-hover:text-indigo-600">
                                    {{ $document->title }}
                                </h3>
                            </a>
                            <p class="mt-2 text-sm text-gray-600 line-clamp-2">{{ strip_tags($document->excerpt) }}</p>

                            <div class="mt-3 flex flex-wrap items-center gap-3 text-xs text-gray-500">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $document->category->color }}-100 text-{{ $document->category->color }}-800">
                                    {{ $document->category->name }}
                                </span>
                                <span>by {{ $document->author->name }}</span>
                                <span>📅 {{ $document->created_at->format('M d, Y') }}</span>
                                <span>👁️ {{ $document->views_count }} views</span>
                            </div>

                            @if($document->tags->count() > 0)
                                <div class="mt-2 flex flex-wrap gap-1">
                                    @foreach($document->tags->take(5) as $tag)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-700">
                                            #{{ $tag->name }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No results found</h3>
                    <p class="mt-1 text-sm text-gray-500">Try adjusting your search terms</p>
                    <div class="mt-6">
                        <a href="{{ route('documents.index') }}"
                            class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                            Browse all documents
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($documents->hasPages())
            <div class="mt-6">
                {{ $documents->appends(['q' => $query])->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'My Favorites')

@section('content')
<div class="py-6">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumbs -->
        <x-breadcrumbs :items="[
            ['label' => 'Favorites']
        ]" />

        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900">⭐ My Favorite Documents</h2>
            <p class="mt-2 text-sm text-gray-600">{{ $favorites->total() }} document(s) marked as favorite</p>
        </div>

        <!-- Favorites Grid -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($favorites as $document)
                <div class="relative rounded-lg border border-gray-300 bg-white p-6 shadow-sm hover:border-indigo-400 hover:shadow-md transition">
                    <div class="flex items-start justify-between">
                        <div class="flex-1 min-w-0">
                            <a href="{{ route('documents.show', $document) }}" class="focus:outline-none">
                                <span class="absolute inset-0"></span>
                                <h3 class="text-base font-semibold text-gray-900">{{ $document->title }}</h3>
                            </a>
                            <p class="mt-2 text-sm text-gray-500 line-clamp-2">{{ strip_tags($document->excerpt) }}</p>
                        </div>
                    </div>

                    <div class="mt-4 flex items-center justify-between text-xs text-gray-500">
                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-{{ $document->category->color }}-100 text-{{ $document->category->color }}-800">
                            {{ $document->category->name }}
                        </span>
                        <div class="flex items-center space-x-2">
                            <span>👁️ {{ $document->views_count }}</span>
                            <span>📅 {{ $document->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>

                    <div class="mt-4 flex items-center text-xs text-gray-500">
                        <span>by {{ $document->author->name }}</span>
                    </div>

                    <!-- Remove from favorites -->
                    <form action="{{ route('favorites.toggle', $document) }}" method="POST" class="relative z-10 mt-4">
                        @csrf
                        <button type="submit"
                            class="text-xs text-red-600 hover:text-red-800 font-medium">
                            Remove from favorites
                        </button>
                    </form>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No favorites yet</h3>
                    <p class="mt-1 text-sm text-gray-500">Start adding documents to your favorites</p>
                    <div class="mt-6">
                        <a href="{{ route('documents.index') }}"
                            class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                            Browse documents
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($favorites->hasPages())
            <div class="mt-6">
                {{ $favorites->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

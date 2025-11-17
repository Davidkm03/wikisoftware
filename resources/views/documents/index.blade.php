@extends('layouts.app')

@section('title', 'Documents')

@section('content')
<div class="py-6">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between">
            <div class="min-w-0 flex-1">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                    Documents
                </h2>
            </div>
            @can('create', App\Models\Document::class)
                <div class="mt-4 flex md:ml-4 md:mt-0">
                    <a href="{{ route('documents.create') }}"
                        class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                        <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        New Document
                    </a>
                </div>
            @endcan
        </div>

        <!-- Filters -->
        <div class="mt-6 flex flex-wrap gap-2">
            <a href="{{ route('documents.index') }}"
                class="inline-flex items-center rounded-md px-3 py-2 text-sm font-medium {{ !request()->has('category') && !request()->has('tag') ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50' }}">
                All Documents
            </a>
            @foreach($categories as $category)
                <a href="{{ route('documents.index', ['category' => $category->id]) }}"
                    class="inline-flex items-center rounded-md px-3 py-2 text-sm font-medium {{ request('category') == $category->id ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50' }}">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>

        <!-- Documents Grid -->
        <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($documents as $document)
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

                    @if($document->tags->count() > 0)
                        <div class="mt-3 flex flex-wrap gap-1">
                            @foreach($document->tags->take(3) as $tag)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-700">
                                    {{ $tag->name }}
                                </span>
                            @endforeach
                        </div>
                    @endif

                    <div class="mt-4 flex items-center text-xs text-gray-500">
                        <span>by {{ $document->author->name }}</span>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-gray-500">No documents found.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($documents->hasPages())
            <div class="mt-6">
                {{ $documents->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

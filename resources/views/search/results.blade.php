@extends('layouts.app')

@section('title', 'Search Results')

@section('content')
<div class="py-6">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumbs -->
        <x-breadcrumbs :items="[
            ['label' => 'Search Results']
        ]" />

        <div class="lg:grid lg:grid-cols-4 lg:gap-8">
            <!-- Filters Sidebar -->
            <div class="hidden lg:block lg:col-span-1">
                <div class="bg-white shadow rounded-lg p-6 sticky top-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Filters</h3>

                    <form method="GET" action="{{ route('search') }}" class="space-y-6">
                        <input type="hidden" name="q" value="{{ $query }}">

                        <!-- Category Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                            <select name="category" onchange="this.form.submit()"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $categoryId == $category->id ? 'selected' : '' }}>
                                        {{ $category->icon ?? '📄' }} {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Tag Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tag</label>
                            <select name="tag" onchange="this.form.submit()"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">All Tags</option>
                                @foreach($tags as $tag)
                                    <option value="{{ $tag->id }}" {{ $tagId == $tag->id ? 'selected' : '' }}>
                                        #{{ $tag->name }} ({{ $tag->usage_count }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Sort -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
                            <select name="sort" onchange="this.form.submit()"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="relevance" {{ $sort == 'relevance' ? 'selected' : '' }}>Relevance</option>
                                <option value="date_desc" {{ $sort == 'date_desc' ? 'selected' : '' }}>Newest First</option>
                                <option value="date_asc" {{ $sort == 'date_asc' ? 'selected' : '' }}>Oldest First</option>
                                <option value="title" {{ $sort == 'title' ? 'selected' : '' }}>Title A-Z</option>
                                <option value="views" {{ $sort == 'views' ? 'selected' : '' }}>Most Viewed</option>
                            </select>
                        </div>

                        <!-- Clear Filters -->
                        @if($categoryId || $tagId || $sort != 'relevance')
                            <button type="button" onclick="window.location.href='{{ route('search', ['q' => $query]) }}'"
                                class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Clear Filters
                            </button>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Search Results -->
            <div class="lg:col-span-3 mt-6 lg:mt-0">
                <!-- Results Header -->
                <div class="mb-6 flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Search Results</h2>
                        <p class="mt-2 text-sm text-gray-600">
                            Found <strong>{{ $documents->total() }}</strong> result(s) for "<strong class="text-indigo-600">{{ $query }}</strong>"
                            @if($categoryId)
                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-800">
                                    {{ $categories->find($categoryId)->name ?? '' }}
                                </span>
                            @endif
                            @if($tagId)
                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-700">
                                    #{{ $tags->find($tagId)->name ?? '' }}
                                </span>
                            @endif
                        </p>
                    </div>

                    <!-- Mobile Filter Toggle -->
                    <div class="lg:hidden">
                        <button type="button" onclick="document.getElementById('mobile-filters').classList.toggle('hidden')"
                            class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Mobile Filters -->
                <div id="mobile-filters" class="hidden lg:hidden mb-6 bg-white shadow rounded-lg p-4">
                    <form method="GET" action="{{ route('search') }}" class="space-y-4">
                        <input type="hidden" name="q" value="{{ $query }}">

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <select name="category" onchange="this.form.submit()"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $categoryId == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tag</label>
                            <select name="tag" onchange="this.form.submit()"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">All Tags</option>
                                @foreach($tags as $tag)
                                    <option value="{{ $tag->id }}" {{ $tagId == $tag->id ? 'selected' : '' }}>
                                        #{{ $tag->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Sort</label>
                            <select name="sort" onchange="this.form.submit()"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="relevance" {{ $sort == 'relevance' ? 'selected' : '' }}>Relevance</option>
                                <option value="date_desc" {{ $sort == 'date_desc' ? 'selected' : '' }}>Newest</option>
                                <option value="date_asc" {{ $sort == 'date_asc' ? 'selected' : '' }}>Oldest</option>
                                <option value="title" {{ $sort == 'title' ? 'selected' : '' }}>Title</option>
                                <option value="views" {{ $sort == 'views' ? 'selected' : '' }}>Views</option>
                            </select>
                        </div>
                    </form>
                </div>

                <!-- Results List -->
                <div class="space-y-4">
                    @forelse($documents as $document)
                        <div class="bg-white shadow rounded-lg p-6 hover:shadow-md transition">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <a href="{{ route('documents.show', $document) }}" class="group">
                                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-indigo-600">
                                            {!! str_ireplace($query, '<mark class="bg-yellow-200">' . $query . '</mark>', $document->title) !!}
                                        </h3>
                                    </a>
                                    <p class="mt-2 text-sm text-gray-600 line-clamp-2">
                                        {!! str_ireplace($query, '<mark class="bg-yellow-200">' . $query . '</mark>', strip_tags($document->excerpt)) !!}
                                    </p>

                                    <div class="mt-3 flex flex-wrap items-center gap-3 text-xs text-gray-500">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $document->category->color }}-100 text-{{ $document->category->color }}-800">
                                            {{ $document->category->icon ?? '📄' }} {{ $document->category->name }}
                                        </span>
                                        <span>by {{ $document->author->name }}</span>
                                        <span>📅 {{ $document->created_at->format('M d, Y') }}</span>
                                        <span>👁️ {{ $document->views_count }} views</span>
                                        <span>⏱️ {{ $document->reading_time }} min read</span>
                                    </div>

                                    @if($document->tags->count() > 0)
                                        <div class="mt-2 flex flex-wrap gap-1">
                                            @foreach($document->tags->take(5) as $tag)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ str_contains(strtolower($tag->name), strtolower($query)) ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-700' }}">
                                                    #{{ $tag->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12 bg-white rounded-lg shadow">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No results found</h3>
                            <p class="mt-1 text-sm text-gray-500">Try adjusting your search terms or filters</p>
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
                        {{ $documents->appends(['q' => $query, 'category' => $categoryId, 'tag' => $tagId, 'sort' => $sort])->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

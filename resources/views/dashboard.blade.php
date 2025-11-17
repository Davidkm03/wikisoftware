@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="py-6">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between">
            <div class="min-w-0 flex-1">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                    Welcome back, {{ auth()->user()->name }}!
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

        <!-- Stats -->
        <div class="mt-8 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                <dt class="truncate text-sm font-medium text-gray-500">Total Documents</dt>
                <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">{{ $stats['total_documents'] }}</dd>
            </div>
            <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                <dt class="truncate text-sm font-medium text-gray-500">Categories</dt>
                <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">{{ $stats['total_categories'] }}</dd>
            </div>
            <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                <dt class="truncate text-sm font-medium text-gray-500">My Documents</dt>
                <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">{{ $stats['my_documents'] }}</dd>
            </div>
            <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                <dt class="truncate text-sm font-medium text-gray-500">My Favorites</dt>
                <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">{{ $stats['my_favorites'] }}</dd>
            </div>
        </div>

        <!-- Categories -->
        <div class="mt-8">
            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Categories</h3>
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5">
                @foreach($categories as $category)
                    <a href="{{ route('categories.show', $category) }}"
                        class="relative rounded-lg border border-gray-300 bg-white px-6 py-5 shadow-sm hover:border-{{ $category->color }}-400 hover:shadow-md transition">
                        <div class="text-center">
                            <div class="text-3xl mb-2">
                                @if($category->icon)
                                    <span>{{ $category->icon === 'academic-cap' ? '🎓' : ($category->icon === 'user-group' ? '👥' : ($category->icon === 'document-text' ? '📄' : ($category->icon === 'book-open' ? '📖' : ($category->icon === 'briefcase' ? '💼' : ($category->icon === 'clipboard-check' ? '✅' : ($category->icon === 'document-duplicate' ? '📋' : ($category->icon === 'calendar' ? '📅' : ($category->icon === 'shield-check' ? '🛡️' : '📁')))))))) }}</span>
                                @endif
                            </div>
                            <h3 class="text-sm font-medium text-gray-900">{{ $category->name }}</h3>
                            <p class="mt-1 text-xs text-gray-500">{{ $category->documents_count }} docs</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Recent Documents -->
        <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-2">
            <!-- Recent -->
            <div>
                <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Recent Documents</h3>
                <div class="space-y-3">
                    @forelse($recentDocuments as $document)
                        <a href="{{ route('documents.show', $document) }}"
                            class="block rounded-lg border border-gray-200 bg-white p-4 hover:border-indigo-400 hover:shadow-md transition">
                            <div class="flex items-start justify-between">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $document->title }}</p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-{{ $document->category->color }}-100 text-{{ $document->category->color }}-800">
                                            {{ $document->category->name }}
                                        </span>
                                        <span class="ml-2">by {{ $document->author->name }}</span>
                                    </p>
                                </div>
                                <span class="text-xs text-gray-400">{{ $document->created_at->diffForHumans() }}</span>
                            </div>
                        </a>
                    @empty
                        <p class="text-sm text-gray-500">No documents yet.</p>
                    @endforelse
                </div>
            </div>

            <!-- Popular -->
            <div>
                <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Popular Documents</h3>
                <div class="space-y-3">
                    @forelse($popularDocuments as $document)
                        <a href="{{ route('documents.show', $document) }}"
                            class="block rounded-lg border border-gray-200 bg-white p-4 hover:border-indigo-400 hover:shadow-md transition">
                            <div class="flex items-start justify-between">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $document->title }}</p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-{{ $document->category->color }}-100 text-{{ $document->category->color }}-800">
                                            {{ $document->category->name }}
                                        </span>
                                    </p>
                                </div>
                                <span class="text-xs text-gray-400">👁️ {{ $document->views_count }}</span>
                            </div>
                        </a>
                    @empty
                        <p class="text-sm text-gray-500">No popular documents yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

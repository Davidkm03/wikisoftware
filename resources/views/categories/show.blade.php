@extends('layouts.app')

@section('title', $category->name)

@section('content')
<div class="py-6">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <!-- Category Header -->
        <div class="mb-8">
            <div class="flex items-center mb-4">
                <span class="text-6xl mr-4">
                    @if($category->icon === 'academic-cap') 🎓
                    @elseif($category->icon === 'user-group') 👥
                    @elseif($category->icon === 'document-text') 📄
                    @elseif($category->icon === 'book-open') 📖
                    @elseif($category->icon === 'briefcase') 💼
                    @elseif($category->icon === 'clipboard-check') ✅
                    @elseif($category->icon === 'document-duplicate') 📋
                    @elseif($category->icon === 'calendar') 📅
                    @elseif($category->icon === 'shield-check') 🛡️
                    @else 📁
                    @endif
                </span>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $category->name }}</h1>
                    <p class="mt-2 text-lg text-gray-600">{{ $category->description }}</p>
                    <p class="mt-1 text-sm text-gray-500">{{ $documents->total() }} document(s) in this category</p>
                </div>
            </div>
        </div>

        <!-- Documents Grid -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
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
                    <p class="text-gray-500">No documents in this category yet.</p>
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

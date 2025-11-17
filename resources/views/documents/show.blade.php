@extends('layouts.app')

@section('title', $document->title)

@section('content')
<div class="py-6">
    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumbs -->
        <x-breadcrumbs :items="[
            ['label' => 'Documents', 'url' => route('documents.index')],
            ['label' => $document->title]
        ]" />

        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between">
            <div class="min-w-0 flex-1">
                <h1 class="text-3xl font-bold text-gray-900">{{ $document->title }}</h1>
                <div class="mt-2 flex items-center text-sm text-gray-500">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $document->category->color }}-100 text-{{ $document->category->color }}-800">
                        {{ $document->category->name }}
                    </span>
                    <span class="ml-3">by {{ $document->author->name }}</span>
                    <span class="ml-3">📅 {{ $document->created_at->format('M d, Y') }}</span>
                    <span class="ml-3">👁️ {{ $document->views_count }} views</span>
                    <span class="ml-3">⏱️ {{ $document->reading_time }} min read</span>
                </div>
            </div>
            <div class="mt-4 flex space-x-3 md:ml-4 md:mt-0">
                <!-- Favorite -->
                <form action="{{ route('favorites.toggle', $document) }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="inline-flex items-center rounded-md {{ $document->isFavoritedBy(auth()->user()) ? 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200' : 'bg-white text-gray-700 hover:bg-gray-50' }} border border-gray-300 px-3 py-2 text-sm font-medium shadow-sm">
                        {{ $document->isFavoritedBy(auth()->user()) ? '⭐ Favorited' : '☆ Favorite' }}
                    </button>
                </form>

                @can('update', $document)
                    <a href="{{ route('documents.edit', $document) }}"
                        class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-medium text-gray-700 shadow-sm border border-gray-300 hover:bg-gray-50">
                        Edit
                    </a>
                @endcan

                @can('delete', $document)
                    <form action="{{ route('documents.destroy', $document) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this document?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="inline-flex items-center rounded-md bg-red-600 px-3 py-2 text-sm font-medium text-white shadow-sm hover:bg-red-500">
                            Delete
                        </button>
                    </form>
                @endcan
            </div>
        </div>

        <!-- Tags -->
        @if($document->tags->count() > 0)
            <div class="mt-4 flex flex-wrap gap-2">
                @foreach($document->tags as $tag)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-700">
                        #{{ $tag->name }}
                    </span>
                @endforeach
            </div>
        @endif

        <!-- Content -->
        <div class="mt-8 bg-white shadow rounded-lg p-8">
            <div class="prose max-w-none">
                {!! $document->content !!}
            </div>
        </div>

        <!-- Attachments -->
        @if($document->attachments->count() > 0 || auth()->user()->canEdit())
            <div class="mt-8 bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">📎 Attachments ({{ $document->attachments->count() }})</h3>

                @if($document->attachments->count() > 0)
                    <div class="space-y-2">
                        @foreach($document->attachments as $attachment)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-md">
                                <div class="flex items-center">
                                    <span class="text-2xl mr-3">
                                        @if($attachment->is_image) 🖼️
                                        @elseif($attachment->is_pdf) 📄
                                        @else 📁
                                        @endif
                                    </span>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $attachment->original_name }}</p>
                                        <p class="text-xs text-gray-500">{{ $attachment->file_size_human }} • Downloaded {{ $attachment->download_count }} times</p>
                                    </div>
                                </div>
                                <div class="flex space-x-2">
                                    <a href="{{ route('attachments.download', $attachment) }}"
                                        class="text-sm text-indigo-600 hover:text-indigo-500">
                                        Download
                                    </a>
                                    @can('update', $document)
                                        <form action="{{ route('attachments.destroy', $attachment) }}" method="POST" onsubmit="return confirm('Delete this file?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm text-red-600 hover:text-red-500">Delete</button>
                                        </form>
                                    @endcan
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                @can('update', $document)
                    <form action="{{ route('attachments.store', $document) }}" method="POST" enctype="multipart/form-data" class="mt-4">
                        @csrf
                        <div class="flex items-center space-x-3">
                            <input type="file" name="file" required
                                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                            <button type="submit"
                                class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                                Upload
                            </button>
                        </div>
                    </form>
                @endcan
            </div>
        @endif

        <!-- Version History -->
        @if($document->versions->count() > 0)
            <div class="mt-8 bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">📚 Version History ({{ $document->versions->count() }})</h3>
                <div class="space-y-2">
                    @foreach($document->versions->take(5) as $version)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-md text-sm">
                            <div>
                                <span class="font-medium">{{ $version->version_label }}</span>
                                <span class="text-gray-500 ml-2">by {{ $version->user->name }}</span>
                                @if($version->change_summary)
                                    <span class="text-gray-500 ml-2">- {{ $version->change_summary }}</span>
                                @endif
                            </div>
                            <span class="text-gray-400">{{ $version->created_at->diffForHumans() }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

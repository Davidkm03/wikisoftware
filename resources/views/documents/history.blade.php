@extends('layouts.app')

@section('title', 'Version History - ' . $document->title)

@section('content')
<div class="py-6">
    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumbs -->
        <x-breadcrumbs :items="[
            ['label' => 'Documents', 'url' => route('documents.index')],
            ['label' => $document->title, 'url' => route('documents.show', $document)],
            ['label' => 'Version History']
        ]" />

        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Version History</h1>
            <p class="mt-2 text-sm text-gray-600">
                <a href="{{ route('documents.show', $document) }}" class="text-indigo-600 hover:text-indigo-900">
                    &larr; Back to {{ $document->title }}
                </a>
            </p>
        </div>

        <!-- Current Version -->
        <div class="mb-6 bg-indigo-50 border border-indigo-200 rounded-lg p-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span class="text-sm font-medium text-indigo-900">
                    Current Version: v{{ $versions->first()->version_number ?? 1 }} - {{ $document->title }}
                </span>
            </div>
        </div>

        <!-- Version Timeline -->
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <ul class="divide-y divide-gray-200">
                @forelse($versions as $version)
                    <li class="px-6 py-4 {{ $loop->first ? 'bg-gray-50' : '' }}">
                        <div class="flex items-start justify-between">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center space-x-3">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $loop->first ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-800' }}">
                                        v{{ $version->version_number }}
                                    </span>
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $version->title }}</h3>
                                </div>

                                @if($version->change_summary)
                                    <p class="mt-2 text-sm text-gray-600">
                                        <strong>Changes:</strong> {{ $version->change_summary }}
                                    </p>
                                @endif

                                <div class="mt-3 flex items-center text-sm text-gray-500 space-x-4">
                                    <span>by {{ $version->user->name }}</span>
                                    <span>📅 {{ $version->created_at->format('M d, Y h:i A') }}</span>
                                    <span>{{ $version->created_at->diffForHumans() }}</span>
                                </div>

                                <!-- Version Preview -->
                                <details class="mt-3">
                                    <summary class="cursor-pointer text-sm text-indigo-600 hover:text-indigo-900">
                                        View content preview
                                    </summary>
                                    <div class="mt-2 p-4 bg-gray-50 rounded-md border border-gray-200 max-h-64 overflow-y-auto">
                                        <div class="prose prose-sm max-w-none">
                                            {!! Str::limit($version->content, 500) !!}
                                        </div>
                                    </div>
                                </details>
                            </div>

                            <!-- Restore Button -->
                            @if(!$loop->first)
                                @can('update', $document)
                                    <div class="ml-6">
                                        <form action="{{ route('documents.restore', [$document, $version->id]) }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to restore this version? The current version will be backed up.');">
                                            @csrf
                                            <button type="submit"
                                                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                                </svg>
                                                Restore
                                            </button>
                                        </form>
                                    </div>
                                @endcan
                            @endif
                        </div>
                    </li>
                @empty
                    <li class="px-6 py-12 text-center text-gray-500">
                        No version history available.
                    </li>
                @endforelse
            </ul>
        </div>

        @if($versions->count() > 0)
            <div class="mt-6 bg-yellow-50 border-l-4 border-yellow-400 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            <strong>Note:</strong> Restoring a version will create a backup of the current version before applying the restoration. All versions are preserved in the history.
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Software Wiki')</title>

    <!-- TailwindCSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Heroicons (for icons) -->
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="h-full" x-data="{ sidebarOpen: false }">
    <div class="min-h-full">
        <!-- Navbar -->
        <nav class="bg-indigo-600">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <h1 class="text-white text-2xl font-bold">📚 Software Wiki</h1>
                        </div>
                        <div class="hidden md:block">
                            <div class="ml-10 flex items-baseline space-x-4">
                                <a href="{{ route('dashboard') }}" class="@if(request()->routeIs('dashboard')) bg-indigo-700 @endif text-white hover:bg-indigo-500 px-3 py-2 rounded-md text-sm font-medium">
                                    Dashboard
                                </a>
                                <a href="{{ route('documents.index') }}" class="@if(request()->routeIs('documents.*')) bg-indigo-700 @endif text-white hover:bg-indigo-500 px-3 py-2 rounded-md text-sm font-medium">
                                    Documents
                                </a>
                                <a href="{{ route('favorites.index') }}" class="@if(request()->routeIs('favorites.*')) bg-indigo-700 @endif text-white hover:bg-indigo-500 px-3 py-2 rounded-md text-sm font-medium">
                                    Favorites
                                </a>
                                @if(auth()->user()->isAdmin())
                                    <div x-data="{ adminOpen: false }" class="relative inline-block text-left">
                                        <button @click="adminOpen = !adminOpen" type="button" class="@if(request()->routeIs('admin.*')) bg-indigo-700 @endif text-white hover:bg-indigo-500 px-3 py-2 rounded-md text-sm font-medium inline-flex items-center">
                                            Admin
                                            <svg class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>
                                        <div x-show="adminOpen" @click.away="adminOpen = false" x-cloak
                                            class="absolute left-0 mt-2 w-48 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 z-50">
                                            <a href="{{ route('admin.users.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Users</a>
                                            <a href="{{ route('admin.categories.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Categories</a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="hidden md:block">
                        <div class="ml-4 flex items-center md:ml-6 space-x-4">
                            <!-- Search -->
                            <form action="{{ route('search') }}" method="GET" class="relative">
                                <input type="text" name="q" placeholder="Search..."
                                    class="w-64 rounded-md border-0 bg-indigo-700 px-4 py-2 text-white placeholder-indigo-300 focus:outline-none focus:ring-2 focus:ring-white">
                            </form>

                            <!-- User menu -->
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" class="flex items-center text-sm text-white focus:outline-none">
                                    <span class="mr-2">{{ auth()->user()->name }}</span>
                                    <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full
                                        @if(auth()->user()->isAdmin()) bg-red-500 @elseif(auth()->user()->isEditor()) bg-green-500 @else bg-blue-500 @endif">
                                        {{ ucfirst(auth()->user()->role) }}
                                    </span>
                                </button>

                                <div x-show="open" @click.away="open = false" x-cloak
                                    class="absolute right-0 mt-2 w-48 rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5">
                                    @if(auth()->user()->isAdmin())
                                        <a href="{{ route('admin.users.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Manage Users
                                        </a>
                                        <a href="{{ route('admin.categories.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Manage Categories
                                        </a>
                                        <div class="border-t border-gray-100"></div>
                                    @endif
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Sign out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile menu button -->
                    <div class="md:hidden">
                        <button @click="sidebarOpen = !sidebarOpen" class="text-white hover:bg-indigo-500 p-2 rounded-md">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile menu -->
            <div x-show="sidebarOpen" x-cloak class="md:hidden">
                <div class="space-y-1 px-2 pb-3 pt-2">
                    <a href="{{ route('dashboard') }}" class="block text-white hover:bg-indigo-500 px-3 py-2 rounded-md text-base font-medium">Dashboard</a>
                    <a href="{{ route('documents.index') }}" class="block text-white hover:bg-indigo-500 px-3 py-2 rounded-md text-base font-medium">Documents</a>
                    <a href="{{ route('favorites.index') }}" class="block text-white hover:bg-indigo-500 px-3 py-2 rounded-md text-base font-medium">Favorites</a>
                </div>
            </div>
        </nav>

        <!-- Flash messages -->
        @if(session('success'))
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 mt-4">
                <div class="rounded-md bg-green-50 p-4">
                    <div class="flex">
                        <div class="text-sm text-green-800">{{ session('success') }}</div>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 mt-4">
                <div class="rounded-md bg-red-50 p-4">
                    <div class="flex">
                        <div class="text-sm text-red-800">{{ session('error') }}</div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Page Content -->
        <main>
            @yield('content')
        </main>
    </div>
</body>
</html>

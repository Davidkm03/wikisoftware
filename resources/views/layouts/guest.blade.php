<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Login - Software Wiki')</title>

    <!-- TailwindCSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-full">
    <div class="min-h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <h1 class="text-center text-4xl font-bold text-indigo-600">📚 Software Wiki</h1>
            <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-gray-900">
                @yield('heading')
            </h2>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                @if(session('error'))
                    <div class="mb-4 rounded-md bg-red-50 p-4">
                        <div class="text-sm text-red-800">{{ session('error') }}</div>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>

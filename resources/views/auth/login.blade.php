@extends('layouts.guest')

@section('title', 'Login')
@section('heading', 'Sign in to your account')

@section('content')
<form method="POST" action="{{ route('login') }}" class="space-y-6">
    @csrf

    <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
        <div class="mt-1">
            <input id="email" name="email" type="email" autocomplete="email" required
                value="{{ old('email') }}"
                class="block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm @error('email') border-red-500 @enderror">
        </div>
        @error('email')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
        <div class="mt-1">
            <input id="password" name="password" type="password" autocomplete="current-password" required
                class="block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm @error('password') border-red-500 @enderror">
        </div>
        @error('password')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <input id="remember" name="remember" type="checkbox"
                class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
            <label for="remember" class="ml-2 block text-sm text-gray-900">Remember me</label>
        </div>

        <div class="text-sm">
            <a href="{{ route('password.request') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                Forgot password?
            </a>
        </div>
    </div>

    <div>
        <button type="submit"
            class="flex w-full justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
            Sign in
        </button>
    </div>

    <div class="text-sm text-center">
        <a href="{{ route('register') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
            Don't have an account? Register
        </a>
    </div>
</form>

<div class="mt-6">
    <div class="relative">
        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-300"></div>
        </div>
        <div class="relative flex justify-center text-sm">
            <span class="bg-white px-2 text-gray-500">Demo Credentials</span>
        </div>
    </div>

    <div class="mt-4 text-xs text-gray-600 space-y-1">
        <p><strong>Admin:</strong> admin@wiki.local / password</p>
        <p><strong>Editor:</strong> editor@wiki.local / password</p>
        <p><strong>Viewer:</strong> viewer@wiki.local / password</p>
    </div>
</div>
@endsection

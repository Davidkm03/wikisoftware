@extends('layouts.guest')

@section('title', 'Forgot Password')
@section('heading', 'Reset your password')

@section('content')
<div class="text-sm text-gray-600 mb-4">
    Forgot your password? No problem. Just let us know your email address and we will email you a password reset link.
</div>

<form method="POST" action="{{ route('password.email') }}" class="space-y-6">
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
        <button type="submit"
            class="flex w-full justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
            Email Password Reset Link
        </button>
    </div>

    <div class="text-sm text-center">
        <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
            Back to login
        </a>
    </div>
</form>
@endsection

@extends('layouts.guest')

@section('title', 'Reset Password')
@section('heading', 'Reset your password')

@section('content')
<form method="POST" action="{{ route('password.update') }}" class="space-y-6">
    @csrf

    <input type="hidden" name="token" value="{{ $token }}">

    <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
        <div class="mt-1">
            <input id="email" name="email" type="email" autocomplete="email" required
                value="{{ old('email', request()->email) }}"
                class="block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm @error('email') border-red-500 @enderror">
        </div>
        @error('email')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
        <div class="mt-1">
            <input id="password" name="password" type="password" autocomplete="new-password" required
                class="block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm @error('password') border-red-500 @enderror">
        </div>
        @error('password')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
        <div class="mt-1">
            <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required
                class="block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
        </div>
    </div>

    <div>
        <button type="submit"
            class="flex w-full justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
            Reset Password
        </button>
    </div>
</form>
@endsection

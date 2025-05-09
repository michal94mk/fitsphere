@extends('layouts.app')

@section('title', __('Too Many Requests'))
@section('body-class', 'bg-gray-100')

@section('body')
<div class="min-h-screen flex flex-col items-center justify-center px-4">
    <div class="w-full max-w-md bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="p-6 sm:p-8">
            <div class="text-center">
                <h1 class="text-9xl font-bold text-blue-500">429</h1>
                <h2 class="mt-4 text-2xl font-bold text-gray-800">{{ __('Too Many Requests') }}</h2>
                <p class="mt-3 text-gray-600">{{ __('Sorry, you have made too many requests recently. Please try again later.') }}</p>
                
                <div class="mt-8">
                    <a href="/" wire:navigate class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium rounded-lg hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-150">
                        <span>{{ __('Back to Home') }}</span>
                        <svg class="ml-2 w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
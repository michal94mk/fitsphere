@extends('layouts.dashboard')

@section('title', 'Panel Trenera')
@section('dashboard-name', config('app.name') . ' Trainer')
@section('mobile-title', 'Panel Trenera')
@section('header-title', 'Panel Trenera')

@section('sidebar-navigation')
    <a href="{{ route('home') }}" class="flex items-center px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white rounded-md">
        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
        </svg>
        Strona główna
    </a>
    
    <a href="{{ route('trainer.reservations') }}" class="flex items-center px-3 py-2 text-sm font-medium {{ request()->routeIs('trainer.reservations') ? 'text-white bg-gray-700' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} rounded-md">
        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
        </svg>
        Rezerwacje
    </a>
    
    <a href="{{ route('profile') }}" class="flex items-center px-3 py-2 text-sm font-medium {{ request()->routeIs('profile') ? 'text-white bg-gray-700' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} rounded-md">
        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
        </svg>
        Mój profil
    </a>
@endsection

@section('mobile-navigation')
    <a href="{{ route('home') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md text-gray-300 hover:bg-gray-700 hover:text-white">
        <svg class="mr-3 h-6 w-6 text-gray-400 group-hover:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
        </svg>
        Strona główna
    </a>
    
    <a href="{{ route('trainer.reservations') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('trainer.reservations') ? 'text-white bg-gray-700' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
        <svg class="mr-3 h-6 w-6 text-gray-400 group-hover:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
        </svg>
        Rezerwacje
    </a>
    
    <a href="{{ route('profile') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('profile') ? 'text-white bg-gray-700' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
        <svg class="mr-3 h-6 w-6 text-gray-400 group-hover:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
        </svg>
        Mój profil
    </a>
@endsection

@section('user-profile')
    <div class="flex items-center">
        <div class="w-8 h-8 rounded-full bg-blue-500 mr-3 flex items-center justify-center text-white font-bold">
            {{ substr(Auth::guard('trainer')->user()->name, 0, 1) }}
        </div>
        <div>
            <p class="text-sm font-medium text-white">{{ Auth::guard('trainer')->user()->name }}</p>
            <p class="text-xs text-gray-400">Trener</p>
        </div>
        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="ml-auto text-gray-400 hover:text-white">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
            </svg>
        </a>
    </div>
@endsection 
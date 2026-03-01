@extends('layouts.public')

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar -->
            <div class="w-full lg:w-1/4">
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 flex flex-col space-y-2 sticky top-24">
                    <div class="pb-6 border-b border-gray-100 mb-4">
                        <p class="text-[10px] font-black uppercase tracking-[3px] text-brand-500 mb-1">Ritual Gallery</p>
                        <h2 class="text-xl font-black text-onyx-900">{{ Auth::user()->name }}</h2>
                    </div>

                    <a href="{{ route('customer.dashboard') }}" 
                       class="flex items-center space-x-3 p-3 rounded-xl transition-all {{ request()->routeIs('customer.dashboard') ? 'bg-brand-600 text-white shadow-lg' : 'text-gray-500 hover:bg-gray-50 hover:text-onyx-900' }}">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                        <span class="text-xs font-bold uppercase tracking-widest">Sanctuary Overview</span>
                    </a>

                    <a href="{{ route('customer.orders') }}" 
                       class="flex items-center space-x-3 p-3 rounded-xl transition-all {{ request()->routeIs('customer.orders') ? 'bg-brand-600 text-white shadow-lg' : 'text-gray-500 hover:bg-gray-50 hover:text-onyx-900' }}">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                        <span class="text-xs font-bold uppercase tracking-widest">Sacred Orders</span>
                    </a>

                    <a href="{{ route('customer.addresses') }}" 
                       class="flex items-center space-x-3 p-3 rounded-xl transition-all {{ request()->routeIs('customer.addresses') ? 'bg-brand-600 text-white shadow-lg' : 'text-gray-500 hover:bg-gray-50 hover:text-onyx-900' }}">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        <span class="text-xs font-bold uppercase tracking-widest">Delivery Registries</span>
                    </a>

                    <a href="{{ route('customer.profile') }}" 
                       class="flex items-center space-x-3 p-3 rounded-xl transition-all {{ request()->routeIs('customer.profile') ? 'bg-brand-600 text-white shadow-lg' : 'text-gray-500 hover:bg-gray-50 hover:text-onyx-900' }}">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        <span class="text-xs font-bold uppercase tracking-widest">Scribe Details</span>
                    </a>

                    <div class="pt-6 mt-4 border-t border-gray-100">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full flex items-center space-x-3 p-3 rounded-xl text-red-500 hover:bg-red-50 transition-all">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                                <span class="text-xs font-bold uppercase tracking-widest">Exit Portal</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="flex-1">
                @yield('customer_content')
            </div>
        </div>
    </div>
</div>
@endsection

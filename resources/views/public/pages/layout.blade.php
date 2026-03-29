@extends('layouts.public')

@section('content')
<div class="bg-white min-h-screen">
    <!-- Header Hero -->
    <div class="bg-gray-50 border-b border-gray-100 py-20 px-4 text-center">
        <h1 class="text-4xl md:text-5xl font-black text-onyx-900 mb-4 uppercase tracking-[6px] animate-in fade-in slide-in-from-bottom-4 duration-700">@yield('page_title')</h1>
        <p class="text-[10px] font-black uppercase tracking-[4px] text-brand-500 opacity-80">Last Updated: {{ date('M d, Y') }}</p>
    </div>

    <!-- Content Area -->
    <div class="container mx-auto px-4 lg:px-8 py-20">
        <div class="max-w-4xl mx-auto">
            <div class="space-y-12 text-gray-700 leading-relaxed font-medium">
                @yield('page_content')
            </div>
            
            <div class="mt-20 pt-10 border-t border-gray-100 flex flex-col md:flex-row items-center justify-between gap-6">
                <p class="text-xs text-gray-400 font-bold uppercase tracking-widest text-center md:text-left leading-relaxed">Questions about our policies?<br>Our sacred concierge is here to help.</p>
                <a href="mailto:support@bhavanicrafts.com" class="px-8 py-3 bg-brand-600 text-white text-[10px] font-black uppercase tracking-[3px] rounded-xl hover:bg-brand-500 transition-all shadow-xl shadow-brand-500/20">Contact Support</a>
            </div>
        </div>
    </div>
</div>
@endsection

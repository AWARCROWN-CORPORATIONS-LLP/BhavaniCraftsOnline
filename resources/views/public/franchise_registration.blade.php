@extends('layouts.public')

@section('meta_title', 'Franchise Opportunity | Bhavani Crafts - Forging Heritage Together')

@section('content')
<div class="min-h-screen bg-white">
    <!-- Hero Section -->
    <section class="relative py-20 lg:py-32 overflow-hidden bg-onyx-950">
        <div class="absolute inset-0 opacity-20" style="background-image: url('https://images.unsplash.com/photo-1590739225287-bd20498ded45?q=80&w=2670&auto=format&fit=crop'); background-size: cover; background-position: center;"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-transparent via-onyx-950/80 to-onyx-950"></div>
        
        <div class="container mx-auto px-4 lg:px-8 relative z-10 text-center">
            <span class="text-[10px] font-black uppercase tracking-[6px] text-brand-400 mb-4 block">Entrepreneurship Opportunity</span>
            <h1 class="text-4xl lg:text-7xl font-serif font-bold text-white mb-8 italic">
                Join the <span class="text-brand-500">Divine Journey</span>
            </h1>
            <p class="max-w-2xl mx-auto text-gray-400 text-lg md:text-xl font-medium leading-relaxed mb-12">
                Empower your entrepreneurial spirit by bringing the specialized craftsmanship of Vijayawada's finest designer puja items, coconut art, and ritual essentials to your city. 
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center space-y-4 sm:space-y-0 sm:space-x-8">
                <a href="#register-form" class="px-10 py-5 bg-brand-500 rounded-2xl text-[11px] font-black uppercase tracking-[4px] text-white hover:bg-brand-600 transition-all transform hover:scale-105 active:scale-95 shadow-2xl shadow-brand-500/20">Apply Now</a>
                <a href="#benefits" class="px-10 py-5 border border-white/20 rounded-2xl text-[11px] font-black uppercase tracking-[4px] text-white hover:bg-white/10 transition-all italic">Explore Benefits</a>
            </div>
        </div>
    </section>

    <!-- Why Bhavani Crafts -->
    <section id="benefits" class="py-24 bg-white">
        <div class="container mx-auto px-4 lg:px-8">
            <div class="text-center mb-20">
                <h2 class="text-3xl lg:text-5xl font-serif font-bold text-onyx-900 mb-4 italic">Why Partner With Us?</h2>
                <div class="h-1 w-20 bg-brand-500 mx-auto"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <div class="p-8 rounded-3xl bg-gray-50 border border-gray-100 hover:border-brand-500/30 transition-all group">
                    <div class="h-16 w-16 bg-white rounded-2xl flex items-center justify-center text-brand-500 shadow-sm mb-8 group-hover:scale-110 transition-transform">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                    </div>
                    <h3 class="text-xl font-black text-onyx-900 mb-4 uppercase tracking-wider">Trusted Since 2017</h3>
                    <p class="text-gray-500 leading-relaxed font-medium">Join a legacy of craftsmanship established in 2017, serving thousands of families with specialty puja and wedding artifacts.</p>
                </div>

                <div class="p-8 rounded-3xl bg-gray-50 border border-gray-100 hover:border-brand-500/30 transition-all group">
                    <div class="h-16 w-16 bg-white rounded-2xl flex items-center justify-center text-brand-500 shadow-sm mb-8 group-hover:scale-110 transition-transform">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                    </div>
                    <h3 class="text-xl font-black text-onyx-900 mb-4 uppercase tracking-wider">High ROI</h3>
                    <p class="text-gray-500 leading-relaxed font-medium">Premium handicrafts with excellent margins and a rapidly growing market for devotional items.</p>
                </div>

                <div class="p-8 rounded-3xl bg-gray-50 border border-gray-100 hover:border-brand-500/30 transition-all group">
                    <div class="h-16 w-16 bg-white rounded-2xl flex items-center justify-center text-brand-500 shadow-sm mb-8 group-hover:scale-110 transition-transform">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.168.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                    </div>
                    <h3 class="text-xl font-black text-onyx-900 mb-4 uppercase tracking-wider">Master Dashboard</h3>
                    <p class="text-gray-500 leading-relaxed font-medium">Full access to our centralized inventory, restock management, and customer CRM systems.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Registration Form -->
    <section id="register-form" class="py-24 bg-gray-50">
        <div class="container mx-auto px-4 lg:px-8 max-w-4xl">
            <div class="bg-white rounded-[40px] shadow-3xl overflow-hidden border border-gray-100 p-8 lg:p-16">
                <div class="text-center mb-12">
                    <h2 class="text-3xl lg:text-4xl font-serif font-bold text-onyx-900 italic">Franchise Application Form</h2>
                    <p class="text-gray-400 mt-4 font-medium uppercase tracking-[3px] text-[10px]">Start your journey here</p>
                </div>

                @if(session('success'))
                    <div class="mb-12 p-6 bg-brand-50 border border-brand-100 rounded-3xl text-center animate-bounceIn">
                        <p class="text-brand-600 font-bold tracking-wider">{{ session('success') }}</p>
                    </div>
                @endif

                <form method="POST" action="{{ route('franchise.store') }}" class="space-y-8">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-500 mb-2 block">Full Name</label>
                            <input type="text" name="full_name" required placeholder="Mandatory" class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-6 py-4 text-sm font-bold text-onyx-900 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all">
                        </div>
                        <div>
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-500 mb-2 block">Email Address</label>
                            <input type="email" name="email" required placeholder="example@mail.com" class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-6 py-4 text-sm font-bold text-onyx-900 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all">
                        </div>
                        <div>
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-500 mb-2 block">Phone Number</label>
                            <input type="tel" name="phone" required placeholder="+91 XXXX XXX XXX" class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-6 py-4 text-sm font-bold text-onyx-900 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all">
                        </div>
                        <div>
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-500 mb-2 block">Planned Location/City</label>
                            <input type="text" name="location" required placeholder="City, State" class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-6 py-4 text-sm font-bold text-onyx-900 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-500 mb-2 block">Brief About Your Experience</label>
                        <textarea name="experience" required rows="4" placeholder="Tell us about yourself..." class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-6 py-4 text-sm font-bold text-onyx-900 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all"></textarea>
                    </div>

                    <div class="p-8 border-2 border-dashed border-gray-100 rounded-3xl">
                         <div class="flex items-start space-x-4">
                             <input type="checkbox" required class="mt-1 h-4 w-4 text-brand-500 rounded border-gray-300 focus:ring-brand-500/20 cursor-pointer">
                             <p class="text-xs text-gray-400 font-medium leading-relaxed">
                                 I understand that submitting this form does not guarantee a franchise. By submitting, I agree to be contacted by Bhavani Crafts Corporate for further vetting and financial disclosure discussions.
                             </p>
                         </div>
                    </div>

                    <button type="submit" class="w-full py-6 bg-brand-500 text-white text-[11px] font-black uppercase tracking-[4px] rounded-2xl shadow-2xl shadow-brand-500/30 hover:bg-brand-600 hover:scale-105 active:scale-95 transition-all">Submit Application</button>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection

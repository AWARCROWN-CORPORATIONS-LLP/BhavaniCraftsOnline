@extends('layouts.admin')

@section('header_extra')
    <div class="flex items-center space-x-4">
        <h2 class="text-xl lg:text-3xl font-black text-gray-900 uppercase tracking-tighter">Mass Email Hub</h2>
        <span class="bg-brand-50 text-brand-600 text-[9px] font-black uppercase tracking-[3px] px-4 py-1.5 rounded-full border border-brand-100 italic">Global Broadcast</span>
    </div>
@endsection

@section('content')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Composer Side -->
        <div class="lg:col-span-2">
            <div class="card-premium p-8">
                <div class="mb-8 border-b border-gray-100 pb-6">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-[6px] mb-2 leading-none">Draft Your Message</h3>
                    <p class="text-[10px] text-gray-300 font-bold uppercase tracking-widest leading-none">Send a beautiful mail to everyone in your registry.</p>
                </div>

                <form action="{{ route('admin.bulk-email.send') }}" method="POST" onsubmit="return confirm('Ready to send this mail to all users and subscribers?');">
                    @csrf
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[3px] mb-3">Email Subject / Title</label>
                            <input type="text" name="subject" required 
                                   class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-[12px] font-black text-gray-900 uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-brand-500/10 focus:border-brand-500 transition-all shadow-inner"
                                   placeholder="EX: HOLI SPECIAL OFFERS OR NEW ARRIVALS">
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[3px] mb-3">Message Content</label>
                            <textarea name="content" rows="12" required
                                      class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-[13px] font-medium text-gray-700 leading-relaxed focus:outline-none focus:ring-2 focus:ring-brand-500/10 focus:border-brand-500 transition-all shadow-inner"
                                   placeholder="Write your message here in simple English..."></textarea>
                        </div>

                        <div class="pt-6">
                            <button type="submit" 
                                    class="w-full py-5 bg-brand-600 text-white text-[11px] font-black uppercase tracking-[4px] rounded-2xl hover:bg-brand-500 transition-all shadow-xl shadow-brand-900/20 transform hover:-translate-y-1 active:translate-y-0 duration-300">
                                Send Broadcast to All
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Audience Info Side -->
        <div class="space-y-8">
            <div class="card-premium p-8 bg-brand-900 text-white border-none shadow-2xl shadow-brand-900/40 relative overflow-hidden">
                <div class="absolute -right-8 -bottom-8 opacity-10">
                    <svg class="h-48 w-48 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                </div>
                <div class="relative z-10">
                    <h3 class="text-[10px] font-black text-white/40 uppercase tracking-[4px] mb-8 leading-none italic">Target Audience</h3>
                    
                    <div class="space-y-6">
                        <div class="flex items-end justify-between border-b border-white/10 pb-4">
                            <div>
                                <p class="text-[9px] font-black uppercase tracking-widest text-white/60 mb-1">Registered Users</p>
                                <h4 class="text-3xl font-black tracking-tighter">{{ $userCount }}</h4>
                            </div>
                            <span class="text-[8px] font-bold uppercase text-white/30 mb-1">Seekers</span>
                        </div>

                        <div class="flex items-end justify-between border-b border-white/10 pb-4">
                            <div>
                                <p class="text-[9px] font-black uppercase tracking-widest text-white/60 mb-1">Active Subscribers</p>
                                <h4 class="text-3xl font-black tracking-tighter">{{ $subscriberCount }}</h4>
                            </div>
                            <span class="text-[8px] font-bold uppercase text-white/30 mb-1">Retained</span>
                        </div>

                        <div class="pt-4">
                            <p class="text-[11px] text-white/80 font-bold leading-relaxed italic">
                                Total unique recipients will be calculated during sending to prevent duplicate mails.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-premium p-8 border-brand-100 bg-brand-50/30">
                <h3 class="text-[11px] font-black text-brand-800 uppercase tracking-[3px] mb-4">Guidelines</h3>
                <ul class="space-y-4">
                    <li class="flex items-start space-x-3">
                        <div class="h-5 w-5 rounded-full bg-brand-200 flex items-center justify-center text-brand-600 text-[10px] font-black">1</div>
                        <p class="text-[11px] text-brand-900/60 font-bold leading-none mt-1">Use simple English words.</p>
                    </li>
                    <li class="flex items-start space-x-3">
                        <div class="h-5 w-5 rounded-full bg-brand-200 flex items-center justify-center text-brand-600 text-[10px] font-black">2</div>
                        <p class="text-[11px] text-brand-900/60 font-bold leading-none mt-1">Focus on festival offers.</p>
                    </li>
                    <li class="flex items-start space-x-3">
                        <div class="h-5 w-5 rounded-full bg-brand-200 flex items-center justify-center text-brand-600 text-[10px] font-black">3</div>
                        <p class="text-[11px] text-brand-900/60 font-bold leading-none mt-1">Announce new ritual items.</p>
                    </li>
                </ul>
            </div>
        </div>
    </div>

@endsection

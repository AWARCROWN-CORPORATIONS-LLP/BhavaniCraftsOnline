@extends('customer.layout')

@section('customer_content')
<div class="space-y-8 animate-fadeIn">
    <!-- Welcome Header -->
    <div class="bg-onyx-900 rounded-[2rem] p-10 text-white relative overflow-hidden shadow-2xl">
        <div class="relative z-10">
            <h2 class="text-3xl font-black mb-2 italic">Welcome back, {{ Auth::user()->name }}</h2>
            <p class="text-white/60 text-sm font-medium">Continue your sacred craft journey.</p>
        </div>
        <!-- Decorative element -->
        <div class="absolute -right-10 -bottom-10 h-64 w-64 bg-brand-500/10 rounded-full blur-3xl"></div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 group hover:border-brand-500/30 transition-all duration-500">
            <div class="h-12 w-12 bg-gray-50 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-500">
                <svg class="h-6 w-6 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
            </div>
            <p class="text-[10px] font-black uppercase tracking-[2px] text-gray-400 mb-1">Total Orders</p>
            <h3 class="text-2xl font-black text-onyx-900">{{ App\Models\Order::where('user_id', Auth::id())->count() }}</h3>
        </div>
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 group hover:border-brand-500/30 transition-all duration-500">
            <div class="h-12 w-12 bg-gray-50 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-500">
                <svg class="h-6 w-6 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
            </div>
            <p class="text-[10px] font-black uppercase tracking-[2px] text-gray-400 mb-1">Sacred Wishlist</p>
            <h3 class="text-2xl font-black text-onyx-900">{{ App\Models\Wishlist::where('user_id', Auth::id())->count() }}</h3>
        </div>
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 group hover:border-brand-500/30 transition-all duration-500">
            <div class="h-12 w-12 bg-gray-50 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-500">
                <svg class="h-6 w-6 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
            </div>
            <p class="text-[10px] font-black uppercase tracking-[2px] text-gray-400 mb-1">Saved Addresses</p>
            <h3 class="text-2xl font-black text-onyx-900">{{ Auth::user()->addresses()->count() }}</h3>
        </div>
    </div>

    <!-- Collection Sharing Section -->
    <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100 relative overflow-hidden group">
        <div class="absolute -right-20 -top-20 h-64 w-64 bg-brand-50/50 rounded-full blur-3xl group-hover:bg-brand-100/50 transition-colors duration-[1.5s]"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
            <div class="max-w-xl">
                <div class="flex items-center space-x-3 mb-4">
                    <span class="h-10 w-10 bg-brand-50 text-brand-500 rounded-xl flex items-center justify-center">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" /></svg>
                    </span>
                    <h3 class="text-lg font-black text-onyx-900 uppercase tracking-widest italic">Sacred Collection Sharing</h3>
                </div>
                <p class="text-sm text-gray-400 font-medium leading-relaxed">
                    Planning a ceremony or wedding? Generate a divine link to share your curated artifacts with family and friends via WhatsApp or Email.
                </p>
                
                @if(Auth::user()->wishlist_public)
                <div class="mt-6 p-4 bg-gray-50 rounded-2xl border border-dashed border-gray-200 flex items-center justify-between group/link">
                    <div class="flex flex-col">
                        <span class="text-[8px] font-black uppercase tracking-widest text-gray-400 mb-1">Your Registry Link</span>
                        <code class="text-[10px] font-black text-brand-500 break-all pr-4">{{ route('collection.show', Auth::user()->wishlist_token) }}</code>
                    </div>
                    <button onclick="navigator.clipboard.writeText('{{ route('collection.show', Auth::user()->wishlist_token) }}'); alert('Link copied to clipboard!');" 
                            class="shrink-0 px-4 py-2 bg-white rounded-lg text-[10px] font-black uppercase tracking-widest text-onyx-900 hover:text-brand-500 shadow-sm border border-gray-100 transition-all">
                        Copy Link
                    </button>
                </div>
                
                <div class="mt-6 flex items-center space-x-4">
                    <!-- WhatsApp Share -->
                    <a href="https://wa.me/?text={{ urlencode('Discover my curated selection of divine artifacts from Bhavani Crafts: ' . route('collection.show', Auth::user()->wishlist_token)) }}" 
                       target="_blank"
                       class="flex items-center space-x-2 px-5 py-3 bg-[#25D366] text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:shadow-lg transition-all transform hover:-translate-y-1">
                        <svg class="h-4 w-4 fill-current" viewBox="0 0 24 24" style="width:16px;height:16px;"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        <span>WhatsApp Share</span>
                    </a>
                    
                    <!-- Email Share -->
                    <a href="mailto:?subject={{ urlencode('My Curated Divine Artifacts Registry') }}&body={{ urlencode('I wanted to share my selection of artifacts from Bhavani Crafts with you: ' . route('collection.show', Auth::user()->wishlist_token)) }}" 
                       class="flex items-center space-x-2 px-5 py-3 bg-onyx-900 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:shadow-lg transition-all transform hover:-translate-y-1">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                        <span>Email Registry</span>
                    </a>
                </div>
                @endif
            </div>

            <form action="{{ route('customer.wishlist.toggle_sharing') }}" method="POST">
                @csrf
                <button type="submit" 
                        class="px-8 py-4 {{ Auth::user()->wishlist_public ? 'bg-onyx-900 border-onyx-900' : 'bg-brand-500 border-brand-500 shadow-brand-500/20 shadow-lg' }} border text-white text-[11px] font-black uppercase tracking-[3px] rounded-2xl transition-all shadow-xl hover:shadow-brand-500/30">
                    {{ Auth::user()->wishlist_public ? 'Make Collection Private' : 'Enable Public Link' }}
                </button>
            </form>
        </div>

    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden mb-12">
        <div class="px-8 py-6 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-sm font-black uppercase tracking-widest text-onyx-900">Recent Divine Orders</h3>
            <a href="{{ route('customer.orders') }}" class="text-[10px] font-black uppercase tracking-widest text-brand-500 hover:text-onyx-900 transition-colors">View All Orders</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-8 py-4 text-[10px] font-black uppercase tracking-widest text-gray-400">Order ID</th>
                        <th class="px-8 py-4 text-[10px] font-black uppercase tracking-widest text-gray-400">Date</th>
                        <th class="px-8 py-4 text-[10px] font-black uppercase tracking-widest text-gray-400">Status</th>
                        <th class="px-8 py-4 text-[10px] font-black uppercase tracking-widest text-gray-400 text-right">Sanctuary Value</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($recentOrders as $order)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-8 py-6">
                            <span class="text-sm font-bold text-onyx-900">#{{ $order->order_id_string }}</span>
                        </td>
                        <td class="px-8 py-6 text-sm font-medium text-gray-500">
                            {{ $order->ordered_date ? $order->ordered_date->format('M d, Y') : 'N/A' }}
                        </td>
                        <td class="px-8 py-6">
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-[10px] font-black uppercase tracking-widest rounded-full">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="px-8 py-6 text-right text-sm font-black text-onyx-900">
                            ₹{{ number_format($order->total_amount, 2) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="h-10 w-10 text-gray-200 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                                <p class="text-sm font-bold text-gray-400 uppercase tracking-widest italic">No rituals ordered yet.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

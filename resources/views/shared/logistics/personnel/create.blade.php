@extends(\Illuminate\Support\Facades\Auth::user()->hasRole('employee') ? 'layouts.employee' : 'layouts.admin')

@section('header_extra')
    <h2 class="text-xl lg:text-3xl font-black text-gray-900 uppercase tracking-tighter">New Logistics Personnel Registry</h2>
@endsection

@section('content')

    <form action="{{ route('shared.logistics.personnel.store') }}" method="POST" class="card-premium">
        @csrf
        
        <div class="p-8 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
            <div>
                <h3 class="text-xs font-black text-gray-400 uppercase tracking-[6px] mb-2 leading-none">Access Generation</h3>
                <p class="text-2xl font-light text-gray-600">Register field logistics operative</p>
            </div>
            <div class="h-12 w-12 bg-[#ff9933]/10 text-[#ff9933] rounded-2xl flex items-center justify-center">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
            </div>
        </div>

        <div class="p-8 lg:p-12">
            <div class="max-w-3xl space-y-10">
                <!-- Name -->
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[3px] mb-3">Operative Callsign / Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#ff9933] focus:ring-0 transition-colors shadow-sm" placeholder="e.g. Ramesh Logistix">
                </div>

                <!-- Contact Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[3px] mb-3">Official Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#ff9933] focus:ring-0 transition-colors shadow-sm" placeholder="runner@bhavanicrafts.com">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[3px] mb-3">Field Contact Number</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" required class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#ff9933] focus:ring-0 transition-colors shadow-sm" placeholder="+91 999 999 9999">
                    </div>
                </div>

                <!-- Access Key -->
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[3px] mb-3">Initial Access Vault Key (Password)</label>
                    <input type="password" name="password" required class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#ff9933] focus:ring-0 transition-colors shadow-sm" placeholder="••••••••">
                    <p class="mt-2 text-[9px] text-gray-400 font-bold uppercase tracking-widest">Minimum 8 characters. Strict confidentiality required.</p>
                </div>

                <div class="pt-10 border-t border-gray-100">
                    <button type="submit" class="btn-luxury-saffron w-full md:w-auto px-12 py-4 shadow-xl text-xs flex items-center justify-center space-x-4">
                        <span>Mint Field Agent Access</span>
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                    </button>
                </div>
            </div>
        </div>
    </form>

@endsection

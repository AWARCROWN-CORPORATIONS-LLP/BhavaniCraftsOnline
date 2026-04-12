@extends('layouts.admin')

@section('header_extra')
    <h2 class="text-xl lg:text-3xl font-black text-gray-900 uppercase tracking-tighter">Add New Staff</h2>
@endsection

@section('content')

    <form action="{{ route('superadmin.employees.store') }}" method="POST" class="card-premium">
        @csrf
        
        <div class="p-8 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
            <div>
                <h3 class="text-xs font-black text-gray-400 uppercase tracking-[6px] mb-2 leading-none">Staff Details</h3>
+                <p class="text-2xl font-light text-gray-600">Enter employee information</p>
            </div>
            <div class="h-12 w-12 bg-[#ff9933]/10 text-[#ff9933] rounded-2xl flex items-center justify-center">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354l.586.586H19v10.354L12.586 16H4V4.94L11.414 4H12zM12 11h.01" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11a1 1 0 100-2 1 1 0 000 2z" /></svg>
            </div>
        </div>

        <div class="p-8 lg:p-12">
            <div class="max-w-3xl space-y-10">
                <!-- Name -->
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[3px] mb-3">Full Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#ff9933] focus:ring-0 transition-colors shadow-sm" placeholder="e.g. Aditi Sharma">
                </div>

                <!-- Contact Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[3px] mb-3">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}" required class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#ff9933] focus:ring-0 transition-colors shadow-sm" placeholder="employee@bhavanicrafts.com">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[3px] mb-3">Phone Number</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" required class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#ff9933] focus:ring-0 transition-colors shadow-sm" placeholder="+91 999 999 9999">
                    </div>
                </div>

                <!-- Role Selection -->
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[3px] mb-3">Access Tier / Role</label>
                    <select name="role" required class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#ff9933] focus:ring-0 transition-colors shadow-sm appearance-none">
                        <option value="employee">Employee (Standard Access)</option>
                        <option value="associate_admin">Associate Admin (Elevated Access, No Revenue)</option>
                    </select>
                    <p class="mt-2 text-[9px] text-gray-400 font-bold uppercase tracking-widest">Controls visibility of financial data and elevated settings.</p>
                </div>

                <!-- Access Key -->
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[3px] mb-3">Set Password</label>
                    <input type="password" name="password" required class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#ff9933] focus:ring-0 transition-colors shadow-sm" placeholder="••••••••">
                    <p class="mt-2 text-[9px] text-gray-400 font-bold uppercase tracking-widest">Minimum 8 characters. Strict confidentiality required.</p>
                </div>

                <div class="pt-10 border-t border-gray-100">
                    <button type="submit" class="btn-luxury-saffron w-full md:w-auto px-12 py-4 shadow-xl text-xs flex items-center justify-center space-x-4">
                        <span>Save Staff Member</span>
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                    </button>
                </div>
            </div>
        </div>
    </form>

@endsection

@extends('customer.layout')

@section('customer_content')
<div class="space-y-8 animate-fadeIn">
    <div class="bg-white rounded-[2rem] p-10 shadow-sm border border-gray-100">
        <div class="mb-10">
            <h2 class="text-2xl font-black text-onyx-900 uppercase tracking-widest">Your Profile</h2>
            <p class="text-sm text-gray-400 font-medium">Manage your account details and password.</p>
        </div>

        <form action="{{ route('customer.profile.update') }}" method="POST" class="space-y-8">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 border-b border-gray-100 pb-10">
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-[2px] text-onyx-900 ml-1">Full Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl text-sm font-bold text-onyx-900 focus:ring-2 focus:ring-brand-500/20 transition-all placeholder-gray-300">
                    @error('name') <p class="text-red-500 text-[10px] font-bold mt-1 uppercase">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-[2px] text-onyx-900 ml-1">Email Address</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                           class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl text-sm font-bold text-onyx-900 focus:ring-2 focus:ring-brand-500/20 transition-all placeholder-gray-300">
                    @error('email') <p class="text-red-500 text-[10px] font-bold mt-1 uppercase">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-[2px] text-onyx-900 ml-1">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" required
                           class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl text-sm font-bold text-onyx-900 focus:ring-2 focus:ring-brand-500/20 transition-all placeholder-gray-300">
                    @error('phone') <p class="text-red-500 text-[10px] font-bold mt-1 uppercase">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="pt-4">
                <h3 class="text-sm font-black text-onyx-900 uppercase tracking-widest mb-6 italic">Change Password</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-[2px] text-onyx-900 ml-1">Current Password</label>
                        <input type="password" name="current_password"
                               class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl text-sm font-bold text-onyx-900 focus:ring-2 focus:ring-brand-500/20 transition-all placeholder-gray-300">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-[2px] text-onyx-900 ml-1">New Password</label>
                        <input type="password" name="new_password"
                               class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl text-sm font-bold text-onyx-900 focus:ring-2 focus:ring-brand-500/20 transition-all placeholder-gray-300">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-[2px] text-onyx-900 ml-1">Confirm New Password</label>
                        <input type="password" name="new_password_confirmation"
                               class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl text-sm font-bold text-onyx-900 focus:ring-2 focus:ring-brand-500/20 transition-all placeholder-gray-300">
                    </div>
                </div>
                @error('current_password') <p class="text-red-500 text-[10px] font-bold mt-2 uppercase">{{ $message }}</p> @enderror
                @error('new_password') <p class="text-red-500 text-[10px] font-bold mt-2 uppercase">{{ $message }}</p> @enderror
            </div>

            <div class="pt-10 flex justify-end">
                <button type="submit" class="px-12 py-4 bg-brand-600 text-white text-[11px] font-black uppercase tracking-[4px] rounded-2xl hover:bg-brand-500 transition-all duration-500 shadow-xl shadow-brand-900/10 hover:shadow-brand-500/30">
                    Update Profile
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

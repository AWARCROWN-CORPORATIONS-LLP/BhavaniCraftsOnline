@extends('layouts.employee')

@section('header_extra')
    <div class="flex items-center space-x-4">
        <h2 class="text-xl lg:text-3xl font-black text-gray-900 uppercase tracking-tighter">New Broadcast</h2>
        <span class="text-gray-300">/</span>
        <p class="text-[10px] items-center font-black text-sky-500 uppercase tracking-[4px]">Internal Communications</p>
    </div>
@endsection

@section('content')

    <div class="max-w-3xl mx-auto">
        <form action="{{ route('employee.broadcasts.store') }}" method="POST" class="card-premium p-10 space-y-10 focus-within:ring-2 focus-within:ring-sky-50 transition-all duration-300">
            @csrf

            <div class="space-y-6">
                <div class="flex items-center space-x-4">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-[6px] leading-none">Message Content</h3>
                    <div class="flex-grow h-px bg-gray-100"></div>
                </div>

                <div class="space-y-6">
                    <div>
                        <label class="text-[10px] font-black text-gray-600 tracking-[3px] uppercase mb-4 block leading-none">Header / Title *</label>
                        <input type="text" name="title" required value="{{ old('title') }}"
                               placeholder="e.g. Server Maintenance Notice"
                               class="w-full bg-gray-50 border-none px-6 py-5 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-sky-500/30">
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-gray-600 tracking-[3px] uppercase mb-4 block leading-none">Information / Body *</label>
                        <textarea name="message" required rows="6"
                                  placeholder="Provide detailed instructions or updates to the staff..."
                                  class="w-full bg-gray-50 border-none px-6 py-5 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-sky-500/30">{{ old('message') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="flex items-center space-x-4">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-[6px] leading-none">Visibility Options</h3>
                    <div class="flex-grow h-px bg-gray-100"></div>
                </div>

                <label class="flex items-center space-x-4 p-6 bg-gray-50 rounded-2xl border border-transparent hover:border-sky-500/20 transition-all cursor-pointer group">
                    <input type="checkbox" name="is_active" value="1" checked class="h-5 w-5 text-sky-500 rounded border-gray-100 focus:ring-sky-500/30">
                    <div>
                        <span class="text-xs font-black text-gray-900 uppercase tracking-widest block mb-1">Schedule Live</span>
                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Publish this message immediately after saving.</span>
                    </div>
                </label>
            </div>

            <div class="pt-6 flex items-center justify-between border-t border-gray-50">
                <a href="{{ route('employee.broadcasts.index') }}" class="text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-red-500 transition-colors">← Cancel</a>
                <button type="submit" class="btn-luxury px-10 py-5 text-[11px] shadow-lg">Save Broadcast</button>
            </div>
        </form>
    </div>

@endsection

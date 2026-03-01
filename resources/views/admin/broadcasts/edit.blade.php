@extends('layouts.admin')

@section('header_extra')
    <div class="flex items-center space-x-4">
        <h2 class="text-xl lg:text-3xl font-black text-gray-900 uppercase tracking-tighter">Edit Transmission</h2>
        <span class="text-gray-300">/</span>
        <p class="text-[10px] items-center font-black text-[#ff9933] uppercase tracking-[4px]">Global Broadcast</p>
    </div>
@endsection

@section('content')

    <div class="max-w-3xl mx-auto">
        <form action="{{ route('admin.broadcasts.update', $broadcast->id) }}" method="POST" class="card-premium p-12 space-y-12">
            @csrf
            @method('PUT')

            <div class="space-y-8">
                <div class="flex items-center space-x-6">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-[6px] leading-none">Transmission Payload</h3>
                    <div class="flex-grow h-[1px] bg-gray-100"></div>
                </div>

                <div>
                    <label class="text-[10px] font-black text-gray-600 tracking-[3px] uppercase mb-4 block ml-1 leading-none">Headline Identity</label>
                    <input type="text" name="title" required value="{{ $broadcast->title }}" class="w-full bg-gray-50 border-none px-8 py-5 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-[#ff9933]/20 transition-all">
                </div>

                <div>
                    <label class="text-[10px] font-black text-gray-600 tracking-[3px] uppercase mb-4 block ml-1 leading-none">Full Notification Directive</label>
                    <textarea name="message" required rows="6" class="w-full bg-gray-50 border-none px-8 py-5 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-[#ff9933]/20 transition-all">{{ $broadcast->message }}</textarea>
                </div>
            </div>

            <div class="space-y-8">
                <div class="flex items-center space-x-6">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-[6px] leading-none">Routing & Priority</h3>
                    <div class="flex-grow h-[1px] bg-gray-100"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="text-[10px] font-black text-gray-600 tracking-[3px] uppercase mb-4 block ml-1 leading-none">Target Frequency</label>
                        <select name="target_audience" required class="w-full bg-gray-50 border-none px-8 py-5 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-[#ff9933]/20 transition-all">
                            <option value="all" {{ $broadcast->target_audience == 'all' ? 'selected' : '' }}>Global Network (All Systems)</option>
                            <option value="exact:employee" {{ $broadcast->target_audience == 'exact:employee' ? 'selected' : '' }}>Internal Employees Only</option>
                            <option value="exact:franchise" {{ $broadcast->target_audience == 'exact:franchise' ? 'selected' : '' }}>Franchise Partners Only</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-gray-600 tracking-[3px] uppercase mb-4 block ml-1 leading-none">Threat/Urgency Level</label>
                        <select name="urgency" required class="w-full bg-gray-50 border-none px-8 py-5 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-[#ff9933]/20 transition-all">
                            <option value="info" class="text-blue-600 font-bold" {{ $broadcast->urgency == 'info' ? 'selected' : '' }}>Information</option>
                            <option value="warning" class="text-amber-600 font-bold" {{ $broadcast->urgency == 'warning' ? 'selected' : '' }}>Operational Warning</option>
                            <option value="critical" class="text-red-600 font-bold" {{ $broadcast->urgency == 'critical' ? 'selected' : '' }}>Critical Alert</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="pt-10 flex items-center justify-between">
                <a href="{{ route('admin.broadcasts.index') }}" class="text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-red-500 transition-colors">Discard Revisions</a>
                <button type="submit" class="btn-luxury-saffron px-12 py-5 text-[11px] shadow-2xl">Confirm Broadcast Update</button>
            </div>
        </form>
    </div>

@endsection

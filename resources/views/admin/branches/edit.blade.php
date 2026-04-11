@extends('layouts.admin')

@section('header_extra')
    <div class="flex items-center space-x-4">
        <h2 class="text-xl lg:text-3xl font-black text-gray-900 uppercase tracking-tighter">Edit Branch</h2>
        <span class="text-gray-300">/</span>
        <p class="text-[10px] font-black text-[#ff9933] uppercase tracking-[4px]">Modify Registry Node</p>
    </div>
@endsection

@section('content')

    <div class="max-w-4xl mx-auto">
        <form action="{{ route('admin.branches.update', $branch->id) }}" method="POST" class="card-premium p-10 space-y-10">
            @csrf
            @method('PUT')

            @if($errors->any())
                <div class="p-4 bg-red-50 border border-red-100 rounded-2xl text-red-600 text-sm font-semibold">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <!-- Left: Branch Identity & Contact -->
                <div class="space-y-8">
                    <div class="flex items-center space-x-4">
                        <h3 class="text-xs font-black text-gray-400 uppercase tracking-[6px] leading-none">Identity & Contact</h3>
                        <div class="flex-grow h-px bg-gray-100"></div>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label class="text-[10px] font-black text-gray-600 tracking-[3px] uppercase mb-3 block leading-none">Branch Name *</label>
                            <input type="text" name="name" required value="{{ old('name', $branch->name) }}" placeholder="e.g. Hyderabad Flagship"
                                   class="w-full bg-gray-50 border-none px-6 py-4 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-[#ff9933]/30">
                        </div>

                        <div>
                            <label class="text-[10px] font-black text-gray-600 tracking-[3px] uppercase mb-3 block leading-none">City</label>
                            <input type="text" name="city" value="{{ old('city', $branch->city) }}" placeholder="e.g. Hyderabad"
                                   class="w-full bg-gray-50 border-none px-6 py-4 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-[#ff9933]/30">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="text-[10px] font-black text-gray-600 tracking-[3px] uppercase mb-3 block leading-none">Phone</label>
                                <input type="text" name="phone" value="{{ old('phone', $branch->phone) }}" placeholder="+91 ..."
                                       class="w-full bg-gray-50 border-none px-6 py-4 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-[#ff9933]/30">
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-gray-600 tracking-[3px] uppercase mb-3 block leading-none">Email</label>
                                <input type="email" name="email" value="{{ old('email', $branch->email) }}" placeholder="branch@bhavanicrafts.com"
                                       class="w-full bg-gray-50 border-none px-6 py-4 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-[#ff9933]/30">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Logistics & Location -->
                <div class="space-y-8">
                    <div class="flex items-center space-x-4">
                        <h3 class="text-xs font-black text-gray-400 uppercase tracking-[6px] leading-none">Logistics & Location</h3>
                        <div class="flex-grow h-px bg-gray-100"></div>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label class="text-[10px] font-black text-gray-600 tracking-[3px] uppercase mb-3 block leading-none">Complete Address</label>
                            <textarea name="address" rows="3" placeholder="Street, Building, Area, Pin Code..."
                                      class="w-full bg-gray-50 border-none px-6 py-4 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-[#ff9933]/30">{{ old('address', $branch->address) }}</textarea>
                        </div>

                        <div>
                            <label class="text-[10px] font-black text-gray-600 tracking-[3px] uppercase mb-3 block leading-none">Google Maps Link</label>
                            <input type="url" name="map_link" value="{{ old('map_link', $branch->map_link) }}" placeholder="https://goo.gl/maps/..."
                                   class="w-full bg-gray-50 border-none px-6 py-4 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-[#ff9933]/30">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-4">
                            <div>
                                <label class="text-[10px] font-black text-gray-600 tracking-[3px] uppercase mb-3 block leading-none">Sort Order</label>
                                <input type="number" name="sort_order" value="{{ old('sort_order', $branch->sort_order) }}"
                                       class="w-full bg-gray-50 border-none px-6 py-4 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-[#ff9933]/30">
                            </div>
                            <div class="flex flex-col justify-center translate-y-3">
                                <label class="flex items-center space-x-3 cursor-pointer group">
                                    <div class="relative">
                                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $branch->is_active) ? 'checked' : '' }} class="sr-only peer">
                                        <div class="w-10 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[#ff9933]/20 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#ff9933]"></div>
                                    </div>
                                    <span class="text-[10px] font-black text-gray-500 uppercase tracking-widest group-hover:text-[#ff9933] transition-colors">Visible in Footer</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="pt-8 flex items-center justify-between border-t border-gray-50">
                <a href="{{ route('admin.branches.index') }}" class="text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-red-500 transition-colors">← Exit Modification</a>
                <button type="submit" class="btn-luxury-saffron px-12 py-5 text-[11px] shadow-lg">Authenticate & Update Registry</button>
            </div>
        </form>
    </div>

@endsection

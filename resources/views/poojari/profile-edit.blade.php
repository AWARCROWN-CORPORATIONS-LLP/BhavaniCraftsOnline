@extends('layouts.public')

@section('title', 'Refine Your Sacred Profile | Bhavani Crafts')

@section('content')
<div class="bg-gray-50 min-h-screen pt-32 pb-20">
    <div class="container mx-auto px-6 max-w-4xl">
        <div class="flex items-center space-x-4 mb-12">
            <a wire:navigate href="{{ route('poojari.dashboard') }}" class="h-12 w-12 bg-white rounded-full flex items-center justify-center hover:bg-brand-500 hover:text-white transition-all shadow-sm">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M15 19l-7-7 7-7"/></svg>
            </a>
            <h1 class="text-3xl md:text-4xl font-serif font-bold text-onyx-900 leading-none">Your Sacred Practitioner Profile</h1>
        </div>

        <div class="bg-white rounded-[3.5rem] p-12 lg:p-16 shadow-2xl shadow-brand-500/5 relative overflow-hidden border border-gray-100">
            <form action="{{ route('poojari.profile.update') }}" method="POST" class="space-y-12">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                    <div class="space-y-4">
                        <label class="text-[10px] font-black uppercase tracking-[3px] text-brand-500 px-4">Years of Divine Experience</label>
                        <input type="number" name="experience_years" required min="0" value="{{ old('experience_years', $profile->experience_years) }}"
                               class="w-full bg-gray-50 border-none rounded-full py-4 px-8 text-onyx-900 focus:ring-4 focus:ring-brand-500/10 transition-all font-bold">
                    </div>
                    <div class="space-y-4">
                        <label class="text-[10px] font-black uppercase tracking-[3px] text-brand-500 px-4">Specialization (Comma Separated)</label>
                        <input type="text" name="specializations" value="{{ old('specializations', $profile->specializations) }}" placeholder="e.g., Marriage, Vastu, Shanti Path"
                               class="w-full bg-gray-50 border-none rounded-full py-4 px-8 text-onyx-900 focus:ring-4 focus:ring-brand-500/10 transition-all font-bold">
                    </div>
                </div>

                <div class="space-y-4">
                    <label class="text-[10px] font-black uppercase tracking-[3px] text-brand-500 px-4">Serving Region / Location</label>
                    <input type="text" name="location" value="{{ old('location', $profile->location) }}" placeholder="e.g., Delhi, Noida, Gurgaon"
                           class="w-full bg-gray-50 border-none rounded-full py-4 px-8 text-onyx-900 focus:ring-4 focus:ring-brand-500/10 transition-all font-bold">
                </div>

                <div class="space-y-4">
                    <label class="text-[10px] font-black uppercase tracking-[3px] text-brand-500 px-4">Biography / Spiritual Journey</label>
                    <textarea name="bio" rows="4" placeholder="Describe your experience and ritual philosophies..." 
                              class="w-full bg-gray-50 border-none rounded-[2.5rem] py-8 px-8 text-onyx-900 focus:ring-4 focus:ring-brand-500/10 transition-all leading-relaxed">{{ old('bio', $profile->bio) }}</textarea>
                </div>

                <div class="space-y-8 pt-8 border-t border-gray-100">
                    <h3 class="text-xs font-black uppercase tracking-[4px] text-onyx-900">Weekly Availability Settings</h3>
                    <p class="text-xs text-gray-400 -mt-4">Define the times slots you are available for divine consultations each day.</p>
                    
                    @php
                        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                        $currentAvail = $profile->availability ?: [];
                    @endphp

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8">
                        @foreach($days as $day)
                            <div x-data="{ slots: {{ json_encode($currentAvail[$day] ?? []) }} }" class="space-y-3">
                                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block">{{ $day }}</label>
                                <div class="flex flex-wrap gap-2">
                                    <template x-for="(slot, index) in slots" :key="index">
                                        <div class="flex items-center bg-brand-50 text-brand-700 px-4 py-2 rounded-full border border-brand-100/50 group">
                                            <input type="hidden" :name="'availability[' + '{{ $day }}' + '][]'" :value="slot">
                                            <span class="text-[10px] font-bold" x-text="slot"></span>
                                            <button type="button" @click="slots.splice(index, 1)" class="ml-2 text-brand-300 hover:text-brand-500 transition-colors">
                                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>
                                        </div>
                                    </template>
                                    <button type="button" 
                                            @click="let s = prompt('Enter slot (e.g., 09:00-12:00)'); if(s) slots.push(s)"
                                            class="h-8 w-8 bg-gray-100 rounded-full flex items-center justify-center text-gray-400 hover:bg-brand-500 hover:text-white transition-all">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M12 4v16m8-8H4"/></svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="pt-8">
                    <button type="submit" 
                            class="w-full bg-onyx-900 text-white rounded-full py-6 text-center font-black text-xs uppercase tracking-[4px] hover:bg-brand-500 transition-all duration-300 shadow-2xl shadow-brand-500/10">
                        Finalize & Update Profile
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

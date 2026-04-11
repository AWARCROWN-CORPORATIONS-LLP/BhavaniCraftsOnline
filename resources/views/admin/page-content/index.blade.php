@extends('layouts.admin')

@section('header_extra')
    <h2 class="text-xl lg:text-2xl font-black text-gray-900 uppercase tracking-tight">Home Page Content Management</h2>
@endsection

@section('content')
    <div x-data="contentManager()" class="space-y-12 pb-20">
        @if(session('success'))
            <div class="p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl font-bold uppercase text-[10px] tracking-widest animate-fade-in-down">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.page-content.update') }}" method="POST" enctype="multipart/form-data" class="space-y-12">
            @csrf

            @foreach($contents as $section => $items)
                {{-- No sections skipped (Hero and Offers now manageable) --}}
                <div class="card-premium p-8 relative overflow-hidden">
                    <div class="flex items-center space-x-4 mb-8">
                        <span class="h-8 w-8 bg-brand-500/10 text-brand-600 rounded-lg flex items-center justify-center">
                            @if($section == 'identity')
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            @else
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" /></svg>
                            @endif
                        </span>
                        <div>
                            <h3 class="text-sm font-black uppercase tracking-[3px] text-gray-900">{{ ucfirst($section) }} Section</h3>
                            <p class="text-[9px] text-gray-400 font-bold uppercase tracking-[1px] mt-1">Manage visuals and messaging for the {{ $section }} area.</p>
                        </div>
                        <div class="flex-grow h-[1px] bg-gray-100"></div>
                        @if($section != 'identity')
                        <button type="button" @click="openPreview('{{ $section }}')" class="flex items-center space-x-2 px-4 py-2 bg-[#111111] text-white rounded-lg text-[9px] font-black uppercase tracking-widest hover:bg-[#ff9933] transition-colors">
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            <span>Live Preview</span>
                        </button>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        @foreach($items as $item)
                            <div class="space-y-2">
                                <label class="text-[9px] font-black uppercase tracking-[2px] text-gray-400 block">{{ $item->label }}</label>
                                
                                @if($item->type == 'textarea')
                                    <textarea name="{{ $item->key }}" 
                                              x-model="formValues['{{ $item->key }}']"
                                              class="w-full bg-gray-50 border-gray-100 rounded-xl text-xs font-bold text-gray-900 focus:ring-brand-500 focus:border-brand-500 transition-all p-4" rows="4">{{ $item->value }}</textarea>
                                @elseif($item->type == 'image')
                                    <div class="space-y-3">
                                        <div class="relative group">
                                            <input type="file" name="file_{{ $item->key }}" 
                                                   @change="handleFile('{{ $item->key }}', $event)"
                                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                            <div class="w-full bg-gray-50 border-2 border-dashed border-gray-200 rounded-xl py-8 px-4 text-center group-hover:border-brand-500 transition-all">
                                                <svg class="h-8 w-8 mx-auto text-gray-400 mb-2 group-hover:text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Select New Image</p>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-xl">
                                            <div class="h-14 w-14 rounded-lg overflow-hidden border border-gray-100 bg-white shadow-sm flex-shrink-0">
                                                <img :src="formValues['{{ $item->key }}']" 
                                                     loading="lazy"
                                                     class="h-full w-full object-cover" 
                                                     x-on:error="$el.src='data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%22150%22%20height%3D%22150%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%3Crect%20width%3D%22100%25%22%20height%3D%22100%25%22%20fill%3D%22%23f3f4f6%22%2F%3E%3Ctext%20x%3D%2250%25%22%20y%3D%2250%25%22%20font-family%3D%22sans-serif%22%20font-size%3D%2214%22%20fill%3D%22%239ca3af%22%20text-anchor%3D%22middle%22%20dy%3D%22.3em%22%3ENo+Image%3C%2Ftext%3E%3C%2Fsvg%3E'; $el.onerror=null;">
                                            </div>
                                            <div class="flex-grow truncate">
                                                <p class="text-[10px] font-black text-gray-900 uppercase tracking-widest truncate" x-text="'Visual Content: ' + (formValues['{{ $item->key }}'] || 'Default')"></p>
                                                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-[2px]">Current Master Visual</p>
                                            </div>
                                        </div>
                                        
                                        <input type="hidden" name="{{ $item->key }}" :value="formValues['{{ $item->key }}']">
                                    </div>
                                @elseif(str_ends_with($item->key, '_enabled') || str_ends_with($item->key, '_status'))
                                    <div class="flex items-center">
                                        <div class="relative inline-block w-14 h-8 transition duration-200 ease-in-out bg-gray-200 rounded-full"
                                             :class="formValues['{{ $item->key }}'] == '1' ? 'bg-emerald-500' : 'bg-gray-200'">
                                            <input type="checkbox" 
                                                   @change="formValues['{{ $item->key }}'] = $event.target.checked ? '1' : '0'"
                                                   :checked="formValues['{{ $item->key }}'] == '1'"
                                                   class="absolute inset-0 z-10 w-full h-full opacity-0 cursor-pointer">
                                            <div class="absolute w-6 h-6 transition-all duration-200 ease-in-out bg-white rounded-full top-1 left-1"
                                                 :style="formValues['{{ $item->key }}'] == '1' ? 'transform: translateX(1.5rem)' : ''"></div>
                                        </div>
                                        <span class="ml-4 text-[10px] font-black uppercase tracking-widest" :class="formValues['{{ $item->key }}'] == '1' ? 'text-emerald-600' : 'text-gray-400'" x-text="formValues['{{ $item->key }}'] == '1' ? 'Active' : 'Hidden'"></span>
                                        <input type="hidden" name="{{ $item->key }}" :value="formValues['{{ $item->key }}']">
                                    </div>
                                @elseif($item->key == 'recommendation_mode')
                                    <select name="{{ $item->key }}" 
                                            x-model="formValues['{{ $item->key }}']"
                                            class="w-full bg-gray-50 border-gray-100 rounded-xl text-xs font-bold text-gray-900 h-14 px-4 focus:ring-brand-500 focus:border-brand-500 transition-all">
                                        <option value="Festive">Festive Mode (High Velocity/Best Sellers)</option>
                                        <option value="Heritage">Heritage Mode (Artisan Discovery/Newest Mixed)</option>
                                    </select>
                                @else
                                    <input type="text" name="{{ $item->key }}" 
                                           x-model="formValues['{{ $item->key }}']"
                                           class="w-full bg-gray-50 border-gray-100 rounded-xl text-xs font-bold text-gray-900 h-14 px-4 focus:ring-brand-500 focus:border-brand-500 transition-all">
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <!-- Floating Save Bar -->
            <div class="fixed bottom-10 left-1/2 -translate-x-1/2 z-50">
                <button type="submit" class="px-12 py-4 bg-[#ff9933] hover:bg-[#fb8c00] text-white text-[10px] font-black uppercase tracking-[4px] rounded-full shadow-[0_20px_50px_rgba(245,130,28,0.3)] transition-all transform hover:-translate-y-1 flex items-center space-x-3">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" /></svg>
                    <span>Commit All Changes</span>
                </button>
            </div>
        </form>

        <!-- Preview Modal -->
        <div x-cloak x-show="showPreview" 
             style="display: none;"
             class="fixed inset-0 z-[100] flex items-center justify-center p-4 lg:p-12 overflow-hidden">
            <div x-show="showPreview" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
                 class="absolute inset-0 bg-onyx-900/80 backdrop-blur-md" @click="showPreview = false"></div>
            
            <div x-show="showPreview" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                 class="relative bg-white w-full max-w-6xl h-full rounded-3xl shadow-2xl overflow-hidden flex flex-col">
                
                <!-- Modal Header -->
                <div class="px-8 py-6 border-b border-gray-100 flex items-center justify-between shrink-0">
                    <div class="flex items-center space-x-3">
                        <span class="h-10 w-10 bg-brand-500 text-white rounded-xl flex items-center justify-center">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        </span>
                        <div>
                            <h3 class="text-lg font-black uppercase tracking-widest text-onyx-900">Visual Simulation</h3>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Previewing: <span class="text-brand-500" x-text="previewSection"></span> section</p>
                        </div>
                    </div>
                    <button @click="showPreview = false" class="p-3 bg-gray-50 hover:bg-onyx-900 hover:text-white rounded-2xl transition-all">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <!-- Preview Canvas -->
                <div class="flex-1 overflow-y-auto bg-gray-50 p-4 lg:p-12">
                    <div class="max-w-4xl mx-auto">
                        <template x-if="previewSection === 'hero'">
                            <section class="relative h-[500px] w-full rounded-3xl overflow-hidden shadow-2xl bg-onyx-900">
                                <img :src="formValues['hero_bg_image']" 
                                     loading="lazy"
                                     class="absolute inset-0 h-full w-full object-cover opacity-60">
                                <div class="absolute inset-0 bg-gradient-to-t from-onyx-950 via-onyx-950/40 to-transparent"></div>
                                <div class="relative h-full flex flex-col items-center justify-center text-center px-12">
                                    <span class="inline-block py-2 px-6 rounded-full bg-brand-500/10 border border-brand-500/30 text-brand-400 text-[10px] font-black uppercase tracking-[5px] mb-8" x-text="'Established 2017 • Sacred Artisans'"></span>
                                    <h1 class="text-4xl md:text-5xl font-serif font-bold text-white mb-6 leading-tight" x-html="formValues['hero_title'] || 'Sacred Ritual Marketplace'"></h1>
                                    <p class="text-lg text-gray-300 font-light max-w-xl mx-auto mb-10" x-text="formValues['hero_subtitle'] || 'Serving your spiritual needs with wholesale & retail authentic brass idols and essentials.'"></p>
                                    <div class="flex items-center space-x-6">
                                        <span class="px-8 py-4 bg-white text-onyx-900 text-[11px] font-black uppercase tracking-[3px] rounded-full" x-text="formValues['hero_cta_retail'] || 'Retail Collection'"></span>
                                        <span class="px-8 py-4 bg-brand-600 border border-brand-500 text-white text-[11px] font-black uppercase tracking-[3px] rounded-full" x-text="formValues['hero_cta_wholesale'] || 'Wholesale Hub'"></span>
                                    </div>
                                </div>
                            </section>
                        </template>

                        <template x-if="previewSection === 'offers'">
                            <section class="relative py-20 px-12 rounded-3xl overflow-hidden bg-onyx-900 shadow-2xl">
                                <div class="absolute inset-0">
                                    <div class="absolute inset-0 bg-gradient-to-r from-onyx-900 via-onyx-900/60 to-transparent z-10"></div>
                                    <img :src="formValues['offer_bg_image']" 
                                         loading="lazy"
                                         class="h-full w-full object-cover opacity-30 mix-blend-overlay">
                                </div>
                                <div class="relative z-10 text-center">
                                    <span class="inline-block py-2 px-5 rounded-full bg-brand-500/10 border border-brand-500/20 text-brand-400 text-[10px] font-black uppercase tracking-[5px] mb-8" x-text="formValues['offer_badge']"></span>
                                    <h2 class="text-4xl lg:text-5xl font-serif font-bold text-white mb-6" x-text="formValues['offer_title']"></h2>
                                    <p class="text-lg text-gray-300 mb-10 max-w-xl mx-auto italic lh-relaxed" x-text="formValues['offer_description']"></p>
                                    <span class="inline-block px-12 py-5 bg-white text-onyx-900 text-xs font-black uppercase tracking-[4px] rounded-full" x-text="formValues['offer_btn_text']"></span>
                                </div>
                            </section>
                        </template>

                        <template x-if="previewSection === 'features'">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                                <template x-for="i in [1,2,3]">
                                    <div class="p-8 bg-white rounded-3xl shadow-xl text-center border border-gray-100">
                                        <div class="h-14 w-14 mx-auto bg-brand-50 rounded-2xl flex items-center justify-center mb-6 text-brand-500">
                                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                        </div>
                                        <h3 class="font-serif font-bold text-xl text-onyx-900 mb-4" x-text="formValues['feature_'+i+'_title']"></h3>
                                        <p class="text-sm text-gray-500 leading-relaxed" x-text="formValues['feature_'+i+'_description']"></p>
                                    </div>
                                </template>
                            </div>
                        </template>

                        <template x-if="previewSection === 'gallery'">
                            <div class="space-y-12">
                                <div class="text-center">
                                    <span class="text-[10px] font-black uppercase tracking-[4px] text-brand-500 block mb-2">Heritage in Motion</span>
                                    <h2 class="font-serif text-3xl font-bold text-onyx-900" x-text="formValues['gallery_title']"></h2>
                                </div>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                                    <template x-for="i in [1,2,3,4,5,6]">
                                        <div class="aspect-square rounded-2xl overflow-hidden shadow-lg border border-gray-100">
                                            <img :src="formValues['gallery_image_'+i]" 
                                                 loading="lazy"
                                                 class="w-full h-full object-cover">
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>
                        
                        <template x-if="previewSection === 'products'">
                            <div class="text-center py-20 bg-white rounded-3xl shadow-xl">
                                <span class="inline-block px-4 py-2 bg-brand-50 text-brand-600 text-[10px] font-black uppercase tracking-[4px] rounded-lg mb-4" x-text="formValues['products_badge']"></span>
                                <h2 class="text-3xl font-serif font-bold text-onyx-900" x-text="formValues['products_title']"></h2>
                                <div class="mt-12 grid grid-cols-2 md:grid-cols-4 gap-4 px-8 opacity-20">
                                    <div class="aspect-[3/4] bg-gray-100 rounded-xl"></div>
                                    <div class="aspect-[3/4] bg-gray-100 rounded-xl"></div>
                                    <div class="aspect-[3/4] bg-gray-100 rounded-xl"></div>
                                    <div class="aspect-[3/4] bg-gray-100 rounded-xl"></div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="px-8 py-6 border-t border-gray-100 flex items-center justify-center shrink-0">
                    <button @click="showPreview = false" class="px-12 py-4 bg-onyx-900 text-white text-[10px] font-black uppercase tracking-[4px] rounded-full hover:bg-brand-500 transition-all">
                        Exit Simulation
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function contentManager() {
            return {
                formValues: {
                    @foreach($contents as $section => $itemGroup)
                        @foreach($itemGroup as $item)
                            '{{ $item->key }}': @json($item->value),
                        @endforeach
                    @endforeach
                },
                previewSection: 'hero',
                showPreview: false,

                handleFile(key, event) {
                    const file = event.target.files[0];
                    if (file) {
                        // Cleanup old blob to prevent memory leaks
                        if (this.formValues[key] && this.formValues[key].startsWith('blob:')) {
                            URL.revokeObjectURL(this.formValues[key]);
                        }
                        this.formValues[key] = URL.createObjectURL(file);
                    }
                },

                openPreview(section) {
                    this.previewSection = section;
                    this.showPreview = true;
                }
            }
        }
    </script>
@endsection

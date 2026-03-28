@extends('layouts.admin')

@section('header_extra')
    <h2 class="text-xl lg:text-2xl font-black text-gray-900 uppercase tracking-tight">Dashboard Overview</h2>
@endsection

@section('content')

    <!-- STATS GRID -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
        
        <!-- Total Users Card -->
        <div class="card-premium p-8 relative overflow-hidden flex flex-col justify-between h-[220px]">
            <div class="z-10">
                <p class="text-[9px] font-black text-[#ff9933] uppercase tracking-[4px] mb-2 leading-none">Total Users</p>
                <h3 class="text-4xl lg:text-5xl font-black text-gray-900 leading-none tracking-tighter">{{ $stats['total_users'] }}</h3>
            </div>
            <div class="z-10 flex items-center justify-between">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest leading-none">Registered Members</p>
                <div class="h-10 w-10 bg-[#ff9933]/10 text-[#ff9933] flex items-center justify-center rounded-xl shadow-lg">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354l.586.586H19v10.354L12.586 16H4V4.94L11.414 4H12zM12 11h.01" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11a1 1 0 100-2 1 1 0 000 2z" /></svg>
                </div>
            </div>
            <div class="absolute -right-10 -bottom-10 h-40 w-40 bg-[#ff9933]/5 rounded-full blur-3xl"></div>
        </div>

        <!-- Pending Franchises Card -->
        <div class="card-premium p-8 relative overflow-hidden flex flex-col justify-between h-[220px] bg-gradient-to-br from-white to-gray-50">
            <div class="z-10">
                <p class="text-[9px] font-black text-[#ff9933] uppercase tracking-[4px] mb-2 leading-none">Pending Approvals</p>
                <h3 class="text-4xl lg:text-5xl font-black text-gray-900 leading-none tracking-tighter">{{ $stats['pending_franchises'] }}</h3>
            </div>
            <div class="z-10 flex items-center justify-between">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest leading-none">Pending Businesses</p>
                <div class="h-10 w-10 bg-[#ff9933] text-white flex items-center justify-center rounded-xl shadow-lg animate-pulse">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
            </div>
            <div class="absolute inset-0 border-[10px] border-[#ff9933]/5 rounded-[30px] -m-2 opacity-50"></div>
        </div>

        <!-- Total Products Card -->
        <div class="card-premium p-8 relative overflow-hidden flex flex-col justify-between h-[220px]">
            <div class="z-10">
                <p class="text-[9px] font-black text-[#ff9933] uppercase tracking-[4px] mb-2 leading-none">Catalog Size</p>
                <h3 class="text-4xl lg:text-5xl font-black text-gray-900 leading-none tracking-tighter">{{ $stats['total_products'] }}</h3>
            </div>
            <div class="z-10 flex items-center justify-between">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest leading-none">Total Products</p>
                <div class="h-10 w-10 bg-[#ff9933]/10 text-[#ff9933] flex items-center justify-center rounded-xl shadow-lg">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                </div>
            </div>
            <div class="absolute -left-10 -bottom-10 h-40 w-40 bg-gray-100/50 rounded-full blur-3xl"></div>
        </div>

        <!-- Revenue Registry Card -->
        <div class="card-premium p-8 relative overflow-hidden flex flex-col justify-between h-[220px]">
            <div class="z-10">
                <p class="text-[9px] font-black text-[#ff9933] uppercase tracking-[4px] mb-2 leading-none">Total Revenue</p>
                <h3 class="text-4xl lg:text-5xl font-black revenue-badge leading-none tracking-tighter">@format_currency_abbr($stats['revenue_total'])</h3>
            </div>
            <div class="z-10 flex items-center justify-between">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest leading-none">Total Profit Volume</p>
                <div class="h-10 w-10 bg-[#ff9933]/10 text-[#ff9933] flex items-center justify-center rounded-xl shadow-lg">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
            </div>
            <div class="absolute -right-20 -top-20 h-64 w-64 bg-[#ff9933]/5 rounded-full blur-3xl"></div>
        </div>

        <!-- Successful Deliveries Card -->
        <div class="card-premium p-8 relative overflow-hidden flex flex-col justify-between h-[220px] bg-gradient-to-br from-emerald-500 to-emerald-700">
            <div class="z-10">
                <p class="text-[9px] font-black text-emerald-100 uppercase tracking-[4px] mb-2 leading-none">Successful Deliveries</p>
                <h3 class="text-4xl lg:text-5xl font-black text-white leading-none tracking-tighter">{{ $stats['successful_deliveries'] }}</h3>
            </div>
            <div class="z-10 flex items-center justify-between">
                <p class="text-[10px] text-emerald-100 font-bold uppercase tracking-widest leading-none">Authentication Vault</p>
                <div class="h-10 w-10 bg-white/20 text-white flex items-center justify-center rounded-xl shadow-lg border border-white/30 backdrop-blur-sm">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                </div>
            </div>
            <div class="absolute inset-0 border-[10px] border-white/5 rounded-[30px] -m-2"></div>
        </div>

        <!-- Pending Returns Card -->
        <div class="card-premium p-8 relative overflow-hidden flex flex-col justify-between h-[220px] bg-gradient-to-br from-amber-500 to-amber-700">
            <div class="z-10">
                <p class="text-[9px] font-black text-amber-100 uppercase tracking-[4px] mb-2 leading-none">Pending Extractions</p>
                <h3 class="text-4xl lg:text-5xl font-black text-white leading-none tracking-tighter">{{ $stats['pending_returns'] }}</h3>
            </div>
            <div class="z-10 flex items-center justify-between">
                <p class="text-[10px] text-amber-100 font-bold uppercase tracking-widest leading-none">RMS Returns Registry</p>
                <div class="h-10 w-10 bg-white/20 text-white flex items-center justify-center rounded-xl shadow-lg border border-white/30 backdrop-blur-sm">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z" /></svg>
                </div>
            </div>
            <div class="absolute inset-0 border-[10px] border-white/5 rounded-[30px] -m-2"></div>
        </div>
    </div>

    <!-- COMMAND CENTER & ACCOUNTING EXPORTS -->
    <div class="mt-12 grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Revenue Trajectory Chart -->
        <div class="lg:col-span-2 card-premium p-8 relative overflow-hidden">
            <h3 class="text-xl font-black text-gray-900 uppercase tracking-widest mb-6">Revenue Trajectory (7 Days)</h3>
            <div id="revenueChart" class="w-full h-[300px]"></div>
        </div>

        <!-- Compliance & Export Modules -->
        <div class="card-premium p-8 relative overflow-hidden flex flex-col justify-between">
            <div>
                <h3 class="text-xl font-black text-gray-900 uppercase tracking-widest mb-2">Accounting Compliance</h3>
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-6 leading-relaxed">
                    Generate encrypted CSV payloads of financial transactions and inventory states for regulatory compliance.
                </p>
                
                <div class="space-y-4">
                    <a href="{{ route('admin.export.orders') }}" class="w-full flex items-center justify-between p-4 bg-gray-50 hover:bg-[#ff9933]/10 border border-gray-100 hover:border-[#ff9933] rounded-2xl transition-all group">
                        <div class="flex items-center space-x-4">
                            <div class="h-10 w-10 bg-white rounded-xl shadow-sm flex items-center justify-center text-gray-400 group-hover:text-[#ff9933] transition-colors">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-gray-900">Financial Ledger</h4>
                                <span class="text-[10px] text-gray-500 uppercase tracking-widest">Orders & Revenue</span>
                            </div>
                        </div>
                        <svg class="h-5 w-5 text-gray-300 group-hover:text-[#ff9933]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                    </a>

                    <a href="{{ route('admin.export.products') }}" class="w-full flex items-center justify-between p-4 bg-gray-50 hover:bg-[#ff9933]/10 border border-gray-100 hover:border-[#ff9933] rounded-2xl transition-all group">
                        <div class="flex items-center space-x-4">
                            <div class="h-10 w-10 bg-white rounded-xl shadow-sm flex items-center justify-center text-gray-400 group-hover:text-[#ff9933] transition-colors">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-gray-900">Inventory State</h4>
                                <span class="text-[10px] text-gray-500 uppercase tracking-widest">Stock & Thresholds</span>
                            </div>
                        </div>
                        <svg class="h-5 w-5 text-gray-300 group-hover:text-[#ff9933]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(isset($activeBroadcasts) && $activeBroadcasts->count() > 0)
        <!-- ACTIVE BROADCAST TICKER -->
        <div class="mt-12 space-y-4">
            @foreach($activeBroadcasts as $broadcast)
                <div class="card-premium p-6 flex flex-col md:flex-row items-center justify-between border-l-4 
                    {{ $broadcast->urgency == 'critical' ? 'border-red-500 bg-red-50/30' : 
                       ($broadcast->urgency == 'warning' ? 'border-amber-500 bg-amber-50/30' : 'border-blue-500 bg-blue-50/30') }}">
                    <div class="flex items-start md:items-center space-x-4 mb-4 md:mb-0">
                        <div class="h-10 w-10 flex-shrink-0 flex items-center justify-center rounded-xl 
                            {{ $broadcast->urgency == 'critical' ? 'bg-red-500/10 text-red-500 animate-pulse' : 
                               ($broadcast->urgency == 'warning' ? 'bg-amber-500/10 text-amber-500' : 'bg-blue-500/10 text-blue-500') }}">
                            @if($broadcast->urgency == 'critical')
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                            @else
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            @endif
                        </div>
                        <div>
                            <div class="flex items-center space-x-3 mb-1 mt-1">
                                <h4 class="text-sm font-black uppercase tracking-widest text-gray-900 leading-none">{{ $broadcast->title }}</h4>
                                <span class="px-2 py-0.5 rounded text-[8px] font-black uppercase tracking-[2px] 
                                    {{ $broadcast->target_audience == 'all' ? 'bg-gray-200 text-gray-600' : 'bg-purple-100 text-purple-600' }}">
                                    {{ $broadcast->target_audience == 'all' ? 'Global' : 'Staff Only' }}
                                </span>
                            </div>
                            <p class="text-[11px] font-bold text-gray-500 leading-relaxed">{{ $broadcast->message }}</p>
                        </div>
                    </div>
                    <time class="text-[9px] font-black text-gray-400 uppercase tracking-[3px] ml-4 flex-shrink-0">{{ $broadcast->created_at->diffForHumans() }}</time>
                </div>
            @endforeach
        </div>
    @endif

    <!-- QUICK ACTIONS -->
    <div class="mt-16">
        <div class="flex items-center space-x-6 mb-8">
            <h2 class="text-2xl font-black text-gray-900 uppercase">Quick Actions</h2>
            <div class="flex-grow h-[1px] bg-gray-100"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <a href="{{ route('admin.franchises') }}" class="card-premium p-10 flex flex-col items-center text-center group hover:bg-[#ff9933] transition-colors duration-500">
                <div class="h-20 w-20 bg-[#ff9933]/10 text-[#ff9933] rounded-3xl flex items-center justify-center mb-6 group-hover:bg-white group-hover:scale-110 transition-all">
                    <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                </div>
                <h4 class="text-[12px] font-black uppercase tracking-[4px] text-gray-900 group-hover:text-white mb-2">Franchise Management</h4>
                <p class="text-[10px] text-gray-400 group-hover:text-white/70 font-bold uppercase tracking-wider">Review & Approve Business Requests</p>
            </a>

            <a href="{{ route('admin.categories.index') }}" class="card-premium p-10 flex flex-col items-center text-center group hover:bg-[#ff9933] transition-colors duration-500">
                <div class="h-20 w-20 bg-[#ff9933]/10 text-[#ff9933] rounded-3xl flex items-center justify-center mb-6 group-hover:bg-white group-hover:scale-110 transition-all">
                    <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m12 4a2 2 0 100-4m0 4a2 2 0 110-4m-6 0a2 2 0 100 4m0-4a2 2 0 110 4m-6 0v-2m8 4v-2a2 2 0 110 4m-6 0v2m8 4v-2" /></svg>
                </div>
                <h4 class="text-[12px] font-black uppercase tracking-[4px] text-gray-900 group-hover:text-white mb-2 leading-none">Categories</h4>
                <p class="text-[10px] text-gray-400 group-hover:text-white/70 font-bold uppercase tracking-wider">Manage Product Categories</p>
            </a>

            <a href="{{ route('admin.products.index') }}" class="card-premium p-10 flex flex-col items-center text-center group hover:bg-[#ff9933] transition-colors duration-500">
                <div class="h-20 w-20 bg-[#ff9933]/10 text-[#ff9933] rounded-3xl flex items-center justify-center mb-6 group-hover:bg-white group-hover:scale-110 transition-all">
                    <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                </div>
                <h4 class="text-[12px] font-black uppercase tracking-[4px] text-gray-900 group-hover:text-white mb-2 leading-none">Products</h4>
                <p class="text-[10px] text-gray-400 group-hover:text-white/70 font-bold uppercase tracking-wider">Manage Product Catalog</p>
            </a>

            <a href="{{ route('admin.page-content.index') }}" class="card-premium p-10 flex flex-col items-center text-center group hover:bg-[#ff9933] transition-colors duration-500">
                <div class="h-20 w-20 bg-[#ff9933]/10 text-[#ff9933] rounded-3xl flex items-center justify-center mb-6 group-hover:bg-white group-hover:scale-110 transition-all">
                    <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" /></svg>
                </div>
                <h4 class="text-[12px] font-black uppercase tracking-[4px] text-gray-900 group-hover:text-white mb-2 leading-none">Page Content</h4>
                <p class="text-[10px] text-gray-400 group-hover:text-white/70 font-bold uppercase tracking-wider">Manage Dynamic Sections</p>
            </a>
        </div>
    </div>

    <!-- ApexCharts Setup -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const chartData = @json($revenueTrend);
            
            const options = {
                chart: {
                    type: 'area',
                    height: 300,
                    toolbar: { show: false },
                    fontFamily: 'inherit'
                },
                series: [{
                    name: 'Revenue',
                    data: Object.values(chartData)
                }],
                xaxis: {
                    categories: Object.keys(chartData),
                    labels: { style: { colors: '#9ca3af', fontSize: '10px', fontWeight: 700 } },
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                yaxis: {
                    labels: { 
                        formatter: (val) => { return '₹ ' + val.toLocaleString('en-IN') },
                        style: { colors: '#9ca3af', fontSize: '10px', fontWeight: 700 } 
                    }
                },
                colors: ['#ff9933'],
                fill: {
                    type: 'gradient',
                    gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.0, stops: [0, 90, 100] }
                },
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 3 },
                grid: { borderColor: '#f3f4f6', strokeDashArray: 4, yaxis: { lines: { show: true } } },
                tooltip: { theme: 'light', y: { formatter: function (val) { return '₹ ' + val.toLocaleString('en-IN') } } }
            };

            const chart = new ApexCharts(document.querySelector("#revenueChart"), options);
            chart.render();
        });
    </script>
@endsection

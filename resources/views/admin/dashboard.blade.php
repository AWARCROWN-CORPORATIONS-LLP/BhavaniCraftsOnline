@extends('layouts.admin')

@section('header_extra')
    <h2 class="text-xl lg:text-2xl font-black text-gray-900 uppercase tracking-tight">Store Summary</h2>
@endsection

@section('content')

    <!-- STATS GRID: PRECISION METRICS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Metric Card: Total Users -->
        <div class="card-premium p-6 flex flex-col justify-between h-[160px]">
            <div>
                <p class="label-muted mb-1 text-slate-500">Growth Registry</p>
                <div class="flex items-end justify-between">
                    <h3 class="heading-silk text-3xl">{{ number_format($stats['total_users']) }}</h3>
                    <span class="text-[9px] font-black text-emerald-500 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-100 uppercase tracking-tighter">Verified</span>
                </div>
            </div>
            <div class="flex items-center justify-between pt-4 border-t border-slate-50">
                <p class="text-[10px] text-muted font-semibold uppercase tracking-widest">Total Customers</p>
                <svg class="h-4 w-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
            </div>
        </div>

        <!-- Metric Card: Pending Franchises -->
        <div class="card-premium p-6 flex flex-col justify-between h-[160px]">
            <div>
                <p class="label-muted mb-1 text-slate-500">Application Queue</p>
                <div class="flex items-end justify-between">
                    <h3 class="heading-silk text-3xl">{{ $stats['pending_franchises'] }}</h3>
                    @if($stats['pending_franchises'] > 0)
                        <span class="text-[9px] font-black text-amber-500 bg-amber-50 px-2 py-0.5 rounded border border-amber-100 uppercase tracking-tighter animate-pulse">Action Required</span>
                    @endif
                </div>
            </div>
            <div class="flex items-center justify-between pt-4 border-t border-slate-50">
                <p class="text-[10px] text-muted font-semibold uppercase tracking-widest">Partner Requests</p>
                <svg class="h-4 w-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354l.586.586H19v10.354L12.586 16H4V4.94L11.414 4H12zM12 11h.01" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11a1 1 0 100-2 1 1 0 000 2z" /></svg>
            </div>
        </div>

        @if(auth()->user()->hasRole('super_admin'))
        <!-- Metric Card: Revenue -->
        <div class="card-premium p-6 flex flex-col justify-between h-[160px] border-l-4 border-l-brand-primary">
            <div>
                <p class="label-muted mb-1 text-slate-500">Financial Terminal</p>
                <div class="flex items-end justify-between">
                    <h3 class="heading-silk text-2xl">@format_currency_abbr($stats['revenue_total'])</h3>
                    <span class="text-[9px] font-black text-blue-500 bg-blue-50 px-2 py-0.5 rounded border border-blue-100 uppercase tracking-tighter">Gross</span>
                </div>
            </div>
            <div class="flex items-center justify-between pt-4 border-t border-slate-50">
                <p class="text-[10px] text-muted font-semibold uppercase tracking-widest">Total Sales</p>
                <svg class="h-4 w-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
        </div>
        @endif

        <!-- Metric Card: Returns -->
        <div class="card-premium p-6 flex flex-col justify-between h-[160px]">
            <div>
                <p class="label-muted mb-1 text-slate-500">Inventory Risks</p>
                <div class="flex items-end justify-between">
                    <h3 class="heading-silk text-3xl">{{ $stats['pending_returns'] }}</h3>
                    <span class="text-[9px] font-black text-rose-500 bg-rose-50 px-2 py-0.5 rounded border border-rose-100 uppercase tracking-tighter">Return Dept</span>
                </div>
            </div>
            <div class="flex items-center justify-between pt-4 border-t border-slate-50">
                <p class="text-[10px] text-muted font-semibold uppercase tracking-widest">Pending Returns</p>
                <svg class="h-4 w-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4 2 4-2 4 2z" /></svg>
            </div>
        </div>
    </div>

    <!-- ANALYTICS & REVENUE HUB -->
    <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
        @if(auth()->user()->hasRole('super_admin'))
        <div class="lg:col-span-2 card-premium p-6">
            <h3 class="heading-silk text-sm mb-6">Revenue Trajectory</h3>
            <div id="revenueChart" class="w-full h-[320px]"></div>
        </div>
        @endif

        <!-- DATA EXPORTS -->
        <div class="card-premium p-6 flex flex-col">
            <h3 class="heading-silk text-sm mb-2">Export Data</h3>
            <p class="text-[11px] text-muted mb-6 leading-relaxed">Download CSV archives for offline accounting and reporting.</p>
            
            <div class="space-y-3">
                @if(auth()->user()->hasRole('super_admin'))
                <a href="{{ route('admin.export.orders') }}" data-turbo="false" class="w-full flex items-center justify-between p-4 bg-slate-50 hover:bg-white border hover:border-brand-primary rounded-lg transition-all group">
                    <div class="flex items-center space-x-3">
                        <svg class="h-4 w-4 text-slate-400 group-hover:text-brand-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        <span class="text-xs font-bold text-slate-700">Financial Registry</span>
                    </div>
                    <svg class="h-3 w-3 text-slate-300 group-hover:text-brand-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                </a>
                @endif

                <a href="{{ route('admin.export.products') }}" data-turbo="false" class="w-full flex items-center justify-between p-4 bg-slate-50 hover:bg-white border hover:border-brand-primary rounded-lg transition-all group">
                    <div class="flex items-center space-x-3">
                        <svg class="h-4 w-4 text-slate-400 group-hover:text-brand-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                        <span class="text-xs font-bold text-slate-700">Inventory Logs</span>
                    </div>
                    <svg class="h-3 w-3 text-slate-300 group-hover:text-brand-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                </a>
            </div>
        </div>
    </div>

    <!-- INFRASTRUCTURE & BROADCASTS -->
    <div class="mt-8 grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- SYSTEM TELEMETRY -->
        <div class="lg:col-span-8 card-premium p-0 flex flex-col overflow-hidden">
            <div class="px-6 py-4 border-b border-border-subtle bg-slate-50 flex items-center justify-between">
                <h3 class="heading-silk text-[11px] uppercase tracking-widest text-slate-400">Infrastructure Health</h3>
                <div class="flex items-center space-x-2">
                    <div class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></div>
                    <span class="text-[10px] font-bold text-emerald-600 uppercase">Operational</span>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div class="space-y-1">
                        <p class="label-muted text-[10px]">Environment</p>
                        <p class="font-bold text-slate-900">{{ $telemetry['php_version'] }} / PHP</p>
                    </div>
                    <div class="space-y-1">
                        <p class="label-muted text-[10px]">Core Runtime</p>
                        <p class="font-bold text-slate-900">Laravel v{{ $telemetry['laravel_version'] }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="label-muted text-[10px]">Memory Pulse</p>
                        <p class="font-bold text-slate-900">{{ $telemetry['memory_usage'] }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="label-muted text-[10px]">Security Engine</p>
                        <p class="font-bold {{ $telemetry['debug_mode'] == 'Disabled' ? 'text-emerald-500' : 'text-rose-500' }}">{{ $telemetry['debug_mode'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- BROADCAST SHORTS -->
        <div class="lg:col-span-4 card-premium p-0 flex flex-col overflow-hidden">
            <div class="px-6 py-4 border-b border-border-subtle bg-brand-dark flex items-center justify-between">
                <h3 class="heading-silk text-[11px] uppercase tracking-widest text-white/50">Admin Logs</h3>
                <svg class="h-4 w-4 text-white/20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" /></svg>
            </div>
            <div class="p-6 space-y-4">
                @forelse($activeBroadcasts as $broadcast)
                    <div class="group">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-[9px] font-black uppercase tracking-widest text-brand-primary">{{ $broadcast->title }}</span>
                            <span class="text-[8px] font-bold text-slate-400">{{ $broadcast->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-[10px] text-slate-600 font-medium group-hover:text-slate-900 transition-colors">{{ $broadcast->message }}</p>
                    </div>
                @empty
                    <p class="text-[10px] text-slate-400 italic text-center py-4 uppercase tracking-widest">No active logs</p>
                @endforelse
            </div>
        </div>
    </div>


    <!-- ACTION HUB -->
    <div class="mt-12">
        <h3 class="heading-silk text-sm mb-6">Action Hub</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <a href="{{ route('admin.franchises') }}" class="card-premium p-5 flex items-center space-x-4 hover:bg-slate-50 transition-colors">
                <div class="h-10 w-10 bg-slate-100 rounded-lg flex items-center justify-center text-slate-500">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                </div>
                <div>
                     <p class="text-xs font-bold text-slate-900">Partner Manager</p>
                     <p class="text-[9px] text-muted uppercase font-bold tracking-widest">Franchises</p>
                </div>
            </a>

            <a href="{{ route('admin.categories.index') }}" class="card-premium p-5 flex items-center space-x-4 hover:bg-slate-50 transition-colors">
                <div class="h-10 w-10 bg-slate-100 rounded-lg flex items-center justify-center text-slate-500">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m12 4a2 2 0 100-4m0 4a2 2 0 110-4m-6 0a2 2 0 100 4m0-4a2 2 0 110 4m-6 0v-2m8 4v-2a2 2 0 110 4m-6 0v2m8 4v-2" /></svg>
                </div>
                <div>
                     <p class="text-xs font-bold text-slate-900">Category Engine</p>
                     <p class="text-[9px] text-muted uppercase font-bold tracking-widest">Inventory</p>
                </div>
            </a>

            <a href="{{ route('admin.products.index') }}" class="card-premium p-5 flex items-center space-x-4 hover:bg-slate-50 transition-colors">
                <div class="h-10 w-10 bg-slate-100 rounded-lg flex items-center justify-center text-slate-500">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                </div>
                <div>
                     <p class="text-xs font-bold text-slate-900">Resource Master</p>
                     <p class="text-[9px] text-muted uppercase font-bold tracking-widest">Products</p>
                </div>
            </a>

            <a href="{{ route('admin.page-content.index') }}" class="card-premium p-5 flex items-center space-x-4 hover:bg-slate-50 transition-colors">
                <div class="h-10 w-10 bg-slate-100 rounded-lg flex items-center justify-center text-slate-500">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" /></svg>
                </div>
                <div>
                     <p class="text-xs font-bold text-slate-900">Content Designer</p>
                     <p class="text-[9px] text-muted uppercase font-bold tracking-widest">Frontend</p>
                </div>
            </a>
        </div>
    </div>

    <!-- ApexCharts Setup -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        (function() {
            const initChart = () => {
                const chartEl = document.querySelector("#revenueChart");
                if (!chartEl) return;

                const chartData = @json($revenueTrend);
                const options = {
                    chart: {
                        type: 'area',
                        height: 320,
                        toolbar: { show: false },
                        fontFamily: 'inherit',
                        animations: { enabled: true, easing: 'easeinout', speed: 800 }
                    },
                    series: [{
                        name: 'Revenue',
                        data: Object.values(chartData)
                    }],
                    xaxis: {
                        categories: Object.keys(chartData),
                        labels: { style: { colors: '#64748b', fontSize: '10px', fontWeight: 600 } },
                        axisBorder: { show: false },
                        axisTicks: { show: false }
                    },
                    yaxis: {
                        labels: { 
                            formatter: (val) => { return '₹' + val.toLocaleString('en-IN') },
                            style: { colors: '#64748b', fontSize: '10px', fontWeight: 600 } 
                        }
                    },
                    colors: ['#ff9933'],
                    fill: {
                        type: 'gradient',
                        gradient: { shadeIntensity: 1, opacityFrom: 0.2, opacityTo: 0.05, stops: [0, 90, 100] }
                    },
                    dataLabels: { enabled: false },
                    stroke: { curve: 'smooth', width: 2, colors: ['#ff9933'] },
                    grid: { borderColor: '#f1f5f9', strokeDashArray: 4, yaxis: { lines: { show: true } } },
                    tooltip: { theme: 'light', y: { formatter: function (val) { return '₹' + val.toLocaleString('en-IN') } } }
                };

                const chart = new ApexCharts(chartEl, options);
                chart.render();
            };

            // Support both standard load and Turbo navigation
            document.addEventListener('DOMContentLoaded', initChart);
            document.addEventListener('turbo:load', initChart);
        })();
    </script>
@endsection

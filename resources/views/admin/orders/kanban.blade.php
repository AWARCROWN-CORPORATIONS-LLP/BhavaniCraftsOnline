@extends('layouts.admin')

@section('header_extra')
    <div class="flex items-center space-x-4">
        <h2 class="text-xl lg:text-3xl font-black text-gray-900 uppercase tracking-tighter">Sacred Fulfillment</h2>
        <span class="text-gray-300">/</span>
        <p class="text-[10px] items-center font-black text-[#ff9933] uppercase tracking-[4px]">Kanban Module</p>
    </div>
    
    <a href="{{ route('admin.orders.index') }}" class="btn-luxury-saffron px-6 py-2.5 text-[10px] bg-white text-gray-400 border border-gray-100 hover:bg-gray-50 shadow-none">
        Switch to List View
    </a>
@endsection

@section('content')

    <div class="flex flex-col lg:flex-row h-[75vh] w-full space-y-6 lg:space-y-0 lg:space-x-6 overflow-x-auto pb-4 kanban-board" x-data="kanbanBoard()">
        
        <!-- PENDING COLUMN -->
        <div class="flex-shrink-0 w-80 bg-gray-50/50 rounded-2xl flex flex-col h-full border border-gray-100 p-4" data-status="Pending">
            <div class="mb-4 flex items-center justify-between px-2">
                <div class="flex items-center space-x-3">
                    <div class="h-3 w-3 rounded-full bg-gray-300"></div>
                    <h3 class="text-[11px] font-black uppercase text-gray-500 tracking-[3px]">Incoming (Pending)</h3>
                </div>
                <span class="bg-white text-gray-400 text-[10px] font-black px-2 py-0.5 rounded-md shadow-sm border border-gray-100">{{ $pendingOrders->count() }}</span>
            </div>
            <div class="flex-1 overflow-y-auto space-y-3 p-2 kanban-column" id="col-pending">
                @foreach($pendingOrders as $order)
                    @include('admin.orders.partials.kanban_card', ['order' => $order])
                @endforeach
            </div>
        </div>

        <!-- PROCESSING COLUMN -->
        <div class="flex-shrink-0 w-80 bg-blue-50/50 rounded-2xl flex flex-col h-full border border-blue-100 p-4" data-status="Processing">
            <div class="mb-4 flex items-center justify-between px-2">
                <div class="flex items-center space-x-3">
                    <div class="h-3 w-3 rounded-full bg-blue-400 animate-pulse"></div>
                    <h3 class="text-[11px] font-black uppercase text-blue-600 tracking-[3px]">Polishing (Processing)</h3>
                </div>
                <span class="bg-white text-blue-400 bg-blue-50 border-blue-100 text-[10px] font-black px-2 py-0.5 rounded-md shadow-sm border">{{ $processingOrders->count() }}</span>
            </div>
            <div class="flex-1 overflow-y-auto space-y-3 p-2 kanban-column" id="col-processing">
                @foreach($processingOrders as $order)
                    @include('admin.orders.partials.kanban_card', ['order' => $order])
                @endforeach
            </div>
        </div>

        <!-- SHIPPED COLUMN -->
        <div class="flex-shrink-0 w-80 bg-purple-50/50 rounded-2xl flex flex-col h-full border border-purple-100 p-4" data-status="Shipped">
            <div class="mb-4 flex items-center justify-between px-2">
                <div class="flex items-center space-x-3">
                    <div class="h-3 w-3 rounded-full bg-purple-400"></div>
                    <h3 class="text-[11px] font-black uppercase text-purple-600 tracking-[3px]">In Transit (Shipped)</h3>
                </div>
                <span class="bg-white text-purple-400 bg-purple-50 border-purple-100 text-[10px] font-black px-2 py-0.5 rounded-md shadow-sm border">{{ $shippedOrders->count() }}</span>
            </div>
            <div class="flex-1 overflow-y-auto space-y-3 p-2 kanban-column" id="col-shipped">
                @foreach($shippedOrders as $order)
                    @include('admin.orders.partials.kanban_card', ['order' => $order])
                @endforeach
            </div>
        </div>

        <!-- DELIVERED COLUMN -->
        <div class="flex-shrink-0 w-80 bg-green-50/50 rounded-2xl flex flex-col h-full border border-green-100 p-4" data-status="Delivered">
            <div class="mb-4 flex items-center justify-between px-2">
                <div class="flex items-center space-x-3">
                    <div class="h-3 w-3 rounded-full bg-green-500"></div>
                    <h3 class="text-[11px] font-black uppercase text-green-600 tracking-[3px]">Completed (Delivered)</h3>
                </div>
                <span class="bg-white text-green-500 bg-green-50 border-green-100 text-[10px] font-black px-2 py-0.5 rounded-md shadow-sm border">{{ $deliveredOrders->count() }}</span>
            </div>
            <div class="flex-1 overflow-y-auto space-y-3 p-2 kanban-column" id="col-delivered">
                @foreach($deliveredOrders as $order)
                    @include('admin.orders.partials.kanban_card', ['order' => $order])
                @endforeach
            </div>
        </div>

    </div>

    <!-- Dragula for Drag/Drop -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dragula/3.7.3/dragula.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dragula/3.7.3/dragula.min.css">
    
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('kanbanBoard', () => ({
                init() {
                    const drake = dragula([
                        document.getElementById('col-pending'),
                        document.getElementById('col-processing'),
                        document.getElementById('col-shipped'),
                        document.getElementById('col-delivered')
                    ]);

                    drake.on('drop', (el, target, source, sibling) => {
                        const orderId = el.dataset.id;
                        const newStatus = target.parentElement.dataset.status;
                        
                        // Proceed to send AJAX request to update status
                        this.updateOrderStatus(orderId, newStatus);
                    });
                },

                updateOrderStatus(orderId, newStatus) {
                    fetch(`/admin/orders/kanban/${orderId}/status`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ status: newStatus })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if(data.success) {
                            // Optional: show a mini toast notification here
                            console.log(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error updating status:', error);
                        // Reload page to reset state on error
                        window.location.reload();
                    });
                }
            }));
        });
    </script>
    
    <style>
        .gu-mirror { position: fixed !important; margin: 0 !important; z-index: 9999 !important; opacity: 0.8; }
        .gu-hide { display: none !important; }
        .gu-unselectable { user-select: none !important; }
        .gu-transit { opacity: 0.2; transform: scale(0.95); }
    </style>

@endsection

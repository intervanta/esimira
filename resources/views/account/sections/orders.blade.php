<div id="orders">

    <div class="content-header mb-6">
        <h3>Orders</h3>
    </div>

    @if($orders->count() == 0)

        <!-- EMPTY STATE -->
        <div class="text-center py-10">
            <img src="{{ asset('assets/images/empty_orders.svg') }}" 
                 alt="No orders" class="mx-auto mb-4 opacity-70" style="height: 120px;">

            <h5 class="text-xl font-semibold mb-2">You haven’t placed any orders yet.</h5>
            <p class="text-gray-600">Browse our eSIM plans and get connected in minutes!</p>

            <a href="/plans" class="btn btn-primary mt-4 inline-block">
                BROWSE ESIM PLANS
            </a>
        </div>

    @else

        <!-- ORDER LIST -->
        <div class="space-y-5">

            @foreach($orders as $order)
                <div class="border border-gray-200 rounded-xl p-5 bg-white shadow-sm hover:shadow-md transition">

                    <div class="flex justify-between items-center mb-3">
                        <div>
                            <p class="font-semibold">Order #{{ $order->order_number }}</p>
                            <p class="text-sm text-gray-500">
                                {{ $order->created_at->format('d M Y, h:i A') }}
                            </p>
                        </div>

                        <!-- STATUS BADGE -->
                        <span class="px-3 py-1 text-sm rounded-full 
                            {{ $order->payment_status ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $order->payment_status ? 'Paid' : 'Unpaid' }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-sm">
                        <p><strong>Amount:</strong> ₹{{ number_format($order->amount, 2) }}</p>
                        <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('order.confirmation', ['order_number' => $order->order_number]) }}"
                           class="text-primary font-semibold hover:underline">
                            View Details →
                        </a>
                    </div>

                </div>
            @endforeach

        </div>

    @endif

</div>

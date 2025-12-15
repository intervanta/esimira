<div id="orders" class="bg-[#FBF7F2] p-4 rounded-3xl">

    <!-- HEADER -->
    <div class="content-header mb-6">
        <h3 class="text-xl font-semibold">Orders</h3>
    </div>

    @if ($orders->count() == 0)

        <!-- EMPTY STATE -->
        <div class="text-center py-10">
            <img src="{{ asset('assets/images/empty_orders.svg') }}"
                 class="mx-auto mb-4 opacity-70" style="height:120px">

            <h5 class="text-xl font-semibold mb-2">
                You haven't placed any orders yet.
            </h5>

            <p class="text-gray-600">
                Browse our eSIM plans and get connected in minutes!
            </p>

            <a href="/plans" class="btn btn-primary mt-4 inline-block">
                BROWSE ESIM PLANS
            </a>
        </div>

    @else

        <!-- ORDER LIST -->
        <div class="space-y-4">
            @foreach ($orders as $order)
                <div
                    class="bg-white rounded-2xl border border-gray-100 px-5 py-4
                           flex items-center justify-between cursor-pointer
                           hover:shadow-md transition"
                    onclick="openOrderModal(this)"

                    data-order="#{{ $order->order_number }}"
                    data-date="{{ $order->created_at->format('d M Y, h:i A') }}"
                    data-status="{{ $order->status }}"
                    data-payment="{{ strtoupper($order->latestTransaction->gateway ?? '-') }}"
                    data-total="{{ $order->latestTransaction->total_amount ?? 0 }}"
                    data-receipt="{{ route('dashboard.orders.receipt', $order->id) }}"
                >

                    <!-- LEFT -->
                    <div class="flex items-center gap-4">
                        <img src="{{ Storage::url($order->bundle->image) }}"
                             class="w-14 h-14 rounded-xl object-cover">

                        <div>
                            <h4 class="font-semibold">
                                {{ $order->bundle->name }}
                            </h4>
                            <p class="text-sm text-gray-600">
                                {{ $order->refill->title }}
                            </p>
                            <p class="text-xs text-gray-400">
                                {{ $order->created_at->format('d M Y, h:i A') }}
                            </p>
                        </div>
                    </div>

                    <!-- RIGHT (Rate Conversion Compatible) -->
                    <div class="font-semibold plan-price"
                         data-price="{{ $order->latestTransaction->total_amount ?? '' }}">
                    </div>
                </div>
            @endforeach
        </div>

        <!-- PAGINATION -->
        <div class="mt-6">
            {{ $orders->links('pagination::tailwind') }}
        </div>

    @endif
</div>

<!-- ================= ORDER DETAILS MODAL ================= -->
<div id="orderModal"
     class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">

    <div class="bg-[#FBF7F2] rounded-3xl w-full max-w-lg mx-4 p-6 relative">

        <!-- CLOSE -->
        <button onclick="closeOrderModal()"
                class="absolute top-4 right-4 text-xl text-gray-400 hover:text-gray-600">
            ✕
        </button>

        <h3 class="text-xl font-semibold mb-6">Order details</h3>

        <!-- ORDER INFO -->
        <div class="bg-white rounded-2xl p-5 mb-5">
            <h4 class="font-semibold mb-4">Order information</h4>

            <div class="space-y-3 text-sm">
                <div class="flex justify-between border-b pb-2">
                    <span class="text-gray-500">Order ID</span>
                    <span id="mOrder">-</span>
                </div>

                <div class="flex justify-between border-b pb-2">
                    <span class="text-gray-500">Order date</span>
                    <span id="mDate">-</span>
                </div>

                <div class="flex justify-between">
                    <span class="text-gray-500">Order status</span>
                    <span id="mStatus"
                          class="px-3 py-1 rounded-full text-xs bg-gray-100 text-gray-600">
                        -
                    </span>
                </div>
            </div>
        </div>

        <!-- PAYMENT DETAILS -->
        <div class="bg-white rounded-2xl p-5 mb-5">
            <h4 class="font-semibold mb-4">Payment details</h4>

            <div class="flex justify-between text-sm mb-2">
                <span class="text-gray-500">Payment method</span>
                <span id="mPayment">-</span>
            </div>

            <div class="flex justify-between font-semibold plan-price"
                 id="mAmount"
                 data-price="">
                -
            </div>
        </div>

        <!-- RECEIPT -->
        <div class="text-right">
            <a id="mReceipt"
               href="#"
               target="_blank"
               class="hidden inline-flex items-center gap-2 text-sm px-4 py-2
                      border rounded-xl hover:bg-gray-100">
                📄 Download receipt
            </a>
        </div>

    </div>
</div>

<!-- ================= SCRIPT ================= -->
<script>
let currentModalData = null;

function openOrderModal(card) {
    closeOrderModal();

    currentModalData = {
        order: card.dataset.order,
        date: card.dataset.date,
        status: card.dataset.status,
        payment: card.dataset.payment,
        total: card.dataset.total,
        receipt: card.dataset.receipt
    };

    updateModalContent();

    document.getElementById('orderModal').classList.remove('hidden');
    document.getElementById('orderModal').classList.add('flex');
}

function updateModalContent() {
    if (!currentModalData) return;

    const statusMap = {
        0: ['Pending', 'bg-yellow-100 text-yellow-700'],
        1: ['Confirmed', 'bg-blue-100 text-blue-700'],
        2: ['Processing', 'bg-indigo-100 text-indigo-700'],
        3: ['Completed', 'bg-green-100 text-green-700'],
        4: ['Cancelled', 'bg-red-100 text-red-700'],
        5: ['Refunded', 'bg-purple-100 text-purple-700'],
        6: ['Failed', 'bg-red-100 text-red-700'],
    };

    document.getElementById('mOrder').textContent = currentModalData.order;
    document.getElementById('mDate').textContent = currentModalData.date;
    document.getElementById('mPayment').textContent = currentModalData.payment;

    const statusEl = document.getElementById('mStatus');
    const status = statusMap[currentModalData.status] || ['Unknown', 'bg-gray-100 text-gray-600'];
    statusEl.textContent = status[0];
    statusEl.className = `px-3 py-1 rounded-full text-xs ${status[1]}`;

    const amountEl = document.getElementById('mAmount');
    amountEl.dataset.price = currentModalData.total;
    amountEl.textContent = '';

    const receiptBtn = document.getElementById('mReceipt');
    if (parseInt(currentModalData.status) === 3) {
        receiptBtn.href = currentModalData.receipt;
        receiptBtn.classList.remove('hidden');
    }
}

function closeOrderModal() {
    const modal = document.getElementById('orderModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');

    currentModalData = null;

    document.getElementById('mOrder').textContent = '-';
    document.getElementById('mDate').textContent = '-';
    document.getElementById('mPayment').textContent = '-';

    const statusEl = document.getElementById('mStatus');
    statusEl.textContent = '-';
    statusEl.className = 'px-3 py-1 rounded-full text-xs bg-gray-100 text-gray-600';

    const amountEl = document.getElementById('mAmount');
    amountEl.dataset.price = '';
    amountEl.textContent = '-';

    document.getElementById('mReceipt').classList.add('hidden');
    document.getElementById('mReceipt').href = '#';
}

document.getElementById('orderModal').addEventListener('click', function (e) {
    if (e.target === this) closeOrderModal();
});
</script>

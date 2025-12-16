<div id="membership" >
    <div class="content-header mb-6">
        <h3 class="text-2xl font-bold text-gray-900">MIRA Vault</h3>
        <p class="text-sm text-gray-600 mt-1">Your secure wallet for eSIM credits & rewards</p>
    </div>

    <!-- Wallet Balance Card -->
    <div class="bg-gradient-to-r from-gray-600 to-gray-800 rounded-2xl p-6 text-white shadow-xl">
        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="text-gray-200 text-sm">Total Balance</p>
                <p class="text-4xl font-bold mt-2">
                    <span data-price-usd="{{ auth()->user()->wallet_balance ?? 0 }}" class="price-element inline-block">
                        {{ number_format(auth()->user()->wallet_balance ?? 0, 2) }}
                    </span>
                </p>
            </div>

            <div class="bg-white/20 backdrop-blur-sm rounded-xl p-4">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                </svg>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 mt-6">
            <div class="bg-white/10 backdrop-blur-sm rounded-lg p-3">
                <p class="text-xs opacity-90">Total Credits Earned</p>
                <p class="text-lg font-semibold">
                    <span data-price-usd="{{ auth()->user()->total_credits_earned ?? 0 }}" class="price-element">
                        {{ number_format(auth()->user()->total_credits_earned ?? 0, 2) }}
                    </span>
                   
                </p>
            </div>

            <div class="bg-white/10 backdrop-blur-sm rounded-lg p-3">
                <p class="text-xs opacity-90">Available for Use</p>
                <p class="text-lg font-semibold">
                    <span data-price-usd="{{ auth()->user()->wallet_balance ?? 0 }}" class="price-element">
                        {{ number_format(auth()->user()->wallet_balance ?? 0, 2) }}
                    </span>
                    
                </p>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="mt-8 cursor-pointer" id="openTransactionHistory">
        <h4 class="font-semibold text-gray-900 mb-4 flex items-center justify-between">
            Recent Activity
            <span class="text-sm text-gray-700 hover:text-gray-900 flex items-center gap-1">
                View all 
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </span>
        </h4>

        @if(auth()->user()->walletTransactions()->latest()->limit(5)->exists())
            <div class="space-y-3">
                @foreach(auth()->user()->walletTransactions()->latest()->limit(5)->get() as $tx)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center
                                        {{ $tx->type === 'credit' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if($tx->type === 'credit')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                    @endif
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ ucfirst($tx->source ?? $tx->type) }}</p>
                                <p class="text-xs text-gray-500">{{ $tx->created_at->format('d M Y • H:i') }}</p>
                            </div>
                        </div>
                        <p class="font-semibold {{ $tx->type === 'credit' ? 'text-green-600' : 'text-red-600' }}">
                            {{ $tx->type === 'credit' ? '+' : '-' }}
                            <span data-price-usd="{{ $tx->amount }}" class="price-element inline">
                                {{ number_format($tx->amount, 2) }}
                            </span>
                            <span class="currency-symbol ml-1">
                                {{ session('currency_symbol') ?? (auth()->user()->currency_symbol ?? '$') }}
                            </span>
                        </p>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-10 text-gray-500">
                <svg class="w-16 h-16 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <p>No transactions yet</p>
                <p class="text-xs mt-1">Your wallet activity will appear here</p>
            </div>
        @endif
    </div>
</div>

<!-- Full Transaction History Modal -->
<div id="transactionHistoryModal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col">
        <div class="p-6 border-b flex items-center justify-between">
            <h3 class="text-xl font-bold text-gray-900">Transaction History</h3>
            <button id="closeTransactionModal" class="p-2 hover:bg-gray-100 rounded-lg transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto p-6">
            @if(auth()->user()->walletTransactions()->latest()->exists())
                <div class="space-y-4">
                    @foreach(auth()->user()->walletTransactions()->latest()->get() as $tx)
                        <div class="flex items-center justify-between p-5 bg-gray-50 rounded-xl hover:bg-gray-100 transition">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-full flex items-center justify-center
                                            {{ $tx->type === 'credit' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if($tx->type === 'credit')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                        @endif
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ ucfirst($tx->source ?? $tx->type) }}</p>
                                    <p class="text-sm text-gray-500">{{ $tx->created_at->format('d F Y \a\t H:i') }}</p>
                                    @if($tx->description)
                                        <p class="text-xs text-gray-600 mt-1">{{ $tx->description }}</p>
                                    @endif
                                </div>
                            </div>
                            <p class="text-lg font-bold {{ $tx->type === 'credit' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $tx->type === 'credit' ? '+' : '-' }}
                                <span data-price-usd="{{ $tx->amount }}" class="price-element inline">
                                    {{ number_format($tx->amount, 2) }}
                                </span>
                               
                            </p>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 text-gray-500">
                    <p class="text-lg">No transactions found</p>
                </div>
            @endif
        </div>
    </div>
</div>

<div id="refer-earn" >
    <h3 class="text-lg font-semibold text-gray-900 mb-3">Refer & Earn</h3>
    <p class="text-sm text-gray-700">
        Share your referral code and earn rewards when someone signs up using your link.
    </p>

    <div class="mt-4 bg-gray-50 border border-gray-200 rounded-lg p-3">
        <label class="text-sm font-medium text-gray-800">Referral Link</label>
        <div class="flex items-center mt-1">
            <input
                type="text"
                readonly
                id="refLink"
                class="w-full text-sm border-gray-300 rounded-md px-2 py-1 bg-white"
                value="{{ auth()->user()->referral_link ?? url('/ref?code=' . auth()->user()->referral_code) }}"
            >
            <button type="button" id="copyRef" class="ml-2 text-gray-700 text-sm font-medium hover:underline focus:outline-none">
                Copy
            </button>
        </div>

        <p class="text-xs text-gray-600 mt-3">
            Share this link to earn credits when someone completes their first purchase.
        </p>

        <button type="button" id="openTerms" class="text-xs text-blue-600 hover:underline mt-2 inline-block focus:outline-none">
            Referral Terms
        </button>
    </div>

    <p class="text-xs text-gray-500 mt-4">
        Invited: <strong>{{ auth()->user()->referredUsers()->count() }}</strong> 
        • Earned: <strong>{{ auth()->user()->referral_earnings ?? 0 }} {{ auth()->user()->currency ?? '€' }}</strong>
    </p>
</div>

<!-- Referral Terms Modal -->
<div id="termsModal" class="fixed inset-0 bg-black/40 hidden justify-center items-center p-4 z-50">
    <div class="bg-white w-full max-w-sm rounded-xl shadow-lg p-6 relative">
        <button type="button" id="closeTerms" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <h4 class="text-lg font-semibold text-gray-900 mb-3">Referral Terms</h4>
        <div class="text-sm text-gray-700 leading-relaxed space-y-2">
            <p>• Credits are rewarded once a referred user completes their first purchase.</p>
            <p>• Referral credits cannot be exchanged for cash.</p>
            <p>• Each user must have a valid account to qualify.</p>
            <p>• Misuse of referral links may lead to account restrictions.</p>
        </div>

        <button type="button" id="closeTermsBottom" class="block w-full mt-6 text-center text-gray-700 text-sm font-medium hover:underline">
            Close
        </button>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Copy Referral Link
    document.getElementById('copyRef').addEventListener('click', function () {
        const linkInput = document.getElementById('refLink');
        linkInput.select();
        linkInput.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(linkInput.value).then(() => {
            // Optional: show toast "Copied!"
            const originalText = this.textContent;
            this.textContent = 'Copied!';
            this.classList.add('text-green-600');
            setTimeout(() => {
                this.textContent = originalText;
                this.classList.remove('text-green-600');
            }, 2000);
        });
    });

    // Open Terms Modal
    document.getElementById('openTerms').addEventListener('click', function (e) {
        e.preventDefault();
        document.getElementById('termsModal').classList.remove('hidden');
        document.getElementById('termsModal').classList.add('flex');
    });

    // Close Modal (both buttons + overlay)
    const closeModal = () => {
        document.getElementById('termsModal').classList.add('hidden');
        document.getElementById('termsModal').classList.remove('flex');
    };

    document.getElementById('closeTerms').addEventListener('click', closeModal);
    document.getElementById('closeTermsBottom').addEventListener('click', closeModal);

    // Close on backdrop click
    document.getElementById('termsModal').addEventListener('click', function (e) {
        if (e.target === this) closeModal();
    });
});
</script>
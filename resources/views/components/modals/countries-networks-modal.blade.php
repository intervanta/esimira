<style>
    /* Modal background */
    #countries-networks-modal {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.55);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }

    /* Modal box */
    .modal-content {
        background: #fff;
        width: 90%;
        max-width: 900px;
        height: 85vh; /* FULL HEIGHT MODAL */
        border-radius: 16px;
        position: relative;
        display: flex;
        flex-direction: column;
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }

    /* Sticky Header */
    .modal-header-wrapper {
        background: #fff;
        padding: 20px 24px;
        border-bottom: 1px solid #e5e7eb;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .modal-title {
        font-size: 20px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .modal-subtext {
        color: #6b7280;
        font-size: 14px;
        margin-top: 4px;
        line-height: 1.5;
    }

    /* Close button */
    .modal-close {
        font-size: 28px;
        background: none;
        border: none;
        cursor: pointer;
        color: #666;
        position: absolute;
        top: 16px;
        right: 20px;
    }

    /* Scrollable grid area */
    .countries-scroll-area {
        padding: 20px 24px;
        overflow-y: auto;
        flex: 1;
    }

    /* 4 Column Grid */
    .countries-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
    }

    /* Card */
    .country-card {
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 12px;
        transition: 0.25s;
    }
    .country-card:hover {
        box-shadow: 0 3px 10px rgba(0,0,0,0.10);
        transform: translateY(-2px);
    }

    .country-head {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 10px;
    }

    .country-head img {
        width: 26px;
        height: 26px;
        border-radius: 5px;
        object-fit: cover;
    }

    .network-item {
        background: #ffffff;
        padding: 6px 8px;
        border: 1px solid #e5e7eb;
        border-radius: 4px;
        margin-bottom: 6px;
        font-size: 12px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
</style>
<div id="countries-networks-modal">
    <div class="modal-backdrop" style="position:absolute; inset:0;"></div>

    <div class="modal-content">

        <!-- Close Button -->
        <button class="modal-close">&times;</button>

        <!-- Sticky Header -->
        <div class="modal-header-wrapper">
            <div class="modal-title">
                <i class="fa-solid fa-globe-americas" style="color:#2563eb;"></i>
                Coverage & Networks
            </div>

            <p class="modal-subtext">
                Complete list of supported countries and their local networks for this eSIM plan.
            </p>
        </div>

        <!-- Scrollable content -->
        <div class="countries-scroll-area">
            <div class="countries-grid">

                @foreach($networks as $network)
                    @php
                        $networkData = is_string($network) ? json_decode($network, true) : $network;
                        $countryTitle = $networkData['title'] ?? 'Unknown Country';
                        $localNetworks = $networkData['local_networks'] ?? [];
                        $countryImage = $networkData['image'] ?? '';
                    @endphp

                    <div class="country-card">
                        <div class="country-head">
                            @if($countryImage)
                                <img src="{{ asset('storage/' . $countryImage) }}" alt="{{ $countryTitle }}">
                            @endif

                            <strong>{{ $countryTitle }}</strong>
                        </div>

                        @foreach($localNetworks as $networkName)
                            <div class="network-item">
                                <i class="fa-solid fa-wifi" style="color:#10b981; font-size:12px;"></i>
                                {{ $networkName }}
                            </div>
                        @endforeach
                    </div>

                @endforeach

            </div>
        </div>

    </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("countries-networks-modal");
    const closeBtn = modal.querySelector(".modal-close");
    const backdrop = modal.querySelector(".modal-backdrop");

    closeBtn.addEventListener("click", () => modal.style.display = "none");
    backdrop.addEventListener("click", () => modal.style.display = "none");
});
</script>

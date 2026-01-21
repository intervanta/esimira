<style>
    /* Fullscreen Modal Background */
    #privacy-protected-modal {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.55);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }

    /* Modal Box */
    .privacy-modal-content {
        background: #fff;
        width: 90%;
        max-width: 420px;
        max-height: 80vh;
        border-radius: 14px;
        padding: 22px;
        overflow-y: auto;
        position: relative;
        box-shadow: 0px 8px 25px rgba(0, 0, 0, 0.15);
        animation: fadeIn 0.25s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: scale(0.96); }
        to { opacity: 1; transform: scale(1); }
    }

    /* Modal Header */
    .privacy-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 14px;
        border-bottom: 1px solid #e5e7eb;
        margin-bottom: 18px;
    }

    .privacy-modal-title {
        margin: 0;
        display: flex;
        align-items: center;
        font-size: 19px;
        gap: 8px;
        font-weight: 700;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 26px;
        cursor: pointer;
        color: #6b7280;
        transition: 0.2s;
    }
    .modal-close:hover {
        color: #000;
    }

    /* Info Cards */
    .privacy-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 14px;
        background: #f9fafb;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        margin-bottom: 12px;
        transition: 0.2s;
    }

    .privacy-item:hover {
        background: #f3f4f6;
    }

    .privacy-item strong {
        font-size: 15px;
        margin-bottom: 4px;
        display: block;
    }

    .privacy-item span {
        font-size: 14px;
        color: #6b7280;
        line-height: 1.4;
    }

    .privacy-icon {
        font-size: 18px;
        margin-top: 2px;
        color: #3b82f6;
    }
</style>
<div id="privacy-protected-modal" class="modal">
    <div class="modal-backdrop" style="position: absolute; inset: 0;"></div>

    <div class="privacy-modal-content">

        <!-- Header -->
        <div class="privacy-modal-header">
            <h3 class="privacy-modal-title">
                <i class="fa-solid fa-shield-halved" style="color:#3b82f6;"></i>
                Privacy & Security
            </h3>

            <button class="modal-close">&times;</button>
        </div>

        <!-- Privacy Items -->
        <div>

            <div class="privacy-item">
                <i class="fa-solid fa-lock privacy-icon"></i>
                <div>
                    <strong>Secure Connection</strong>
                    <span>All data is transmitted through fully encrypted channels.</span>
                </div>
            </div>

            <div class="privacy-item">
                <i class="fa-solid fa-eye-slash privacy-icon"></i>
                <div>
                    <strong>No Data Storage</strong>
                    <span>Your IP address or browsing activity is never stored.</span>
                </div>
            </div>

            <div class="privacy-item">
                <i class="fa-solid fa-shield privacy-icon"></i>
                <div>
                    <strong>GDPR Compliant</strong>
                    <span>We meet all global privacy and data protection regulations.</span>
                </div>
            </div>

        </div>

    </div>
</div>

<div id="region-modal" class="modal"
     style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.45); 
            z-index:1000; align-items:center; justify-content:center;">

    <div class="modal-backdrop" style="position:absolute; inset:0;"></div>

    <div style="
        background:#fff; 
        border-radius:8px; 
        width:90%; 
        max-width:380px; 
        max-height:80vh;
        overflow:hidden;
        z-index:1001;
        display:flex;
        flex-direction:column;
    ">

        <!-- Header -->
        <div style="
            padding:15px; 
            border-bottom:1px solid #e5e7eb;
            display:flex;
            justify-content:space-between;
            align-items:center;
            background:#fff;
            position:sticky;
            top:0;
            z-index:2;
        ">
            <h3 style="margin:0; font-size:17px; font-weight:600;">
                <i class="fa-solid fa-globe" style="color:#3b82f6; margin-right:6px;"></i>
                Region Info
            </h3>
            <button class="modal-close"
                style="background:none; border:none; font-size:22px; cursor:pointer; color:#6b7280;">
                &times;
            </button>
        </div>

        <!-- Scrollable Content -->
        <div style="padding:15px; overflow-y:auto;">

            <div style="
                padding:10px; 
                background:#f0f9ff; 
                border-radius:6px; 
                border-left:4px solid #f4633a;
                margin-bottom:12px;
            ">
                <strong>Region:</strong> {{ $region }}
            </div>

            <p style="font-size:14px; color:#6b7280; margin:0;">
                This eSIM supports the {{ $region }} region with reliable network coverage.
            </p>

        </div>
    </div>
</div>

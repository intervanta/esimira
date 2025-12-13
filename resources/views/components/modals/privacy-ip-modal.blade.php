<div id="privacy-ip-modal" class="modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div class="modal-backdrop" style="position: absolute; width: 100%; height: 100%;"></div>
    <div style="background: white; border-radius: 8px; padding: 20px; max-width: 400px; width: 90%; max-height: 80vh; overflow-y: auto; position: relative; z-index: 1001;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid #e5e7eb;">
            <h3 style="margin: 0; font-size: 18px; font-weight: 600;">
                <i class="fa-solid fa-location-dot" style="color: #3b82f6; margin-right: 8px;"></i>
                Privacy IP Information
            </h3>
            <button class="modal-close" style="background: none; border: none; font-size: 20px; cursor: pointer; color: #6b7280;">&times;</button>
        </div>
        <div>
            <div style="margin-bottom: 10px;">
                <strong>Your IP Location:</strong>
                <span>{{ $privacy_ip }}</span>
            </div>
            <div style="color: #6b7280; font-size: 14px;">
                We use your location to show relevant plans and pricing for your region.
            </div>
        </div>
    </div>
</div>
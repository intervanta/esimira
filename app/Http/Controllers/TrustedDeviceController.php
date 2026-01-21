<?php
// app/Http/Controllers/TrustedDeviceController.php

namespace App\Http\Controllers;

use App\Models\TrustedDevice;
use App\Services\TrustedDeviceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrustedDeviceController extends Controller
{
    protected $trustedDeviceService;

    public function __construct(TrustedDeviceService $trustedDeviceService)
    {
        $this->trustedDeviceService = $trustedDeviceService;
    }

    /**
     * Get user's trusted devices
     */
    public function index()
    {
        $user = Auth::user();
        $devices = $user->trustedDevices()
            ->orderBy('last_login_at', 'desc')
            ->get()
            ->map(function ($device) {
                return [
                    'id' => $device->id,
                    'device_id' => $device->device_id,
                    'device_type' => $device->device_type,
                    'platform' => $device->platform,
                    'browser' => $device->browser,
                    'ip_address' => $device->ip_address,
                    'location' => $device->location,
                    'last_login_at' => $device->last_login_at->toISOString(),
                    'expires_at' => $device->expires_at->toISOString(),
                    'is_expired' => $device->isExpired(),
                ];
            });

        return response()->json([
            'devices' => $devices,
            'current_device_id' => $this->trustedDeviceService->generateDeviceId(request()),
        ]);
    }

    /**
     * Remove a trusted device
     */
    public function destroy($deviceId)
    {
        $user = Auth::user();
        
        try {
            $success = $this->trustedDeviceService->removeTrustedDevice($user, $deviceId);
            
            if ($success) {
                return response()->json([
                    'success' => true,
                    'message' => 'Device removed successfully'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Device not found'
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove device'
            ], 500);
        }
    }

    /**
     * Remove all other devices
     */
    public function removeOthers()
    {
        $user = Auth::user();
        
        try {
            $removedCount = $this->trustedDeviceService->removeOtherDevices($user, request());
            
            return response()->json([
                'success' => true,
                'message' => "Removed {$removedCount} other devices",
                'removed_count' => $removedCount
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove devices'
            ], 500);
        }
    }
}
<?php


namespace App\Services;

use App\Models\TrustedDevice;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Stevebauman\Location\Facades\Location;

class TrustedDeviceService
{
    /**
     * Generate unique device ID based on browser fingerprint
     */
    public function generateDeviceId(Request $request): string
    {
        $fingerprint = collect([
            $request->userAgent(),
            $request->ip(),
            $request->header('Accept-Language'),
            $request->header('Accept-Encoding'),
        ])->implode('|');

        return hash('sha256', $fingerprint);
    }

    /**
     * Add current device as trusted
     */
    public function addTrustedDevice(Customer $customer, Request $request, bool $remember = false): TrustedDevice
    {
        $deviceId = $this->generateDeviceId($request);
        
        // Get location data
        $location = $this->getLocation($request->ip());
        
        // Determine device type
        $deviceType = $this->getDeviceType($request->userAgent());
        
        // Determine expiration (30 days for "remember me", 90 days for trusted devices)
        $expiration = $remember ? now()->addDays(30) : now()->addDays(90);

        // Remove existing device entry if any
        TrustedDevice::where('device_id', $deviceId)->delete();

        return TrustedDevice::create([
            'customer_id' => $customer->id,
            'device_id' => $deviceId,
            'device_type' => $deviceType,
            'platform' => $this->getPlatform($request->userAgent()),
            'browser' => $this->getBrowser($request->userAgent()),
            'ip_address' => $request->ip(),
            'location' => $location,
            'last_login_at' => now(),
            'expires_at' => $expiration,
        ]);
    }

    /**
     * Check if device is trusted
     */
    public function isDeviceTrusted(Customer $customer, Request $request): bool
    {
        $deviceId = $this->generateDeviceId($request);

        return $customer->activeTrustedDevices()
            ->where('device_id', $deviceId)
            ->exists();
    }

    /**
     * Remove trusted device
     */
    public function removeTrustedDevice(Customer $customer, string $deviceId): bool
    {
        return (bool) $customer->trustedDevices()
            ->where('device_id', $deviceId)
            ->delete();
    }

    /**
     * Remove all trusted devices except current
     */
    public function removeOtherDevices(Customer $customer, Request $request): int
    {
        $currentDeviceId = $this->generateDeviceId($request);

        return $customer->trustedDevices()
            ->where('device_id', '!=', $currentDeviceId)
            ->delete();
    }

    /**
     * Get location from IP
     */
    protected function getLocation(?string $ip): ?string
    {
        if (!$ip || $ip === '127.0.0.1') {
            return 'Localhost';
        }

        return Cache::remember("location_{$ip}", 3600, function () use ($ip) {
            $position = Location::get($ip);
            
            if ($position) {
                return collect([$position->cityName, $position->regionName, $position->countryCode])
                    ->filter()
                    ->implode(', ');
            }

            return 'Unknown Location';
        });
    }

    /**
     * Detect device type from user agent
     */
    protected function getDeviceType(string $userAgent): string
    {
        if (preg_match('/(mobile|android|iphone|ipod|blackberry|opera mini)/i', $userAgent)) {
            return 'mobile';
        } elseif (preg_match('/(tablet|ipad|kindle|silk)/i', $userAgent)) {
            return 'tablet';
        }

        return 'desktop';
    }

    /**
     * Get platform from user agent
     */
    protected function getPlatform(string $userAgent): string
    {
        $platforms = [
            'windows' => 'Windows',
            'macintosh|mac os' => 'macOS',
            'linux' => 'Linux',
            'android' => 'Android',
            'iphone|ipad' => 'iOS',
        ];

        foreach ($platforms as $pattern => $name) {
            if (preg_match("/{$pattern}/i", $userAgent)) {
                return $name;
            }
        }

        return 'Unknown';
    }

    /**
     * Get browser from user agent
     */
    protected function getBrowser(string $userAgent): string
    {
        $browsers = [
            'chrome' => 'Chrome',
            'firefox' => 'Firefox',
            'safari' => 'Safari',
            'edge|edg' => 'Edge',
            'opera' => 'Opera',
        ];

        foreach ($browsers as $pattern => $name) {
            if (preg_match("/{$pattern}/i", $userAgent)) {
                return $name;
            }
        }

        return 'Unknown';
    }

    /**
     * Clean up expired devices
     */
    public function cleanupExpiredDevices(): int
    {
        return TrustedDevice::where('expires_at', '<', now())->delete();
    }
}
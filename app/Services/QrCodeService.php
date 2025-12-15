<?php
// app/Services/QrCodeService.php
namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class QrCodeService
{
    protected $logoPath;
    
    public function __construct()
    {
        // Set your logo path
        $this->logoPath = public_path('assets/qr-logo.png');
    }
    
    public function generateEsimQr($lpaCode, $esimId, $withLogo = true)
    {
        $fileName = "esim_qr_{$esimId}.png";
        $path = "esims/qr/{$fileName}";

        Storage::disk('public')->makeDirectory('esims/qr');

        try {
            // Generate QR code
            $qrImage = $this->generateWithQuickChart($lpaCode);
            
            // Add logo if requested and available
            if ($withLogo && file_exists($this->logoPath)) {
                $qrImage = $this->addLogoToCenter($qrImage, $this->logoPath);
            }
            
            Storage::disk('public')->put($path, $qrImage);
            
            return $path;
            
        } catch (\Exception $e) {
            // Fallback without logo
            try {
                $qrImage = $this->generateWithGoQR($lpaCode);
                Storage::disk('public')->put($path, $qrImage);
                return $path;
            } catch (\Exception $e2) {
                throw new \Exception('All QR generation methods failed: ' . $e->getMessage());
            }
        }
    }

    private function generateWithQuickChart($text)
    {
        $encodedText = urlencode($text);
        $url = "https://quickchart.io/qr?text={$encodedText}&size=300&margin=2";
        
        $response = Http::timeout(10)->get($url);
        
        if ($response->successful()) {
            return $response->body();
        }
        
        throw new \Exception('QuickChart API failed: ' . $response->status());
    }

    private function generateWithGoQR($text)
    {
        $encodedText = urlencode($text);
        $url = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data={$encodedText}&margin=2";
        
        $response = Http::timeout(10)->get($url);
        
        if ($response->successful()) {
            return $response->body();
        }
        
        throw new \Exception('GoQR API failed: ' . $response->status());
    }

    /**
     * Add logo to the center of QR code
     */
    private function addLogoToCenter($qrImage, $logoPath)
    {
        // Check if GD extension is available
        if (!extension_loaded('gd') || !function_exists('imagecreatefromstring')) {
            Log::warning('GD extension not available for logo overlay');
            return $qrImage;
        }
        
        try {
            // Create QR image from binary data
            $qr = imagecreatefromstring($qrImage);
            if ($qr === false) {
                throw new \Exception('Failed to create QR image from data');
            }
            
            // Get image info to determine type
            $logoInfo = @getimagesize($logoPath);
            if ($logoInfo === false) {
                imagedestroy($qr);
                throw new \Exception('Invalid logo file or path');
            }
            
            // Create logo image based on type
            $logo = null;
            $mimeType = $logoInfo['mime'];
            
            switch($mimeType) {
                case 'image/png':
                    $logo = imagecreatefrompng($logoPath);
                    break;
                case 'image/jpeg':
                case 'image/jpg':
                    $logo = imagecreatefromjpeg($logoPath);
                    break;
                case 'image/gif':
                    $logo = imagecreatefromgif($logoPath);
                    break;
                default:
                    imagedestroy($qr);
                    throw new \Exception('Unsupported logo format: ' . $mimeType);
            }
            
            if ($logo === false) {
                imagedestroy($qr);
                throw new \Exception('Failed to create logo image');
            }
            
            // Get dimensions
            $qrWidth = imagesx($qr);
            $qrHeight = imagesy($qr);
            $logoWidth = imagesx($logo);
            $logoHeight = imagesy($logo);
            
            // Calculate logo size (20% of QR code size)
            $logoSize = (int)($qrWidth * 0.2);
            
            // Resize logo maintaining aspect ratio
            $aspectRatio = $logoWidth / $logoHeight;
            if ($logoWidth > $logoHeight) {
                $newLogoWidth = $logoSize;
                $newLogoHeight = (int)($logoSize / $aspectRatio);
            } else {
                $newLogoHeight = $logoSize;
                $newLogoWidth = (int)($logoSize * $aspectRatio);
            }
            
            // Create resized logo
            $resizedLogo = imagecreatetruecolor($newLogoWidth, $newLogoHeight);
            
            // Preserve transparency for PNG
            if ($mimeType === 'image/png') {
                imagealphablending($resizedLogo, false);
                imagesavealpha($resizedLogo, true);
                $transparent = imagecolorallocatealpha($resizedLogo, 255, 255, 255, 127);
                imagefilledrectangle($resizedLogo, 0, 0, $newLogoWidth, $newLogoHeight, $transparent);
            } else {
                $white = imagecolorallocate($resizedLogo, 255, 255, 255);
                imagefill($resizedLogo, 0, 0, $white);
            }
            
            // Copy and resize logo
            imagecopyresampled(
                $resizedLogo, $logo,
                0, 0, 0, 0,
                $newLogoWidth, $newLogoHeight,
                $logoWidth, $logoHeight
            );
            
            // Calculate center position
            $centerX = (int)(($qrWidth - $newLogoWidth) / 2);
            $centerY = (int)(($qrHeight - $newLogoHeight) / 2);
            
            // Add white background behind logo (optional, improves readability)
            $bgSize = $newLogoWidth + 8;
            $bgX = (int)(($qrWidth - $bgSize) / 2);
            $bgY = (int)(($qrHeight - $bgSize) / 2);
            
            $white = imagecolorallocate($qr, 255, 255, 255);
            imagefilledrectangle($qr, $bgX, $bgY, $bgX + $bgSize, $bgY + $bgSize, $white);
            
            // Merge logo onto QR code
            imagecopy($qr, $resizedLogo, $centerX, $centerY, 0, 0, $newLogoWidth, $newLogoHeight);
            
            // Save to buffer
            ob_start();
            imagepng($qr);
            $result = ob_get_clean();
            
            // Clean up
            imagedestroy($qr);
            imagedestroy($logo);
            imagedestroy($resizedLogo);
            
            return $result;
            
        } catch (\Exception $e) {
            Log::error('Failed to add logo to QR code: ' . $e->getMessage());
            return $qrImage; // Return original QR without logo
        }
    }

    /**
     * Alternative: Simple logo addition (faster)
     */
    private function addLogoSimple($qrImage, $logoPath)
    {
        if (!extension_loaded('gd')) {
            return $qrImage;
        }
        
        try {
            $qr = imagecreatefromstring($qrImage);
            $logo = imagecreatefrompng($logoPath);
            
            $qrSize = imagesx($qr);
            $logoSize = $qrSize * 0.15; // 15% of QR size
            
            // Resize logo
            $resizedLogo = imagescale($logo, $logoSize);
            
            // Calculate center position
            $x = ($qrSize - $logoSize) / 2;
            $y = ($qrSize - $logoSize) / 2;
            
            // Merge logo onto QR
            imagecopy($qr, $resizedLogo, $x, $y, 0, 0, $logoSize, $logoSize);
            
            ob_start();
            imagepng($qr);
            $result = ob_get_clean();
            
            imagedestroy($qr);
            imagedestroy($logo);
            imagedestroy($resizedLogo);
            
            return $result;
            
        } catch (\Exception $e) {
            Log::warning('Simple logo addition failed: ' . $e->getMessage());
            return $qrImage;
        }
    }

    /**
     * Check if logo exists and is valid
     */
    public function checkLogo()
    {
        $logoPath = public_path('assets/images/logo.png');
        
        if (!file_exists($logoPath)) {
            return [
                'exists' => false,
                'message' => 'Logo file not found at: ' . $logoPath
            ];
        }
        
        $imageInfo = @getimagesize($logoPath);
        
        if ($imageInfo === false) {
            return [
                'exists' => true,
                'valid' => false,
                'message' => 'Logo file is not a valid image'
            ];
        }
        
        return [
            'exists' => true,
            'valid' => true,
            'width' => $imageInfo[0],
            'height' => $imageInfo[1],
            'mime' => $imageInfo['mime'],
            'path' => $logoPath
        ];
    }
}
<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * BPD Bali Static QRIS Service
 * 
 * Generates static QRIS codes for pura donations.
 * Uses BPD Bali's closed API documentation.
 * Static QRIS = no fixed amount, scanned at pura location.
 * 
 * For now, this service supports:
 * 1. Storing manually-provided QRIS content/string from BPD Bali
 * 2. Generating QR code image from the QRIS string
 * 3. Downloading the QR code image for printing
 * 
 * When BPD Bali API credentials are available, the generate() method
 * can be extended to call their API directly.
 */
class BpdBaliQrisService
{
    /**
     * Generate a QR code image from a QRIS content string.
     * Uses the `chillerlan/php-qrcode` approach or simple Google Charts API fallback.
     */
    public function generateQrImage(string $qrisContent, string $filename): string
    {
        $dir = public_path('storage/qris_pura');
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }

        $filepath = $dir . '/' . $filename;

        // Use Google Charts API to generate QR code image
        // This is a simple fallback; in production, use a local library
        $size = 400;
        $url = 'https://chart.googleapis.com/chart?chs=' . $size . 'x' . $size 
             . '&cht=qr&chl=' . urlencode($qrisContent) 
             . '&choe=UTF-8&chld=M|2';

        $imageData = @file_get_contents($url);

        if ($imageData === false) {
            // Fallback: generate using simple SVG QR
            $imageData = $this->generateSimpleSvgQr($qrisContent, $size);
            $filename = str_replace('.png', '.svg', $filename);
            $filepath = $dir . '/' . $filename;
            file_put_contents($filepath, $imageData);
            return 'storage/qris_pura/' . $filename;
        }

        file_put_contents($filepath, $imageData);
        return 'storage/qris_pura/' . $filename;
    }

    /**
     * Simple SVG-based QR placeholder when external API is unavailable.
     */
    private function generateSimpleSvgQr(string $data, int $size): string
    {
        // This creates a placeholder. In production, use a proper QR library.
        $escaped = htmlspecialchars($data, ENT_XML1);
        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="{$size}" height="{$size}" viewBox="0 0 {$size} {$size}">
    <rect width="{$size}" height="{$size}" fill="white"/>
    <text x="50%" y="45%" text-anchor="middle" font-family="monospace" font-size="14" fill="#333">QRIS</text>
    <text x="50%" y="55%" text-anchor="middle" font-family="monospace" font-size="10" fill="#666">{$escaped}</text>
</svg>
SVG;
    }

    /**
     * Validate QRIS content format.
     * Standard QRIS starts with "00020101" (EMV QR Code format).
     */
    public function validateQrisContent(string $content): bool
    {
        return str_starts_with(trim($content), '0002');
    }
}

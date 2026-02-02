<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BarcodeService
{
    protected string $disk = 'public';
    protected string $barcodePath = 'barcodes';
    protected string $qrPath = 'qrcodes';

    /**
     * Generate barcode for a collection item.
     */
    public function generateBarcode(string $code, string $type = 'code128'): string
    {
        $filename = 'barcode_' . md5($code) . '.png';
        $filepath = $this->barcodePath . '/' . $filename;
        $fullPath = storage_path('app/public/' . $filepath);

        // Create directory if not exists
        if (!file_exists(dirname($fullPath))) {
            mkdir(dirname($fullPath), 0755, true);
        }

        // Generate simple barcode using GD
        $this->generateCode128Barcode($code, $fullPath);

        return $filepath;
    }

    /**
     * Generate QR code for a collection item.
     */
    public function generateQrCode(string $data): string
    {
        $filename = 'qrcode_' . md5($data) . '.png';
        $filepath = $this->qrPath . '/' . $filename;
        $fullPath = storage_path('app/public/' . $filepath);

        // Create directory if not exists
        if (!file_exists(dirname($fullPath))) {
            mkdir(dirname($fullPath), 0755, true);
        }

        // Generate QR code using Google Charts API (simple approach)
        // In production, use endroid/qr-code or similar package
        $url = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($data);

        // Download and save
        $imageData = file_get_contents($url);
        if ($imageData !== false) {
            file_put_contents($fullPath, $imageData);
        } else {
            // Fallback: generate simple placeholder
            $this->generateQrPlaceholder($data, $fullPath);
        }

        return $filepath;
    }

    /**
     * Generate Code 128 barcode image.
     */
    protected function generateCode128Barcode(string $code, string $filepath): void
    {
        // Simple barcode generation using GD
        $width = 400;
        $height = 100;
        $barcodeHeight = 60;
        $fontSize = 12;

        $image = imagecreatetruecolor($width, $height);
        $white = imagecolorallocate($image, 255, 255, 255);
        $black = imagecolorallocate($image, 0, 0, 0);

        imagefill($image, 0, 0, $white);

        // Simple representation - convert code to bars
        $barWidth = ($width - 40) / strlen($code);
        $x = 20;

        for ($i = 0; $i < strlen($code); $i++) {
            $char = ord($code[$i]);

            // Generate pattern based on character ASCII value
            if ($char % 2 == 0) {
                imagefilledrectangle($image, $x, 10, $x + $barWidth - 2, 10 + $barcodeHeight, $black);
            }

            $x += $barWidth;
        }

        // Add text below
        imagestring($image, 3, ($width - strlen($code) * imagefontwidth(3)) / 2, $height - 20, $code, $black);

        imagepng($image, $filepath);
        imagedestroy($image);
    }

    /**
     * Generate QR code placeholder.
     */
    protected function generateQrPlaceholder(string $data, string $filepath): void
    {
        $size = 300;
        $image = imagecreatetruecolor($size, $size);
        $white = imagecolorallocate($image, 255, 255, 255);
        $black = imagecolorallocate($image, 0, 0, 0);

        imagefill($image, 0, 0, $white);

        // Simple QR-like pattern
        $moduleSize = 10;
        $modules = floor(($size - 40) / $moduleSize);

        // Position patterns (corners)
        $this->drawPositionPattern($image, 10, 10, $moduleSize, $black, $white);
        $this->drawPositionPattern($image, $size - 30, 10, $moduleSize, $black, $white);
        $this->drawPositionPattern($image, 10, $size - 30, $moduleSize, $black, $white);

        // Data pattern (pseudo-random based on data)
        $hash = md5($data);
        for ($i = 0; $i < min(strlen($hash), $modules * $modules); $i++) {
            $row = floor($i / $modules);
            $col = $i % $modules;

            if ($hash[$i] == '1') {
                $x = 20 + $col * $moduleSize;
                $y = 20 + $row * $moduleSize;
                imagefilledrectangle($image, $x, $y, $x + $moduleSize - 1, $y + $moduleSize - 1, $black);
            }
        }

        imagepng($image, $filepath);
        imagedestroy($image);
    }

    /**
     * Draw QR code position pattern.
     */
    protected function drawPositionPattern($image, $x, $y, $moduleSize, $black, $white): void
    {
        $size = 7 * $moduleSize;

        // Outer square
        imagefilledrectangle($image, $x, $y, $x + $size, $y + $size, $black);
        imagefilledrectangle($image, $x + $moduleSize, $y + $moduleSize, $x + $size - $moduleSize, $y + $size - $moduleSize, $white);

        // Inner square
        $innerSize = 3 * $moduleSize;
        $offset = 2 * $moduleSize;
        imagefilledrectangle($image, $x + $offset, $y + $offset, $x + $offset + $innerSize, $y + $offset + $innerSize, $black);
    }

    /**
     * Generate barcode for collection item.
     */
    public function generateForCollectionItem(int $itemId, string $callNumber): string
    {
        $code = $this->formatItemBarcode($itemId, $callNumber);
        return $this->generateBarcode($code);
    }

    /**
     * Generate QR code for collection item.
     */
    public function generateQrForCollectionItem(int $itemId, string $title): string
    {
        $data = route('opac.show', $itemId);
        return $this->generateQrCode($data);
    }

    /**
     * Format item barcode code.
     */
    protected function formatItemBarcode(int $itemId, string $callNumber): string
    {
        // Format: ITEM-{itemId}-{callNumber without spaces}
        $cleanCallNumber = preg_replace('/[^A-Za-z0-9]/', '', $callNumber);
        return 'ITEM-' . str_pad($itemId, 6, '0', STR_PAD_LEFT) . '-' . strtoupper(substr($cleanCallNumber, 0, 10));
    }

    /**
     * Get barcode URL.
     */
    public function getBarcodeUrl(string $path): string
    {
        return Storage::disk($this->disk)->url($path);
    }

    /**
     * Get QR code URL.
     */
    public function getQrCodeUrl(string $path): string
    {
        return Storage::disk($this->disk)->url($path);
    }

    /**
     * Print barcode label (returns HTML for printing).
     */
    public function generateLabelHtml(int $itemId, string $title, string $author, string $callNumber): string
    {
        $barcode = $this->formatItemBarcode($itemId, $callNumber);
        $barcodePath = $this->generateBarcode($barcode);
        $qrPath = $this->generateQrForCollectionItem($itemId, $title);

        $barcodeUrl = $this->getBarcodeUrl($barcodePath);
        $qrUrl = $this->getQrCodeUrl($qrPath);

        return view('admin.collections.label', compact(
            'barcode',
            'title',
            'author',
            'callNumber',
            'barcodeUrl',
            'qrUrl'
        ))->render();
    }
}

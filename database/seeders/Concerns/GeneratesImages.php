<?php

namespace Database\Seeders\Concerns;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

trait GeneratesImages
{
    /**
     * Membuat gambar PNG gradasi dengan label, disimpan di disk "public".
     * Mengembalikan path relatif (untuk kolom database).
     */
    protected function makeImage(string $path, int $width, int $height, string $label, array $rgb): string
    {
        if (! function_exists('imagecreatetruecolor')) {
            throw new RuntimeException('Ekstensi PHP GD belum aktif. Aktifkan "gd" di php.ini.');
        }

        $image = imagecreatetruecolor($width, $height);

        // Gradasi vertikal: dari warna dasar ke versi lebih gelap
        for ($y = 0; $y < $height; $y++) {
            $ratio = $y / max($height - 1, 1);
            $color = imagecolorallocate(
                $image,
                (int) ($rgb[0] * (1 - 0.45 * $ratio)),
                (int) ($rgb[1] * (1 - 0.45 * $ratio)),
                (int) ($rgb[2] * (1 - 0.45 * $ratio)),
            );
            imageline($image, 0, $y, $width, $y, $color);
        }

        // Label di tengah (font bawaan GD, hanya ASCII)
        $text = Str::ascii($label);
        $font = 5;
        $textWidth = imagefontwidth($font) * strlen($text);
        $textHeight = imagefontheight($font);
        $white = imagecolorallocate($image, 255, 255, 255);
        imagestring($image, $font, (int) (($width - $textWidth) / 2), (int) (($height - $textHeight) / 2), $text, $white);

        ob_start();
        imagepng($image);
        $binary = ob_get_clean();
        imagedestroy($image);

        Storage::disk('public')->put($path, $binary);

        return $path;
    }
}

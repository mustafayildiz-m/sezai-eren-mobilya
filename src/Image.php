<?php
declare(strict_types=1);
namespace App;

final class Image
{
    public const MAX_BYTES = 20 * 1024 * 1024;
    public const MAX_WIDTH = 1600;
    public const THUMB_WIDTH = 480;
    public const QUALITY = 82;
    private const ALLOWED = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];

    /** Returns an error message (Turkish) or null when the uploaded file looks fine. */
    public static function validate(array $file, int $maxBytes = self::MAX_BYTES): ?string
    {
        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) return 'Dosya yüklenemedi.';
        if (($file['size'] ?? 0) > $maxBytes) return 'Dosya çok büyük (en fazla ' . intdiv($maxBytes, 1024 * 1024) . ' MB).';
        $tmp = $file['tmp_name'] ?? '';
        if (!is_file($tmp)) return 'Geçersiz dosya.';
        $mime = mime_content_type($tmp) ?: '';
        if (!in_array($mime, self::ALLOWED, true)) return 'Yalnızca JPG, PNG veya WebP yükleyebilirsiniz.';
        return null;
    }

    /** @return array{filename:string, thumb:string} */
    public static function process(string $tmpPath, string $destDir): array
    {
        $data = @file_get_contents($tmpPath);
        $src = $data !== false ? @imagecreatefromstring($data) : false;
        if ($src === false) throw new \InvalidArgumentException('Dosya bir görsel değil.');
        if (!is_dir($destDir)) mkdir($destDir, 0775, true);

        $src = self::fixOrientation($src, $tmpPath);
        $id = bin2hex(random_bytes(8));
        $main = "$id.webp";
        $thumb = "{$id}_thumb.webp";

        self::save(self::fit($src, self::MAX_WIDTH), "$destDir/$main");
        self::save(self::fit($src, self::THUMB_WIDTH), "$destDir/$thumb");
        imagedestroy($src);

        return ['filename' => $main, 'thumb' => $thumb];
    }

    private static function fit(\GdImage $im, int $maxWidth): \GdImage
    {
        $w = imagesx($im);
        if ($w <= $maxWidth) return $im;
        $out = imagescale($im, $maxWidth, -1, IMG_BILINEAR_FIXED);
        if ($out === false) throw new \RuntimeException('Görsel küçültülemedi.');
        return $out;
    }

    private static function save(\GdImage $im, string $path): void
    {
        imagepalettetotruecolor($im);
        imagealphablending($im, true);
        imagesavealpha($im, true);
        if (!imagewebp($im, $path, self::QUALITY)) throw new \RuntimeException('WebP yazılamadı.');
    }

    private static function fixOrientation(\GdImage $im, string $path): \GdImage
    {
        if (!function_exists('exif_read_data')) return $im;
        $exif = @exif_read_data($path);
        $o = (int) ($exif['Orientation'] ?? 1);
        $rot = match ($o) { 3 => 180, 6 => -90, 8 => 90, default => 0 };
        if ($rot === 0) return $im;
        $r = imagerotate($im, $rot, 0);
        return $r === false ? $im : $r;
    }
}

<?php
declare(strict_types=1);
namespace App;

/** Tiny file-based sliding-window rate limiter. Good enough for a single-server brochure site. */
final class RateLimit
{
    public static ?string $dir = null;

    public static function hit(string $key, int $max, int $windowSec, ?int $now = null): bool
    {
        $now ??= time();
        $dir = self::$dir ?? BASE_PATH . '/storage/ratelimit';
        if (!is_dir($dir)) mkdir($dir, 0775, true);
        $file = $dir . '/' . sha1($key) . '.json';

        $fh = fopen($file, 'c+');
        if ($fh === false) return true; // fail open rather than blocking legit users
        flock($fh, LOCK_EX);
        $raw = stream_get_contents($fh);
        $hits = $raw ? (json_decode($raw, true) ?: []) : [];
        $hits = array_values(array_filter($hits, fn($t) => $t > $now - $windowSec));

        $allowed = count($hits) < $max;
        if ($allowed) $hits[] = $now;

        ftruncate($fh, 0); rewind($fh);
        fwrite($fh, json_encode($hits));
        flock($fh, LOCK_UN); fclose($fh);
        return $allowed;
    }
}

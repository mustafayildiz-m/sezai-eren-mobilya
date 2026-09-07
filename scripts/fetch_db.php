<?php
/**
 * Canlı veritabanının yedeğini FTP ile indirir.
 *
 *   php scripts/fetch_db.php <hedef-klasor>
 *
 * deploy.sh --with-data canlı veritabanının ÜZERİNE YAZDIĞI için, içeri
 * aktarma işlemlerinden önce mutlaka bunu çalıştırın: teklif formu kayıtları
 * ve panelden eklenmiş projeler yalnızca canlı veritabanında bulunur.
 */
declare(strict_types=1);

$proj = dirname(__DIR__);
$envFile = "$proj/.env.deploy";
if (!is_file($envFile)) { fwrite(STDERR, ".env.deploy yok.\n"); exit(1); }
foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
    if (str_starts_with(trim($line), '#') || !str_contains($line, '=')) continue;
    [$k, $v] = array_map('trim', explode('=', $line, 2));
    putenv("$k=$v");
}

$dest = rtrim($argv[1] ?? "$proj/storage/canli-yedek", '/');
if (!is_dir($dest)) mkdir($dest, 0775, true);

$host = getenv('FTP_HOST') ?: '';
$appDir = getenv('REMOTE_APP') ?: '/app';
$webDir = getenv('REMOTE_WEB') ?: '/public_html';

$c = ftp_connect($host, 21, 30) ?: exit("Bağlanılamadı: $host\n");
ftp_login($c, getenv('FTP_USER') ?: '', getenv('FTP_PASS') ?: '') ?: exit("Giriş başarısız.\n");
ftp_pasv($c, true);

$stamp = date('Ymd-His');
$dbLocal = "$dest/database-$stamp.sqlite";
if (ftp_get($c, $dbLocal, "$appDir/storage/database.sqlite", FTP_BINARY)) {
    echo "veritabanı yedeği: $dbLocal (" . number_format(filesize($dbLocal)) . " bayt)\n";
} else {
    fwrite(STDERR, "Veritabanı indirilemedi: $appDir/storage/database.sqlite\n");
    exit(1);
}

$uploads = ftp_nlist($c, "$webDir/uploads");
echo "canlı uploads dosya sayısı: " . (is_array($uploads) ? count($uploads) : 'listelenemedi') . "\n";

ftp_close($c);

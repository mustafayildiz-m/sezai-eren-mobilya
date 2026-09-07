<?php
/**
 * FTP ile paylaşımlı hostinge yükleme. scripts/deploy.sh üzerinden çağırın.
 * Yerleşim: /app (kod, web dışı) + /public_html (public/ içeriği).
 */
declare(strict_types=1);

$proj = dirname(__DIR__);
$host = getenv('FTP_HOST') ?: '';
$user = getenv('FTP_USER') ?: '';
$pass = getenv('FTP_PASS') ?: '';
$appDir = getenv('REMOTE_APP') ?: '/app';
$webDir = getenv('REMOTE_WEB') ?: '/public_html';
$withData = getenv('WITH_DATA') === '1';

if (!$host || !$user || !$pass) { fwrite(STDERR, "FTP_HOST/FTP_USER/FTP_PASS eksik.\n"); exit(1); }

$c = ftp_connect($host, 21, 30) ?: exit("Bağlanılamadı: $host\n");
ftp_login($c, $user, $pass) ?: exit("Giriş başarısız: $user\n");
ftp_pasv($c, true);

$n = 0;
$mkd = function (string $d) use ($c): void {
    if (!@ftp_chdir($c, $d)) @ftp_mkdir($c, $d);
    @ftp_chdir($c, '/');
};
$put = function (string $local, string $remote) use ($c, &$n): void {
    if (!ftp_put($c, $remote, $local, FTP_BINARY)) { echo "  HATA: $remote\n"; return; }
    if (++$n % 25 === 0) echo "  ...$n dosya\n";
};
$sync = function (string $localDir, string $remoteDir, array $skip = []) use (&$sync, $mkd, $put): void {
    $mkd($remoteDir);
    foreach (scandir($localDir) ?: [] as $f) {
        if ($f === '.' || $f === '..' || $f === '.DS_Store') continue;
        $lp = "$localDir/$f"; $rp = "$remoteDir/$f";
        if (in_array($f, $skip, true)) { echo "  atlandı: $rp\n"; continue; }
        is_dir($lp) ? $sync($lp, $rp, $skip) : $put($lp, $rp);
    }
};

echo "== kod: $appDir ==\n";
$mkd($appDir);
$sync("$proj/src", "$appDir/src");
$sync("$proj/templates", "$appDir/templates");
$mkd("$appDir/storage");
$mkd("$appDir/storage/ratelimit");

echo "== web: $webDir ==\n";
// uploads/ canlıda panelden yüklenir; sadece --with-data ile gönderilir.
$sync("$proj/public", $webDir, $withData ? [] : ['uploads']);

if ($withData) {
    echo "== veri (DİKKAT: canlı veritabanının üzerine yazar) ==\n";
    if (is_file("$proj/storage/database.sqlite")) $put("$proj/storage/database.sqlite", "$appDir/storage/database.sqlite");
    if (is_dir("$proj/public/uploads")) $sync("$proj/public/uploads", "$webDir/uploads");
}

echo "== izinler ==\n";
foreach ([[$appDir . '/storage', 0755], [$appDir . '/storage/ratelimit', 0775], [$webDir . '/uploads', 0775]] as [$p, $m]) {
    echo (@ftp_chmod($c, $m, $p) ? '  ok   ' : '  fail ') . decoct($m) . " $p\n";
}

// OPcache sıfırla: aksi halde sunucu eski PHP dosyalarını servis etmeye devam eder
$appUrl = rtrim(getenv('APP_URL') ?: '', '/');
if ($appUrl !== '') {
    echo "== opcache ==\n";
    $tok = bin2hex(random_bytes(8));
    $tmp = sys_get_temp_dir() . '/_oc.php';
    file_put_contents($tmp, "<?php if ((\$_GET['t'] ?? '') !== '$tok') { http_response_code(404); exit; }\n"
        . "echo function_exists('opcache_reset') ? (opcache_reset() ? 'OK' : 'FAIL') : 'YOK';\n");
    if (ftp_put($c, "$webDir/_oc.php", $tmp, FTP_BINARY)) {
        $r = @file_get_contents("$appUrl/_oc.php?t=$tok");
        echo '  opcache_reset: ' . ($r ?: 'yanıt yok') . "\n";
        ftp_delete($c, "$webDir/_oc.php");
    } else {
        echo "  atlandı (yüklenemedi)\n";
    }
    @unlink($tmp);
}

echo "Bitti: $n dosya yüklendi.\n";
echo "NOT: $appDir/.env sunucuda kalır, bu script üzerine yazmaz.\n";

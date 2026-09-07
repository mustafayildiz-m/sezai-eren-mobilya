<?php
declare(strict_types=1);

// Web kökü uygulamanın içindeyse (public/) veya kardeşiyse (paylaşımlı hosting: public_html + app/)
$__b = dirname(__DIR__) . '/src/bootstrap.php';
require is_file($__b) ? $__b : dirname(__DIR__) . '/app/src/bootstrap.php';

use App\Controllers\Admin\AuthController;
use App\Controllers\Admin\CategoryAdminController;
use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\ProjectAdminController;
use App\Controllers\Admin\QuoteAdminController;
use App\Controllers\Admin\SettingAdminController;
use App\Controllers\HomeController;
use App\Controllers\LandingController;
use App\Controllers\PageController;
use App\Controllers\ProjectController;
use App\Controllers\QuoteController;
use App\Controllers\SitemapController;
use App\Database;
use App\NotFound;
use App\Router;
use App\View;

if (env('APP_DEBUG') === '1') { ini_set('display_errors', '1'); error_reporting(E_ALL); }

session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax', 'path' => '/']);
session_start();

Database::migrate();

$r = new Router();

// Public
$r->get('/', [HomeController::class, 'index']);
$r->get('/projeler', [ProjectController::class, 'index']);
$r->get('/projeler/{slug}', [ProjectController::class, 'show']);
$r->get('/hakkimizda', [PageController::class, 'about']);
$r->get('/iletisim', [PageController::class, 'contact']);
$r->get('/teklif-al', [QuoteController::class, 'form']);
$r->post('/teklif-al', [QuoteController::class, 'submit']);
$r->get('/teklif-al/tesekkurler', [QuoteController::class, 'done']);
// Ankara yerel arama sayfaları (hizmet + ilçe). Proje detayından önce
// tanımlanmaları gerekmez; desenler çakışmıyor.
$r->get('/ankara-{slug}', [LandingController::class, 'service']);
$r->get('/ankara/{slug}-mobilya', [LandingController::class, 'district']);
$r->get('/sitemap.xml', [SitemapController::class, 'sitemap']);
$r->get('/robots.txt', [SitemapController::class, 'robots']);

// Admin
$r->get('/yonetim/giris', [AuthController::class, 'form']);
$r->post('/yonetim/giris', [AuthController::class, 'login']);
$r->post('/yonetim/cikis', [AuthController::class, 'logout']);
$r->get('/yonetim', [DashboardController::class, 'index']);
$r->get('/yonetim/projeler', [ProjectAdminController::class, 'index']);
$r->get('/yonetim/projeler/yeni', [ProjectAdminController::class, 'create']);
$r->post('/yonetim/projeler/yeni', [ProjectAdminController::class, 'store']);
$r->get('/yonetim/projeler/{id}', [ProjectAdminController::class, 'edit']);
$r->post('/yonetim/projeler/{id}', [ProjectAdminController::class, 'update']);
$r->post('/yonetim/projeler/{id}/sil', [ProjectAdminController::class, 'destroy']);
$r->post('/yonetim/projeler/{id}/resim', [ProjectAdminController::class, 'upload']);
$r->post('/yonetim/resim/{id}/sil', [ProjectAdminController::class, 'deleteImage']);
$r->post('/yonetim/resim/{id}/kapak', [ProjectAdminController::class, 'setCover']);
$r->post('/yonetim/resim/sirala', [ProjectAdminController::class, 'reorder']);
$r->get('/yonetim/kategoriler', [CategoryAdminController::class, 'index']);
$r->post('/yonetim/kategoriler', [CategoryAdminController::class, 'store']);
$r->post('/yonetim/kategoriler/{id}', [CategoryAdminController::class, 'update']);
$r->post('/yonetim/kategoriler/{id}/sil', [CategoryAdminController::class, 'destroy']);
$r->get('/yonetim/teklifler', [QuoteAdminController::class, 'index']);
$r->post('/yonetim/teklifler/{id}/okundu', [QuoteAdminController::class, 'markRead']);
$r->post('/yonetim/teklifler/{id}/sil', [QuoteAdminController::class, 'destroy']);
$r->get('/yonetim/ayarlar', [SettingAdminController::class, 'index']);
$r->post('/yonetim/ayarlar', [SettingAdminController::class, 'save']);

try {
    $out = $r->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI'] ?? '/');
    if (is_string($out)) echo $out;
} catch (NotFound) {
    http_response_code(404);
    echo View::render('404', ['title' => 'Sayfa Bulunamadı', 'robots' => 'noindex, follow']);
}

<?php
declare(strict_types=1);
namespace App\Controllers\Admin;

use App\Auth;
use App\Csrf;
use App\Image;
use App\Models\Category;
use App\Models\ImageModel;
use App\Models\Project;
use App\NotFound;
use App\View;

final class ProjectAdminController
{
    private static function input(): array
    {
        return [
            'title' => trim((string) ($_POST['title'] ?? '')),
            'category_id' => (int) ($_POST['category_id'] ?? 0),
            'location' => trim((string) ($_POST['location'] ?? '')),
            'description' => trim((string) ($_POST['description'] ?? '')),
            'is_featured' => !empty($_POST['is_featured']) ? 1 : 0,
            'sort_order' => (int) ($_POST['sort_order'] ?? 0),
        ];
    }

    private static function json(array $data, int $code = 200): string
    {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public static function index(array $p = []): string
    {
        Auth::require();
        return View::render('admin/projects', ['title' => 'Projeler', 'projects' => Project::all()], 'admin/layout');
    }

    public static function create(array $p = []): string
    {
        Auth::require();
        return View::render('admin/project_form', ['title' => 'Yeni Proje', 'project' => null, 'images' => [], 'categories' => Category::all()], 'admin/layout');
    }

    public static function store(array $p = []): string
    {
        Auth::require(); Csrf::check();
        $d = self::input();
        if ($d['title'] === '') { flash('err', 'Başlık zorunludur.'); redirect('/yonetim/projeler/yeni'); }
        $id = Project::create($d);
        flash('ok', 'Proje oluşturuldu. Şimdi fotoğraf ekleyebilirsiniz.');
        redirect("/yonetim/projeler/$id");
    }

    public static function edit(array $p): string
    {
        Auth::require();
        $project = Project::find((int) $p['id']);
        if (!$project) throw new NotFound();
        return View::render('admin/project_form', ['title' => $project['title'], 'project' => $project, 'images' => ImageModel::forProject((int) $p['id']), 'categories' => Category::all()], 'admin/layout');
    }

    public static function update(array $p): string
    {
        Auth::require(); Csrf::check();
        if (!Project::find((int) $p['id'])) throw new NotFound();
        $d = self::input();
        if ($d['title'] === '') { flash('err', 'Başlık zorunludur.'); redirect("/yonetim/projeler/{$p['id']}"); }
        Project::update((int) $p['id'], $d);
        flash('ok', 'Proje kaydedildi.');
        redirect("/yonetim/projeler/{$p['id']}");
    }

    public static function destroy(array $p): string
    {
        Auth::require(); Csrf::check();
        Project::delete((int) $p['id']);
        flash('ok', 'Proje silindi.');
        redirect('/yonetim/projeler');
    }

    public static function upload(array $p): string
    {
        Auth::require();
        if (!Csrf::verify($_POST['_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null)) return self::json(['error' => 'Oturum doğrulanamadı'], 403);
        $project = Project::find((int) $p['id']);
        if (!$project) return self::json(['error' => 'Proje bulunamadı'], 404);
        $file = $_FILES['photo'] ?? null;
        if (!$file) return self::json(['error' => 'Dosya yok'], 400);
        if ($err = Image::validate($file)) return self::json(['error' => $err], 422);
        try {
            $r = Image::process($file['tmp_name'], BASE_PATH . '/public/uploads');
        } catch (\Throwable $e) {
            return self::json(['error' => 'Görsel işlenemedi'], 422);
        }
        $imgId = ImageModel::add((int) $p['id'], $r['filename'], $r['thumb'], $project['title']);
        if (!$project['cover_image_id']) Project::setCover((int) $p['id'], $imgId);
        return self::json(['id' => $imgId, 'thumb' => '/uploads/' . $r['thumb']]);
    }

    public static function deleteImage(array $p): string
    {
        Auth::require(); Csrf::check();
        $img = ImageModel::find((int) $p['id']);
        if (!$img) throw new NotFound();
        ImageModel::delete((int) $p['id']);
        redirect("/yonetim/projeler/{$img['project_id']}");
    }

    public static function setCover(array $p): string
    {
        Auth::require(); Csrf::check();
        $img = ImageModel::find((int) $p['id']);
        if (!$img) throw new NotFound();
        Project::setCover((int) $img['project_id'], (int) $img['id']);
        flash('ok', 'Kapak fotoğrafı güncellendi.');
        redirect("/yonetim/projeler/{$img['project_id']}");
    }

    public static function reorder(array $p = []): string
    {
        Auth::require();
        if (!Csrf::verify($_SERVER['HTTP_X_CSRF_TOKEN'] ?? null)) return self::json(['error' => 'csrf'], 403);
        $body = json_decode(file_get_contents('php://input') ?: '[]', true);
        $ids = array_map('intval', $body['ids'] ?? []);
        if ($ids) ImageModel::reorder($ids);
        return self::json(['ok' => true]);
    }
}

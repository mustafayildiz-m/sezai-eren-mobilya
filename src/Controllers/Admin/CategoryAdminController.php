<?php
declare(strict_types=1);
namespace App\Controllers\Admin;

use App\Auth;
use App\Csrf;
use App\Models\Category;
use App\View;

final class CategoryAdminController
{
    public static function index(array $p = []): string
    {
        Auth::require();
        return View::render('admin/categories', ['title' => 'Kategoriler', 'categories' => Category::all()], 'admin/layout');
    }

    public static function store(array $p = []): string
    {
        Auth::require(); Csrf::check();
        $name = trim((string) ($_POST['name'] ?? ''));
        if ($name === '') { flash('err', 'Kategori adı zorunludur.'); redirect('/yonetim/kategoriler'); }
        Category::create($name, trim((string) ($_POST['description'] ?? '')), (int) ($_POST['sort_order'] ?? 0));
        flash('ok', 'Kategori eklendi.');
        redirect('/yonetim/kategoriler');
    }

    public static function update(array $p): string
    {
        Auth::require(); Csrf::check();
        $name = trim((string) ($_POST['name'] ?? ''));
        if ($name === '') { flash('err', 'Kategori adı zorunludur.'); redirect('/yonetim/kategoriler'); }
        Category::update((int) $p['id'], $name, trim((string) ($_POST['description'] ?? '')), (int) ($_POST['sort_order'] ?? 0));
        flash('ok', 'Kategori güncellendi.');
        redirect('/yonetim/kategoriler');
    }

    public static function destroy(array $p): string
    {
        Auth::require(); Csrf::check();
        Category::delete((int) $p['id']);
        flash('ok', 'Kategori silindi.');
        redirect('/yonetim/kategoriler');
    }
}

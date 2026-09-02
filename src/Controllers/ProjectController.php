<?php
declare(strict_types=1);
namespace App\Controllers;

use App\Models\Category;
use App\Models\ImageModel;
use App\Models\Project;
use App\Models\Setting;
use App\NotFound;
use App\Seo;
use App\View;

final class ProjectController
{
    public static function index(array $p = []): string
    {
        $active = null;
        if (!empty($_GET['kategori'])) {
            $active = Category::bySlug((string) $_GET['kategori']);
            if (!$active) throw new NotFound();
        }
        $title = $active ? $active['name'] . ' Projeleri Ankara' : 'Projelerimiz';
        return View::render('projects', [
            'title' => $title,
            'description' => $active
                ? 'Ankara ' . mb_strtolower($active['name']) . ' projelerimiz. Ölçüye özel tasarım, birinci sınıf malzeme. Sezai Eren Mobilya portföyü.'
                : 'Sezai Eren Mobilya portföyü: Ankara\'da tamamlanan mutfak dolabı, vestiyer, gardırop ve özel tasarım mobilya projeleri.',
            'canonical' => url('/projeler' . ($active ? '?kategori=' . $active['slug'] : '')),
            'categories' => Category::all(),
            'active' => $active,
            'projects' => Project::all($active ? (int) $active['id'] : null),
            'jsonLd' => [Seo::localBusiness(Setting::all()), Seo::breadcrumb([['Ana Sayfa', '/'], ['Projeler', $active ? '/projeler' : null]] + ($active ? [2 => [$active['name'], null]] : []))],
        ]);
    }

    public static function show(array $p): string
    {
        $project = Project::bySlug($p['slug']);
        if (!$project) throw new NotFound();
        $images = ImageModel::forProject((int) $project['id']);
        $urls = array_map(fn($i) => url('/uploads/' . $i['filename']), $images);
        $desc = trim((string) $project['description']);
        $desc = $desc ? mb_substr(strip_tags($desc), 0, 150) : $project['title'] . ' – ' . ($project['category_name'] ?? 'özel tasarım') . ($project['location'] ? ', ' . $project['location'] : '') . ', Ankara. Sezai Eren Mobilya ölçüye özel üretim.';
        return View::render('project', [
            'title' => $project['title'] . ($project['location'] ? ' – ' . $project['location'] : '') . ', Ankara',
            'description' => $desc,
            'ogImage' => $urls[0] ?? url('/assets/seed/hero.webp'),
            'project' => $project,
            'images' => $images,
            'related' => $project['category_id'] ? Project::related((int) $project['category_id'], (int) $project['id']) : [],
            'jsonLd' => [
                Seo::product($project, $urls),
                Seo::breadcrumb(array_values(array_filter([['Ana Sayfa', '/'], ['Projeler', '/projeler'], $project['category_name'] ? [$project['category_name'], '/projeler?kategori=' . $project['category_slug']] : null, [$project['title'], null]]))),
            ],
        ]);
    }
}

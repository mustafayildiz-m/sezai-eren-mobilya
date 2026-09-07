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
        $projects = Project::all($active ? (int) $active['id'] : null);
        // Ankara landing'i olmayan kategoriler (ör. Turizm & Ticari) Ankara
        // dışında yapılan işler — başlığa "Ankara" eklemek yanlış olur.
        $landing = $active ? \App\Ankara::service((string) $active['slug']) : null;
        $title = $active
            ? ($landing ? 'Ankara ' . $active['name'] . ' Projeleri' : $active['name'])
            : 'Projelerimiz';
        $jsonLd = [
            Seo::localBusiness(Setting::all()),
            Seo::breadcrumb([['Ana Sayfa', '/'], ['Projeler', $active ? '/projeler' : null]] + ($active ? [2 => [$active['name'], null]] : [])),
        ];
        if ($projects) $jsonLd[] = Seo::itemList($projects, $title);

        return View::render('projects', [
            'title' => $title,
            'description' => match (true) {
                $active && $landing !== null => 'Ankara ' . mb_strtolower($active['name']) . ' projelerimiz. Ölçüye özel tasarım, birinci sınıf malzeme. Sezai Eren Mobilya portföyü.',
                $active !== null => $active['name'] . ' — ' . ($active['description'] ?: 'Sezai Eren Mobilya portföyünden seçkiler.'),
                default => 'Sezai Eren Mobilya portföyü: Ankara\'da tamamlanan mutfak dolabı, vestiyer, gardırop ve özel tasarım mobilya projeleri.',
            },
            'canonical' => url('/projeler' . ($active ? '?kategori=' . $active['slug'] : '')),
            'categories' => Category::all(),
            'active' => $active,
            'projects' => $projects,
            // Filtrelenen kategorinin asıl hedef sayfası: /ankara-{slug}
            'landing' => $landing,
            'jsonLd' => $jsonLd,
        ]);
    }

    public static function show(array $p): string
    {
        $project = Project::bySlug($p['slug']);
        if (!$project) throw new NotFound();
        $images = ImageModel::forProject((int) $project['id']);
        $urls = array_map(fn($i) => url('/uploads/' . $i['filename']), $images);
        $desc = trim((string) $project['description']);
        // "Ankara" ancak proje Ankara'da yapılan bir kategoriye aitse eklenir.
        $inAnkara = $project['category_slug'] !== null && \App\Ankara::service((string) $project['category_slug']) !== null;
        $where = $project['location'] ? ', ' . $project['location'] : ($inAnkara ? ', Ankara' : '');
        $desc = $desc ? mb_substr(strip_tags($desc), 0, 150) : $project['title'] . ' – ' . ($project['category_name'] ?? 'özel tasarım') . $where . '. Sezai Eren Mobilya ölçüye özel üretim.';
        return View::render('project', [
            'title' => $project['title'] . ($project['location'] ? ' – ' . $project['location'] . ', Ankara' : ($inAnkara ? ' – Ankara' : '')),
            'description' => $desc,
            'ogImage' => $urls[0] ?? url('/assets/hero.webp'),
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

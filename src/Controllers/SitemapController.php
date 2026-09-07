<?php
declare(strict_types=1);
namespace App\Controllers;

use App\Ankara;
use App\Models\Category;
use App\Models\ImageModel;
use App\Models\Project;

final class SitemapController
{
    public static function sitemap(array $p = []): string
    {
        header('Content-Type: application/xml; charset=utf-8');
        $today = date('Y-m-d');

        // [yol, öncelik, sıklık, lastmod]
        $urls = [
            ['/', '1.0', 'weekly', $today],
            ['/projeler', '0.9', 'weekly', $today],
            ['/teklif-al', '0.8', 'monthly', $today],
            ['/iletisim', '0.7', 'monthly', $today],
            ['/hakkimizda', '0.6', 'monthly', $today],
        ];

        // Ankara hizmet sayfaları — kategori filtrelerinden daha güçlü hedef
        // sayfalar oldukları için önceliği yüksek tutuyoruz.
        foreach (array_keys(Ankara::services()) as $slug) {
            $urls[] = ['/ankara-' . $slug, '0.9', 'monthly', $today];
        }
        foreach (array_keys(Ankara::districts()) as $slug) {
            $urls[] = ['/ankara/' . $slug . '-mobilya', '0.8', 'monthly', $today];
        }

        // Kategori filtreleri: landing sayfaları asıl hedef olduğu için düşük öncelik.
        foreach (Category::all() as $c) {
            $urls[] = ['/projeler?kategori=' . $c['slug'], '0.5', 'weekly', $today];
        }

        $out = '<?xml version="1.0" encoding="UTF-8"?>' . "\n"
            . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . "\n";

        foreach ($urls as $u) {
            $out .= '  <url><loc>' . e(url($u[0])) . '</loc><lastmod>' . $u[3] . '</lastmod><changefreq>' . $u[2] . '</changefreq><priority>' . $u[1] . '</priority></url>' . "\n";
        }

        // Proje sayfaları görselleriyle birlikte: Google Görseller'den gelen
        // trafik mobilyada ciddi bir kanal.
        foreach (Project::all() as $pr) {
            $out .= '  <url><loc>' . e(url('/projeler/' . $pr['slug'])) . '</loc>'
                . '<lastmod>' . substr((string) $pr['created_at'], 0, 10) . '</lastmod>'
                . '<changefreq>monthly</changefreq><priority>0.7</priority>';
            foreach (ImageModel::forProject((int) $pr['id']) as $img) {
                $out .= '<image:image><image:loc>' . e(url('/uploads/' . $img['filename'])) . '</image:loc>'
                    . '<image:title>' . e($img['alt'] ?: $pr['title']) . '</image:title></image:image>';
            }
            $out .= '</url>' . "\n";
        }

        return $out . '</urlset>';
    }

    public static function robots(array $p = []): string
    {
        header('Content-Type: text/plain; charset=utf-8');
        return "User-agent: *\n"
            . "Allow: /\n"
            . "Disallow: /yonetim\n"
            . "Disallow: /teklif-al/tesekkurler\n"
            . "\n"
            . "Sitemap: " . url('/sitemap.xml') . "\n";
    }
}

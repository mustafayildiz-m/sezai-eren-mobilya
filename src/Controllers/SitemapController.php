<?php
declare(strict_types=1);
namespace App\Controllers;

use App\Models\Category;
use App\Models\Project;

final class SitemapController
{
    public static function sitemap(array $p = []): string
    {
        header('Content-Type: application/xml; charset=utf-8');
        $urls = [['/', '1.0', 'weekly'], ['/projeler', '0.9', 'weekly'], ['/hakkimizda', '0.6', 'monthly'], ['/iletisim', '0.7', 'monthly'], ['/teklif-al', '0.8', 'monthly']];
        foreach (Category::all() as $c) $urls[] = ['/projeler?kategori=' . $c['slug'], '0.8', 'weekly'];
        foreach (Project::all() as $pr) $urls[] = ['/projeler/' . $pr['slug'], '0.7', 'monthly', substr((string) $pr['created_at'], 0, 10)];
        $out = '<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach ($urls as $u) {
            $out .= '  <url><loc>' . e(url($u[0])) . '</loc><priority>' . $u[1] . '</priority><changefreq>' . $u[2] . '</changefreq>' . (isset($u[3]) ? '<lastmod>' . $u[3] . '</lastmod>' : '') . '</url>' . "\n";
        }
        return $out . '</urlset>';
    }

    public static function robots(array $p = []): string
    {
        header('Content-Type: text/plain; charset=utf-8');
        return "User-agent: *\nAllow: /\nDisallow: /yonetim\nSitemap: " . url('/sitemap.xml') . "\n";
    }
}

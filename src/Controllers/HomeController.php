<?php
declare(strict_types=1);
namespace App\Controllers;

use App\Models\Category;
use App\Models\Project;
use App\Models\Setting;
use App\Seo;
use App\View;

final class HomeController
{
    public static function index(array $p = []): string
    {
        return View::render('home', [
            'categories' => Category::all(),
            'featured' => Project::featured(6),
            'description' => 'Ankara\'da ölçüye özel mutfak dolabı, vestiyer, gardırop ve özel tasarım mobilya. 20 yıllık ustalık, ücretsiz keşif, 3D tasarım. Sezai Eren Mobilya.',
            'jsonLd' => [Seo::localBusiness(Setting::all()), [
                '@type' => 'WebSite', 'name' => Setting::get('site_name'), 'url' => url('/'),
            ]],
        ]);
    }
}

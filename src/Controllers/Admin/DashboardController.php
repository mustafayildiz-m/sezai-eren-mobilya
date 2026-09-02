<?php
declare(strict_types=1);
namespace App\Controllers\Admin;

use App\Auth;
use App\Database;
use App\Models\Category;
use App\Models\Project;
use App\Models\Quote;
use App\View;

final class DashboardController
{
    public static function index(array $p = []): string
    {
        Auth::require();
        return View::render('admin/dashboard', [
            'title' => 'Özet',
            'stats' => [
                'projects' => Project::count(),
                'images' => (int) Database::pdo()->query('SELECT COUNT(*) FROM images')->fetchColumn(),
                'categories' => count(Category::all()),
                'unread' => Quote::unreadCount(),
            ],
            'quotes' => Quote::all(),
        ], 'admin/layout');
    }
}

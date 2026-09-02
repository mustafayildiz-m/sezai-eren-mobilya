<?php
declare(strict_types=1);
namespace App\Controllers\Admin;

use App\Auth;
use App\Csrf;
use App\Models\Quote;
use App\View;

final class QuoteAdminController
{
    public static function index(array $p = []): string
    {
        Auth::require();
        return View::render('admin/quotes', ['title' => 'Teklifler', 'quotes' => Quote::all()], 'admin/layout');
    }

    public static function markRead(array $p): string
    {
        Auth::require(); Csrf::check();
        Quote::markRead((int) $p['id']);
        redirect('/yonetim/teklifler');
    }

    public static function destroy(array $p): string
    {
        Auth::require(); Csrf::check();
        Quote::delete((int) $p['id']);
        flash('ok', 'Talep silindi.');
        redirect('/yonetim/teklifler');
    }
}

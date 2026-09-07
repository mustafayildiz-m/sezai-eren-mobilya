<?php
declare(strict_types=1);
namespace App;

use PDO;

final class Database
{
    private static ?PDO $pdo = null;

    public static function pdo(): PDO
    {
        if (self::$pdo === null) {
            $path = env('DB_PATH', BASE_PATH . '/storage/database.sqlite');
            if (!is_dir(dirname($path))) mkdir(dirname($path), 0775, true);
            self::$pdo = new PDO('sqlite:' . $path, null, null, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
            self::$pdo->exec('PRAGMA foreign_keys = ON');
            self::$pdo->exec('PRAGMA journal_mode = WAL');
        }
        return self::$pdo;
    }

    public static function reset(): void { self::$pdo = null; }

    public static function migrate(): void
    {
        $db = self::pdo();
        $db->exec(<<<SQL
        CREATE TABLE IF NOT EXISTS categories (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL, slug TEXT NOT NULL UNIQUE,
            description TEXT DEFAULT '', sort_order INTEGER DEFAULT 0
        );
        CREATE TABLE IF NOT EXISTS projects (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            category_id INTEGER REFERENCES categories(id) ON DELETE SET NULL,
            title TEXT NOT NULL, slug TEXT NOT NULL UNIQUE,
            description TEXT DEFAULT '', location TEXT DEFAULT '',
            cover_image_id INTEGER, is_featured INTEGER DEFAULT 0,
            sort_order INTEGER DEFAULT 0,
            created_at TEXT DEFAULT CURRENT_TIMESTAMP
        );
        CREATE TABLE IF NOT EXISTS images (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            project_id INTEGER NOT NULL REFERENCES projects(id) ON DELETE CASCADE,
            filename TEXT NOT NULL, thumb TEXT NOT NULL, alt TEXT DEFAULT '',
            sort_order INTEGER DEFAULT 0
        );
        CREATE TABLE IF NOT EXISTS quotes (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL, phone TEXT NOT NULL, email TEXT DEFAULT '',
            job_type TEXT DEFAULT '', message TEXT DEFAULT '', photo TEXT DEFAULT '',
            is_read INTEGER DEFAULT 0, created_at TEXT DEFAULT CURRENT_TIMESTAMP
        );
        CREATE TABLE IF NOT EXISTS settings (key TEXT PRIMARY KEY, value TEXT NOT NULL DEFAULT '');
        CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT NOT NULL UNIQUE, password_hash TEXT NOT NULL
        );
        SQL);

        $defaults = [
            'site_name' => 'Sezai Eren Mobilya',
            'tagline' => 'Ankara\'da ölçüye özel mutfak dolabı, vestiyer ve gardırop',
            'phone' => '+90 5XX XXX XX XX',
            'whatsapp' => '905XXXXXXXXX',
            'email' => 'info@sezaierenmobilya.com',
            'address' => 'Siteler, Altındağ / Ankara',
            'instagram' => 'https://instagram.com/sezaierenmobilya',
            'working_hours' => 'Pzt–Cmt 09:00–19:00',
            'map_embed' => 'https://www.google.com/maps?q=Siteler,+Alt%C4%B1nda%C4%9F,+Ankara&output=embed',
            'about' => 'Yirmi yılı aşkın tecrübemizle Ankara\'da mutfak dolabı, vestiyer, gardırop ve özel tasarım mobilya üretiyoruz. Her projeyi ölçüye, zevke ve bütçeye göre sıfırdan tasarlıyor; birinci sınıf malzeme ve titiz işçilikle teslim ediyoruz.',
            'years' => '20', 'projects_done' => '850', 'happy_clients' => '600',
            // SEO alanları — boş bırakılırsa ilgili etiket/schema alanı hiç basılmaz.
            'gsc_verification' => '',   // Google Search Console doğrulama kodu
            'map_url' => '',            // Google Business Profile / Maps linki (schema hasMap)
            'latitude' => '', 'longitude' => '',
        ];
        $ins = $db->prepare('INSERT OR IGNORE INTO settings (key, value) VALUES (?, ?)');
        foreach ($defaults as $k => $v) $ins->execute([$k, $v]);

        Models\User::ensureAdmin();
    }
}

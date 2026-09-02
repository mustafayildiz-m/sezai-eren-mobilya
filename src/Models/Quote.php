<?php
declare(strict_types=1);
namespace App\Models;

use App\Database;

final class Quote
{
    public static function create(array $d): int
    {
        $st = Database::pdo()->prepare('INSERT INTO quotes (name, phone, email, job_type, message, photo) VALUES (?,?,?,?,?,?)');
        $st->execute([$d['name'], $d['phone'], $d['email'] ?? '', $d['job_type'] ?? '', $d['message'] ?? '', $d['photo'] ?? '']);
        return (int) Database::pdo()->lastInsertId();
    }

    public static function all(): array
    {
        return Database::pdo()->query('SELECT * FROM quotes ORDER BY is_read, created_at DESC')->fetchAll();
    }

    public static function unreadCount(): int
    {
        return (int) Database::pdo()->query('SELECT COUNT(*) FROM quotes WHERE is_read=0')->fetchColumn();
    }

    public static function markRead(int $id): void
    {
        Database::pdo()->prepare('UPDATE quotes SET is_read=1 WHERE id=?')->execute([$id]);
    }

    public static function delete(int $id): void
    {
        $s = Database::pdo()->prepare('SELECT photo FROM quotes WHERE id=?'); $s->execute([$id]);
        $photo = $s->fetchColumn();
        if ($photo && is_file(BASE_PATH . '/public/uploads/' . $photo)) unlink(BASE_PATH . '/public/uploads/' . $photo);
        Database::pdo()->prepare('DELETE FROM quotes WHERE id=?')->execute([$id]);
    }
}

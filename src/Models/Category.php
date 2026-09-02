<?php
declare(strict_types=1);
namespace App\Models;

use App\Database;
use App\Slug;

final class Category
{
    public static function all(): array
    {
        return Database::pdo()->query('SELECT c.*, (SELECT COUNT(*) FROM projects p WHERE p.category_id=c.id) AS project_count FROM categories c ORDER BY sort_order, name')->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $s = Database::pdo()->prepare('SELECT * FROM categories WHERE id=?'); $s->execute([$id]);
        return $s->fetch() ?: null;
    }

    public static function bySlug(string $slug): ?array
    {
        $s = Database::pdo()->prepare('SELECT * FROM categories WHERE slug=?'); $s->execute([$slug]);
        return $s->fetch() ?: null;
    }

    public static function create(string $name, string $description = '', int $sort = 0): int
    {
        $slug = Slug::unique($name, fn($s) => self::bySlug($s) !== null);
        $st = Database::pdo()->prepare('INSERT INTO categories (name, slug, description, sort_order) VALUES (?,?,?,?)');
        $st->execute([$name, $slug, $description, $sort]);
        return (int) Database::pdo()->lastInsertId();
    }

    public static function update(int $id, string $name, string $description, int $sort): void
    {
        $st = Database::pdo()->prepare('UPDATE categories SET name=?, description=?, sort_order=? WHERE id=?');
        $st->execute([$name, $description, $sort, $id]);
    }

    public static function delete(int $id): void
    {
        Database::pdo()->prepare('DELETE FROM categories WHERE id=?')->execute([$id]);
    }
}

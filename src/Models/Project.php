<?php
declare(strict_types=1);
namespace App\Models;

use App\Database;
use App\Slug;

final class Project
{
    private const SELECT = 'SELECT p.*, c.name AS category_name, c.slug AS category_slug,
        COALESCE(ci.filename, (SELECT filename FROM images WHERE project_id=p.id ORDER BY sort_order, id LIMIT 1)) AS cover,
        COALESCE(ci.thumb, (SELECT thumb FROM images WHERE project_id=p.id ORDER BY sort_order, id LIMIT 1)) AS cover_thumb
        FROM projects p LEFT JOIN categories c ON c.id=p.category_id LEFT JOIN images ci ON ci.id=p.cover_image_id';

    public static function all(?int $categoryId = null): array
    {
        $sql = self::SELECT . ($categoryId ? ' WHERE p.category_id=?' : '') . ' ORDER BY p.sort_order, p.created_at DESC, p.id DESC';
        $s = Database::pdo()->prepare($sql); $s->execute($categoryId ? [$categoryId] : []);
        return $s->fetchAll();
    }

    public static function featured(int $limit = 6): array
    {
        $s = Database::pdo()->prepare(self::SELECT . ' WHERE p.is_featured=1 ORDER BY p.sort_order, p.id DESC LIMIT ?');
        $s->bindValue(1, $limit, \PDO::PARAM_INT); $s->execute();
        return $s->fetchAll();
    }

    public static function related(int $categoryId, int $excludeId, int $limit = 3): array
    {
        $s = Database::pdo()->prepare(self::SELECT . ' WHERE p.category_id=? AND p.id<>? ORDER BY RANDOM() LIMIT ?');
        $s->bindValue(1, $categoryId, \PDO::PARAM_INT); $s->bindValue(2, $excludeId, \PDO::PARAM_INT); $s->bindValue(3, $limit, \PDO::PARAM_INT);
        $s->execute();
        return $s->fetchAll();
    }

    public static function bySlug(string $slug): ?array
    {
        $s = Database::pdo()->prepare(self::SELECT . ' WHERE p.slug=?'); $s->execute([$slug]);
        return $s->fetch() ?: null;
    }

    public static function find(int $id): ?array
    {
        $s = Database::pdo()->prepare(self::SELECT . ' WHERE p.id=?'); $s->execute([$id]);
        return $s->fetch() ?: null;
    }

    public static function count(): int
    {
        return (int) Database::pdo()->query('SELECT COUNT(*) FROM projects')->fetchColumn();
    }

    public static function create(array $d): int
    {
        $slug = Slug::unique($d['title'], fn($s) => self::bySlug($s) !== null);
        $st = Database::pdo()->prepare('INSERT INTO projects (category_id, title, slug, description, location, is_featured, sort_order) VALUES (?,?,?,?,?,?,?)');
        $st->execute([$d['category_id'] ?: null, $d['title'], $slug, $d['description'] ?? '', $d['location'] ?? '', (int)($d['is_featured'] ?? 0), (int)($d['sort_order'] ?? 0)]);
        return (int) Database::pdo()->lastInsertId();
    }

    public static function update(int $id, array $d): void
    {
        $st = Database::pdo()->prepare('UPDATE projects SET category_id=?, title=?, description=?, location=?, is_featured=?, sort_order=? WHERE id=?');
        $st->execute([$d['category_id'] ?: null, $d['title'], $d['description'] ?? '', $d['location'] ?? '', (int)($d['is_featured'] ?? 0), (int)($d['sort_order'] ?? 0), $id]);
    }

    public static function setCover(int $id, ?int $imageId): void
    {
        Database::pdo()->prepare('UPDATE projects SET cover_image_id=? WHERE id=?')->execute([$imageId, $id]);
    }

    public static function delete(int $id): void
    {
        foreach (ImageModel::forProject($id) as $img) ImageModel::delete((int) $img['id']);
        Database::pdo()->prepare('DELETE FROM projects WHERE id=?')->execute([$id]);
    }
}

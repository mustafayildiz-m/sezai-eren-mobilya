<?php
declare(strict_types=1);
namespace App\Models;

use App\Database;

final class ImageModel
{
    public static function forProject(int $projectId): array
    {
        $s = Database::pdo()->prepare('SELECT * FROM images WHERE project_id=? ORDER BY sort_order, id'); $s->execute([$projectId]);
        return $s->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $s = Database::pdo()->prepare('SELECT * FROM images WHERE id=?'); $s->execute([$id]);
        return $s->fetch() ?: null;
    }

    public static function add(int $projectId, string $filename, string $thumb, string $alt = ''): int
    {
        $db = Database::pdo();
        $max = (int) $db->query("SELECT COALESCE(MAX(sort_order),0) FROM images WHERE project_id=$projectId")->fetchColumn();
        $db->prepare('INSERT INTO images (project_id, filename, thumb, alt, sort_order) VALUES (?,?,?,?,?)')->execute([$projectId, $filename, $thumb, $alt, $max + 1]);
        return (int) $db->lastInsertId();
    }

    public static function reorder(array $ids): void
    {
        $st = Database::pdo()->prepare('UPDATE images SET sort_order=? WHERE id=?');
        foreach (array_values($ids) as $i => $id) $st->execute([$i + 1, (int) $id]);
    }

    public static function delete(int $id): void
    {
        $img = self::find($id);
        if (!$img) return;
        foreach ([$img['filename'], $img['thumb']] as $f) {
            $p = BASE_PATH . '/public/uploads/' . $f;
            if (is_file($p)) unlink($p);
        }
        Database::pdo()->prepare('UPDATE projects SET cover_image_id=NULL WHERE cover_image_id=?')->execute([$id]);
        Database::pdo()->prepare('DELETE FROM images WHERE id=?')->execute([$id]);
    }
}

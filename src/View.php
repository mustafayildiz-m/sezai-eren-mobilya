<?php
declare(strict_types=1);
namespace App;

final class View
{
    public static function render(string $template, array $vars = [], ?string $layout = 'layout'): string
    {
        $content = self::partial($template, $vars);
        if ($layout === null) return $content;
        return self::partial($layout, $vars + ['content' => $content]);
    }

    public static function partial(string $template, array $vars = []): string
    {
        $file = BASE_PATH . '/templates/' . $template . '.php';
        if (!is_file($file)) throw new \RuntimeException("Template not found: $template");
        extract($vars, EXTR_SKIP);
        ob_start();
        try { require $file; } finally { $out = ob_get_clean(); }
        return $out;
    }
}

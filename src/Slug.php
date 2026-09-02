<?php
declare(strict_types=1);
namespace App;

final class Slug
{
    private const MAP = ['ş'=>'s','Ş'=>'s','ı'=>'i','I'=>'i','İ'=>'i','ğ'=>'g','Ğ'=>'g','ü'=>'u','Ü'=>'u','ö'=>'o','Ö'=>'o','ç'=>'c','Ç'=>'c'];

    public static function make(string $text): string
    {
        $text = strtr($text, self::MAP);
        $text = mb_strtolower($text, 'UTF-8');
        $text = preg_replace('/[^a-z0-9]+/', '-', $text) ?? '';
        return trim($text, '-') ?: 'proje';
    }

    public static function unique(string $text, callable $exists): string
    {
        $base = self::make($text);
        $slug = $base;
        for ($i = 2; $exists($slug); $i++) $slug = "$base-$i";
        return $slug;
    }
}

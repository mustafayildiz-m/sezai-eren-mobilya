<?php
declare(strict_types=1);
namespace App;

final class Seo
{
    public static function jsonLd(array $data): string
    {
        $data = ['@context' => 'https://schema.org'] + $data;
        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP);
        return '<script type="application/ld+json">' . $json . '</script>';
    }

    public static function localBusiness(array $s): array
    {
        $sameAs = array_values(array_filter([$s['instagram'] ?? null]));
        return [
            '@type' => 'LocalBusiness',
            '@id' => url('/') . '#business',
            'name' => $s['site_name'] ?? 'Sezai Eren Mobilya',
            'description' => $s['tagline'] ?? '',
            'url' => url('/'),
            'telephone' => $s['phone'] ?? '',
            'email' => $s['email'] ?? '',
            'image' => url('/assets/seed/hero.webp'),
            'priceRange' => '₺₺',
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => $s['address'] ?? '',
                'addressLocality' => 'Ankara',
                'addressRegion' => 'Ankara',
                'addressCountry' => 'TR',
            ],
            'areaServed' => ['@type' => 'City', 'name' => 'Ankara'],
            'openingHours' => $s['working_hours'] ?? '',
            'sameAs' => $sameAs,
            'makesOffer' => array_map(fn($n) => ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => $n]],
                ['Mutfak Dolabı', 'Vestiyer', 'Gardırop', 'TV Ünitesi', 'Banyo Dolabı', 'Özel Tasarım Mobilya']),
        ];
    }

    /** @param array<int, array{0:string,1:?string}> $items [label, url|null] */
    public static function breadcrumb(array $items): array
    {
        $els = [];
        foreach (array_values($items) as $i => [$label, $path]) {
            $el = ['@type' => 'ListItem', 'position' => $i + 1, 'name' => $label];
            if ($path !== null) $el['item'] = url($path);
            $els[] = $el;
        }
        return ['@type' => 'BreadcrumbList', 'itemListElement' => $els];
    }

    public static function product(array $project, array $imageUrls): array
    {
        return [
            '@type' => 'Product',
            'name' => $project['title'],
            'description' => trim(strip_tags($project['description'] ?? '')) ?: $project['title'],
            'category' => $project['category_name'] ?? '',
            'brand' => ['@type' => 'Brand', 'name' => 'Sezai Eren Mobilya'],
            'url' => url('/projeler/' . $project['slug']),
            'image' => array_values($imageUrls),
            'offers' => ['@type' => 'Offer', 'availability' => 'https://schema.org/InStock', 'priceCurrency' => 'TRY', 'price' => '0', 'description' => 'Ölçüye özel üretim, ücretsiz keşif ve fiyat teklifi'],
        ];
    }
}

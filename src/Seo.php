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

    /**
     * İşletmenin ana kimliği. FurnitureStore, LocalBusiness'ın alt tipidir ve
     * Google'a "mobilyacı" sinyalini LocalBusiness'tan daha net verir.
     */
    public static function localBusiness(array $s): array
    {
        $sameAs = array_values(array_filter([$s['instagram'] ?? null, $s['facebook'] ?? null, $s['gbp_url'] ?? null]));

        $ld = [
            '@type' => ['FurnitureStore', 'HomeAndConstructionBusiness'],
            '@id' => url('/') . '#business',
            'name' => $s['site_name'] ?? 'Sezai Eren Mobilya',
            'description' => $s['tagline'] ?? '',
            'url' => url('/'),
            'telephone' => $s['phone'] ?? '',
            'email' => $s['email'] ?? '',
            'image' => url('/assets/hero.webp'),
            'logo' => url('/assets/hero.webp'),
            'priceRange' => '₺₺',
            'currenciesAccepted' => 'TRY',
            'paymentAccepted' => 'Nakit, Kredi Kartı, Havale/EFT',
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => $s['address'] ?? '',
                'addressLocality' => 'Ankara',
                'addressRegion' => 'Ankara',
                'addressCountry' => 'TR',
            ],
            // Hizmet bölgesi: Ankara geneli + fiilen çalıştığımız ilçeler.
            'areaServed' => array_merge(
                [['@type' => 'City', 'name' => 'Ankara']],
                array_map(fn($n) => ['@type' => 'AdministrativeArea', 'name' => $n . ', Ankara'], Ankara::areaServedNames())
            ),
            'openingHoursSpecification' => self::openingHours($s['working_hours'] ?? ''),
            'knowsLanguage' => 'tr-TR',
            'hasOfferCatalog' => [
                '@type' => 'OfferCatalog',
                'name' => 'Ankara ölçüye özel mobilya hizmetleri',
                'itemListElement' => array_map(fn($sv) => [
                    '@type' => 'Offer',
                    'url' => url($sv['path']),
                    'itemOffered' => ['@type' => 'Service', 'name' => $sv['name'], 'areaServed' => ['@type' => 'City', 'name' => 'Ankara']],
                ], array_map(fn($k) => Ankara::service($k), array_keys(Ankara::services()))),
            ],
        ];

        if ($sameAs) $ld['sameAs'] = $sameAs;
        if (!empty($s['map_url'])) $ld['hasMap'] = $s['map_url'];
        // Koordinat yalnızca gerçekten girildiyse yazılır; uydurma koordinat
        // Google Business Profile eşleşmesini bozar.
        if (!empty($s['latitude']) && !empty($s['longitude'])) {
            $ld['geo'] = ['@type' => 'GeoCoordinates', 'latitude' => $s['latitude'], 'longitude' => $s['longitude']];
        }

        return $ld;
    }

    /** "Pzt–Cmt 09:00–19:00" → schema.org OpeningHoursSpecification */
    public static function openingHours(string $text): array
    {
        $days = ['pzt' => 'Monday', 'sal' => 'Tuesday', 'car' => 'Wednesday', 'çar' => 'Wednesday',
                 'per' => 'Thursday', 'cum' => 'Friday', 'cmt' => 'Saturday', 'paz' => 'Sunday'];
        $order = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        $t = mb_strtolower(str_replace(['–', '—'], '-', $text), 'UTF-8');
        if (!preg_match('/(\d{1,2}[:.]\d{2})\s*-\s*(\d{1,2}[:.]\d{2})/', $t, $h)) return [];
        $open = str_replace('.', ':', $h[1]);
        $close = str_replace('.', ':', $h[2]);

        // Gün aralığı: "pzt-cmt" → Monday..Saturday
        if (preg_match('/([a-zçğıöşü]{3})\s*-\s*([a-zçğıöşü]{3})/u', $t, $d)
            && isset($days[$d[1]], $days[$d[2]])) {
            $from = array_search($days[$d[1]], $order, true);
            $to = array_search($days[$d[2]], $order, true);
            $list = $from <= $to ? array_slice($order, $from, $to - $from + 1) : array_merge(array_slice($order, $from), array_slice($order, 0, $to + 1));
        } else {
            $list = array_slice($order, 0, 6);
        }

        return [[
            '@type' => 'OpeningHoursSpecification',
            'dayOfWeek' => array_values($list),
            'opens' => $open,
            'closes' => $close,
        ]];
    }

    /** Ana sayfa için WebSite + site içi arama yok, sadece kimlik. */
    public static function website(array $s): array
    {
        return [
            '@type' => 'WebSite',
            '@id' => url('/') . '#website',
            'name' => $s['site_name'] ?? 'Sezai Eren Mobilya',
            'url' => url('/'),
            'inLanguage' => 'tr-TR',
            'publisher' => ['@id' => url('/') . '#business'],
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
            'offers' => [
                '@type' => 'Offer',
                'availability' => 'https://schema.org/InStock',
                'priceCurrency' => 'TRY',
                'price' => '0',
                'description' => 'Ölçüye özel üretim, ücretsiz keşif ve fiyat teklifi',
                'areaServed' => ['@type' => 'City', 'name' => 'Ankara'],
                'seller' => ['@id' => url('/') . '#business'],
            ],
        ];
    }

    /**
     * Hizmet sayfaları için Service. Ankara'yı areaServed olarak bağlar —
     * yerel aramada sayfanın hangi şehre ait olduğunu netleştiren sinyal.
     */
    public static function service(array $sv): array
    {
        return [
            '@type' => 'Service',
            '@id' => url($sv['path']) . '#service',
            'serviceType' => $sv['name'],
            'name' => $sv['h1'],
            'description' => $sv['description'],
            'url' => url($sv['path']),
            'provider' => ['@id' => url('/') . '#business'],
            'areaServed' => array_merge(
                [['@type' => 'City', 'name' => 'Ankara']],
                array_map(fn($n) => ['@type' => 'AdministrativeArea', 'name' => $n . ', Ankara'], Ankara::areaServedNames())
            ),
            'offers' => ['@type' => 'Offer', 'priceCurrency' => 'TRY', 'description' => 'Ücretsiz keşif, ölçü ve 3D tasarım'],
        ];
    }

    /** @param array<int, array{0:string,1:string}> $qa [soru, cevap] */
    public static function faq(array $qa): array
    {
        return [
            '@type' => 'FAQPage',
            'mainEntity' => array_map(fn($p) => [
                '@type' => 'Question',
                'name' => $p[0],
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $p[1]],
            ], array_values($qa)),
        ];
    }

    /** Liste sayfalarındaki projeleri sıralı koleksiyon olarak bildirir. */
    public static function itemList(array $projects, string $name): array
    {
        return [
            '@type' => 'ItemList',
            'name' => $name,
            'numberOfItems' => count($projects),
            'itemListElement' => array_map(fn($i, $p) => [
                '@type' => 'ListItem',
                'position' => $i + 1,
                'url' => url('/projeler/' . $p['slug']),
                'name' => $p['title'],
            ], array_keys(array_values($projects)), array_values($projects)),
        ];
    }
}

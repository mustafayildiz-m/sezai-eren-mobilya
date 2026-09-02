<?php
declare(strict_types=1);
use App\Seo;
use PHPUnit\Framework\TestCase;

final class SeoTest extends TestCase
{
    public function testLocalBusiness(): void
    {
        $ld = Seo::localBusiness(['site_name' => 'Sezai Eren Mobilya', 'phone' => '+90 555', 'address' => 'Siteler, Altındağ / Ankara', 'instagram' => 'https://instagram.com/x']);
        $this->assertSame('LocalBusiness', $ld['@type']);
        $this->assertSame('Ankara', $ld['address']['addressLocality']);
        $this->assertSame('TR', $ld['address']['addressCountry']);
        $this->assertContains('https://instagram.com/x', $ld['sameAs']);
    }

    public function testBreadcrumbPositions(): void
    {
        $ld = Seo::breadcrumb([['Ana Sayfa', '/'], ['Projeler', '/projeler'], ['Mutfak', null]]);
        $this->assertSame('BreadcrumbList', $ld['@type']);
        $this->assertSame([1, 2, 3], array_column($ld['itemListElement'], 'position'));
        $this->assertArrayNotHasKey('item', $ld['itemListElement'][2]);
    }

    public function testJsonLdScriptEscapesClosingTag(): void
    {
        $html = Seo::jsonLd(['@type' => 'Thing', 'name' => '</script><b>x']);
        $this->assertStringStartsWith('<script type="application/ld+json">', $html);
        $this->assertStringNotContainsString('</script><b>', $html);
    }

    public function testProduct(): void
    {
        $ld = Seo::product(['title' => 'Modern Mutfak', 'description' => 'd', 'slug' => 'modern-mutfak', 'category_name' => 'Mutfak Dolabı'], ['a.webp', 'b.webp']);
        $this->assertSame('Product', $ld['@type']);
        $this->assertCount(2, $ld['image']);
    }
}

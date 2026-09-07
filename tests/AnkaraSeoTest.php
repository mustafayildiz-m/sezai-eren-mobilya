<?php
declare(strict_types=1);
use App\Ankara;
use App\Router;
use App\Seo;
use PHPUnit\Framework\TestCase;

final class AnkaraSeoTest extends TestCase
{
    public function testEveryServiceKeyMatchesALandingPath(): void
    {
        foreach (Ankara::services() as $slug => $sv) {
            $s = Ankara::service($slug);
            $this->assertSame('/ankara-' . $slug, $s['path']);
            foreach (['name', 'h1', 'title', 'description', 'lead', 'sections', 'faq'] as $k) {
                $this->assertNotEmpty($s[$k], "$slug.$k boş");
            }
        }
    }

    public function testDistrictContentIsUniquePerDistrict(): void
    {
        // Aynı paragrafın ilçe adı değiştirilerek tekrarlanması doorway page'dir.
        $leads = array_column(Ankara::districts(), 'lead');
        $this->assertSame(count($leads), count(array_unique($leads)));

        $bodies = [];
        foreach (Ankara::districts() as $d) foreach ($d['body'] as $para) $bodies[] = $para;
        $this->assertSame(count($bodies), count(array_unique($bodies)));
    }

    public function testMetaLengthsStayWithinGoogleSnippet(): void
    {
        foreach (Ankara::services() as $slug => $sv) {
            $this->assertLessThanOrEqual(70, mb_strlen($sv['title']), "$slug title uzun");
            $this->assertLessThanOrEqual(165, mb_strlen($sv['description']), "$slug description uzun");
        }
        foreach (Ankara::districts() as $slug => $d) {
            $this->assertLessThanOrEqual(70, mb_strlen($d['title']), "$slug title uzun");
            $this->assertLessThanOrEqual(165, mb_strlen($d['description']), "$slug description uzun");
        }
    }

    public function testEveryServiceSlugIsTheSlugOfItsCategoryName(): void
    {
        // Landing sayfası kategori projelerini Category::bySlug($slug) ile
        // buluyor; slug, kategori adının slug'ı değilse sayfa boş kalır.
        foreach (Ankara::services() as $slug => $sv) {
            $this->assertSame(\App\Slug::make($sv['name']), $slug, "$slug kategori adıyla eşleşmiyor");
        }
    }

    public function testCoreServicesArePresent(): void
    {
        $slugs = array_keys(Ankara::services());
        foreach (['mutfak-dolabi', 'gardirop', 'vestiyer', 'tv-unitesi', 'banyo-dolabi', 'ozel-tasarim'] as $s) {
            $this->assertContains($s, $slugs);
        }
    }

    public function testLandingRoutesResolve(): void
    {
        $r = new Router();
        $r->get('/ankara-{slug}', fn($p) => 'service:' . $p['slug']);
        $r->get('/ankara/{slug}-mobilya', fn($p) => 'district:' . $p['slug']);

        $this->assertSame('service:mutfak-dolabi', $r->dispatch('GET', '/ankara-mutfak-dolabi'));
        $this->assertSame('district:cankaya', $r->dispatch('GET', '/ankara/cankaya-mobilya'));
    }

    public function testLocalBusinessDeclaresAnkaraServiceArea(): void
    {
        $ld = Seo::localBusiness(['site_name' => 'X', 'address' => 'Siteler, Altındağ / Ankara', 'working_hours' => 'Pzt–Cmt 09:00–19:00']);

        $this->assertContains('FurnitureStore', (array) $ld['@type']);
        $names = array_column($ld['areaServed'], 'name');
        $this->assertContains('Ankara', $names);
        $this->assertContains('Çankaya, Ankara', $names);
        $this->assertSame('Ankara', $ld['address']['addressLocality']);
    }

    public function testLocalBusinessOmitsGeoWhenCoordinatesAreUnknown(): void
    {
        $ld = Seo::localBusiness(['site_name' => 'X']);
        $this->assertArrayNotHasKey('geo', $ld);

        $ld = Seo::localBusiness(['site_name' => 'X', 'latitude' => '39.95', 'longitude' => '32.85']);
        $this->assertSame('39.95', $ld['geo']['latitude']);
    }

    public function testOpeningHoursParsing(): void
    {
        $h = Seo::openingHours('Pzt–Cmt 09:00–19:00');
        $this->assertSame('09:00', $h[0]['opens']);
        $this->assertSame('19:00', $h[0]['closes']);
        $this->assertSame(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'], $h[0]['dayOfWeek']);

        $this->assertSame([], Seo::openingHours('saat bilgisi yok'));
    }

    public function testFaqSchemaShape(): void
    {
        $ld = Seo::faq([['Soru?', 'Cevap.']]);
        $this->assertSame('FAQPage', $ld['@type']);
        $this->assertSame('Soru?', $ld['mainEntity'][0]['name']);
        $this->assertSame('Cevap.', $ld['mainEntity'][0]['acceptedAnswer']['text']);
    }

    public function testItemListPositionsStartAtOne(): void
    {
        $ld = Seo::itemList([['slug' => 'a', 'title' => 'A'], ['slug' => 'b', 'title' => 'B']], 'Liste');
        $this->assertSame([1, 2], array_column($ld['itemListElement'], 'position'));
        $this->assertSame(2, $ld['numberOfItems']);
    }

    public function testServiceSchemaBindsToAnkara(): void
    {
        $ld = Seo::service(Ankara::service('gardirop'));
        $this->assertSame('Service', $ld['@type']);
        $this->assertSame('Gardırop', $ld['serviceType']);
        $this->assertContains('Ankara', array_column($ld['areaServed'], 'name'));
    }
}

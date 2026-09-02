<?php
declare(strict_types=1);
use App\Slug;
use PHPUnit\Framework\TestCase;

final class SlugTest extends TestCase
{
    public function testTurkishCharacters(): void
    {
        $this->assertSame('sik-mutfak-cankaya-gorusu', Slug::make('Şık Mutfak Çankaya Görüşü'));
        $this->assertSame('ic-ve-dis', Slug::make('İç ve Dış!!'));
    }

    public function testUniqueAddsSuffix(): void
    {
        $taken = ['mutfak', 'mutfak-2'];
        $this->assertSame('mutfak-3', Slug::unique('Mutfak', fn($s) => in_array($s, $taken, true)));
        $this->assertSame('vestiyer', Slug::unique('Vestiyer', fn($s) => in_array($s, $taken, true)));
    }
}

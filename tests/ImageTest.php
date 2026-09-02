<?php
declare(strict_types=1);
use App\Image;
use PHPUnit\Framework\TestCase;

final class ImageTest extends TestCase
{
    private string $dir;

    protected function setUp(): void
    {
        $this->dir = sys_get_temp_dir() . '/sem-img-' . uniqid();
        mkdir($this->dir);
    }

    protected function tearDown(): void
    {
        foreach (glob($this->dir . '/*') ?: [] as $f) unlink($f);
        rmdir($this->dir);
    }

    public function testProcessesToWebpAndThumb(): void
    {
        $src = $this->dir . '/src.jpg';
        $im = imagecreatetruecolor(2400, 1600);
        imagefill($im, 0, 0, imagecolorallocate($im, 120, 80, 40));
        imagejpeg($im, $src);

        $r = Image::process($src, $this->dir);
        $this->assertStringEndsWith('.webp', $r['filename']);
        $this->assertSame(str_replace('.webp', '_thumb.webp', $r['filename']), $r['thumb']);
        [$w] = getimagesize($this->dir . '/' . $r['filename']);
        [$tw] = getimagesize($this->dir . '/' . $r['thumb']);
        $this->assertSame(1600, $w);
        $this->assertSame(480, $tw);
    }

    public function testSmallImageNotUpscaled(): void
    {
        $src = $this->dir . '/small.png';
        $im = imagecreatetruecolor(300, 200);
        imagepng($im, $src);
        $r = Image::process($src, $this->dir);
        [$w] = getimagesize($this->dir . '/' . $r['filename']);
        $this->assertSame(300, $w);
    }

    public function testRejectsNonImage(): void
    {
        $src = $this->dir . '/bad.jpg';
        file_put_contents($src, 'not an image');
        $this->expectException(InvalidArgumentException::class);
        Image::process($src, $this->dir);
    }

    public function testValidateReportsErrors(): void
    {
        $this->assertNotNull(Image::validate(['error' => UPLOAD_ERR_INI_SIZE, 'size' => 0, 'tmp_name' => '']));
        $this->assertNotNull(Image::validate(['error' => UPLOAD_ERR_OK, 'size' => 30 * 1024 * 1024, 'tmp_name' => '/nonexistent']));
    }
}

<?php
declare(strict_types=1);
use App\RateLimit;
use PHPUnit\Framework\TestCase;

final class RateLimitTest extends TestCase
{
    private string $dir;

    protected function setUp(): void
    {
        $this->dir = sys_get_temp_dir() . '/sem-rl-' . uniqid();
        mkdir($this->dir);
        RateLimit::$dir = $this->dir;
    }

    protected function tearDown(): void
    {
        foreach (glob($this->dir . '/*') ?: [] as $f) unlink($f);
        rmdir($this->dir);
    }

    public function testAllowsUpToMaxThenBlocks(): void
    {
        $t = 1000;
        $this->assertTrue(RateLimit::hit('k', 3, 300, $t));
        $this->assertTrue(RateLimit::hit('k', 3, 300, $t + 1));
        $this->assertTrue(RateLimit::hit('k', 3, 300, $t + 2));
        $this->assertFalse(RateLimit::hit('k', 3, 300, $t + 3));
    }

    public function testWindowExpires(): void
    {
        $t = 1000;
        for ($i = 0; $i < 3; $i++) RateLimit::hit('w', 3, 300, $t);
        $this->assertFalse(RateLimit::hit('w', 3, 300, $t + 10));
        $this->assertTrue(RateLimit::hit('w', 3, 300, $t + 301));
    }

    public function testKeysAreIndependent(): void
    {
        for ($i = 0; $i < 3; $i++) RateLimit::hit('a', 3, 300, 1);
        $this->assertTrue(RateLimit::hit('b', 3, 300, 1));
    }
}

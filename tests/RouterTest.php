<?php
declare(strict_types=1);
use App\NotFound;
use App\Router;
use PHPUnit\Framework\TestCase;

final class RouterTest extends TestCase
{
    public function testStaticRoute(): void
    {
        $r = new Router();
        $r->add('GET', '/hakkimizda', fn() => 'about');
        $this->assertSame('about', $r->dispatch('GET', '/hakkimizda'));
    }

    public function testParamRoute(): void
    {
        $r = new Router();
        $r->add('GET', '/projeler/{slug}', fn(array $p) => $p['slug']);
        $this->assertSame('mutfak-1', $r->dispatch('GET', '/projeler/mutfak-1'));
    }

    public function testTrailingSlashAndQueryIgnored(): void
    {
        $r = new Router();
        $r->add('GET', '/projeler', fn() => 'list');
        $this->assertSame('list', $r->dispatch('GET', '/projeler/?kategori=mutfak'));
    }

    public function testNotFound(): void
    {
        $r = new Router();
        $r->add('GET', '/', fn() => 'home');
        $this->expectException(NotFound::class);
        $r->dispatch('GET', '/yok');
    }

    public function testMethodMismatchIsNotFound(): void
    {
        $r = new Router();
        $r->add('POST', '/teklif-al', fn() => 'ok');
        $this->expectException(NotFound::class);
        $r->dispatch('GET', '/teklif-al');
    }
}

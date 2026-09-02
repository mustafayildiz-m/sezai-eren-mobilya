<?php
declare(strict_types=1);
use App\Auth;
use App\Database;
use PHPUnit\Framework\TestCase;

final class AuthTest extends TestCase
{
    protected function setUp(): void
    {
        Database::reset();
        @unlink(getenv('DB_PATH'));
        Database::migrate();
        $_SESSION = [];
    }

    public function testAttemptWithCorrectPassword(): void
    {
        $this->assertTrue(Auth::attempt('admin', 'secret123'));
        $this->assertTrue(Auth::check());
    }

    public function testAttemptWithWrongPassword(): void
    {
        $this->assertFalse(Auth::attempt('admin', 'nope'));
        $this->assertFalse(Auth::check());
    }

    public function testUnknownUser(): void
    {
        $this->assertFalse(Auth::attempt('ghost', 'secret123'));
    }

    public function testLogout(): void
    {
        Auth::attempt('admin', 'secret123');
        Auth::logout();
        $this->assertFalse(Auth::check());
    }
}

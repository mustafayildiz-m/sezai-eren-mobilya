<?php
declare(strict_types=1);
use App\QuoteValidator;
use PHPUnit\Framework\TestCase;

final class QuoteValidationTest extends TestCase
{
    public function testMissingRequiredFields(): void
    {
        $r = QuoteValidator::validate(['name' => '', 'phone' => ''], []);
        $this->assertArrayHasKey('name', $r['errors']);
        $this->assertArrayHasKey('phone', $r['errors']);
    }

    public function testHoneypotIsSpam(): void
    {
        $r = QuoteValidator::validate(['name' => 'A', 'phone' => '05551234567', 'website' => 'http://spam'], []);
        $this->assertArrayHasKey('spam', $r['errors']);
    }

    public function testInvalidPhoneAndEmail(): void
    {
        $r = QuoteValidator::validate(['name' => 'Ali', 'phone' => '12', 'email' => 'nope'], []);
        $this->assertArrayHasKey('phone', $r['errors']);
        $this->assertArrayHasKey('email', $r['errors']);
    }

    public function testValidNormalizesPhoneAndTrims(): void
    {
        $r = QuoteValidator::validate(['name' => '  Ayşe Kaya ', 'phone' => '0 (555) 123 45 67', 'email' => 'a@b.co', 'job_type' => 'Mutfak Dolabı', 'message' => "  merhaba\n"], []);
        $this->assertSame([], $r['errors']);
        $this->assertSame('Ayşe Kaya', $r['data']['name']);
        $this->assertSame('05551234567', $r['data']['phone']);
        $this->assertSame('merhaba', $r['data']['message']);
    }

    public function testTooLongMessageRejected(): void
    {
        $r = QuoteValidator::validate(['name' => 'A', 'phone' => '05551234567', 'message' => str_repeat('x', 5001)], []);
        $this->assertArrayHasKey('message', $r['errors']);
    }
}

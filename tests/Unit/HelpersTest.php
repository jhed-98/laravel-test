<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class HelpersTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_mail_validation(): void
    {
        $resp = mail_validation('cod.mobile.jhed@gmail.com');
        $this->assertTrue($resp);

        $resp = mail_validation('cod.mobile.jhed');
        $this->assertFalse($resp);
    }
}

<?php

namespace Tests\Feature;

use Tests\TestCase as TestCaseBase;

class TestCase extends TestCaseBase
{
    public function asMainUser()
    {
        $token = auth()->tokenById(1);
        return $this->withHeaders(['Authorization' => "Bearer {$token}"]);
    }

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
        $this->artisan('db:seed');
    }
}

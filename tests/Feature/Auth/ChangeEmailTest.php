<?php

namespace Transave\ScolaCbt\Tests\Feature\Auth;

use Transave\ScolaCbt\Actions\Auth\ChangeEmail;
use Transave\ScolaCbt\Tests\TestCase;

class ChangeEmailTest extends TestCase
{
    private $request;

    public function setUp(): void
    {
        parent::setUp();
        $this->testData();
    }

    /** @test */
    public function can_change_email()
    {
        $response = (new ChangeEmail($this->request))->execute();

        $this->assertTrue($response['success']);
        $this->assertNotNull($response['data']);
    }

    private function testData()
    {
        $this->request = [
            'email' => 'mail@example.com',
        ];
    }
}
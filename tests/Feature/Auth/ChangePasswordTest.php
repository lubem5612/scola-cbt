<?php

namespace Transave\ScolaCbt\Tests\Feature\Auth;

use Transave\ScolaCbt\Actions\Auth\ChangePassword;
use Transave\ScolaCbt\Tests\TestCase;

class ChangePasswordTest extends TestCase
{
    private $request;
    public function setUp(): void
    {
        parent::setUp();
        $this->testData();
    }

    /** @test */
    public function can_change_password(){
        $response = (new ChangePassword($this->request))->execute();
        $this->assertEquals(true, $response['success']);
        $this->assertNotNull($response['data']);
    }

    private function testData(){
        $this->request = [
            'password' => 'password1234',
        ];
    }
}
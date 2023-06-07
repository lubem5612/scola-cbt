<?php

namespace Transave\ScolaCbt\Tests\Unit\Models;

use Orchestra\Testbench\TestCase;
use Transave\ScolaCbt\Models\User;

class UserModelTest extends TestCase
{
    public function test_can_check_if_user_model_exists()
    {
        $this->assertTrue(class_exists(User::class), 'User model does not exist.');
    }
}

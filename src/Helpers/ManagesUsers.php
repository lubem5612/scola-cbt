<?php


namespace Transave\ScolaCbt\Helpers;


use Illuminate\Support\Arr;
use Transave\ScolaCbt\Actions\Auth\RegisterUser;

trait ManagesUsers
{
    protected function userRegistration(array $request)
    {
        $userData = Arr::only($request, ['first_name', 'last_name', 'email', 'role', 'password']);
        return (new RegisterUser($userData));
    }
}
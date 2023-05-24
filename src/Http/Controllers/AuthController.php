<?php


namespace Transave\ScolaCbt\Http\Controllers;


use Illuminate\Http\Request;
use Transave\ScolaCbt\Actions\Auth\Login;

class AuthController extends Controller
{

    public function __construct()
    {

    }

    public function login(Request $request)
    {
        $data = $request->only(['email', 'password']);
        $response = (new Login($data))->execute();
        return $response;
    }
}
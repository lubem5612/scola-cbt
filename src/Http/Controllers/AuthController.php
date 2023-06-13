<?php


namespace Transave\ScolaCbt\Http\Controllers;


use Illuminate\Http\Request;
use Transave\ScolaCbt\Actions\Auth\Login;
use Transave\ScolaCbt\Actions\Auth\Register;

class AuthController extends Controller
{

    public function __construct()
    {

    }

    public function login(Request $request)
    {
        $data = $request->only(['email', 'password']);
        return (new Login($data))->execute();
    }

    public function register(Request $request)
    {
        return (new Register($request->all()))->execute();
    }

}
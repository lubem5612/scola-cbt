<?php


namespace Transave\ScolaCbt\Actions\Auth;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Transave\ScolaCbt\Helpers\ResponseHelper;
use Transave\ScolaCbt\Helpers\ValidationHelper;
use Transave\ScolaCbt\Http\Models\User;

class Login
{
    use ResponseHelper, ValidationHelper;
    private $data;
    private $username;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function execute()
    {
        try {
            return $this
                ->validateLoginData()
                ->username()
                ->authenticateUser();
        }catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }

    private function authenticateUser()
    {
        $isAuth = auth()->attempt([$this->username => $this->data['email'], 'password' => $this->data['password']]);
        if ($isAuth) {
            $token = auth()->user()->createToken(uniqid())->plainTextToken;
            return $this->sendSuccess($token, 'login successful');
        }
        return $this->sendError('authentication failed');

//        $user = User::query()->where($this->username, $this->data['email'])->first();
//        if (empty($user)) {
//            return $this->sendError('user not found', [], 404);
//        }
//        if (!Hash::check($this->data['password'], $user->password)) {
//            return $this->sendError('password does not match user', [], 404);
//        }
//
//        if (method_exists(Auth::class, 'login'))
//            Auth::login($user, true);
//        $token = $user->createToken(uniqid())->plainTextToken;
//        return $this->sendSuccess($token, 'login successful');
    }

    private function username()
    {
        if(filter_var($this->data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->username = 'email';
        }
        elseif (Str::length($this->data['email']) >=9 || Str::contains($this->data['email'], "+")) {
            $this->username = 'phone';
        }else {
            $this->username = 'matriculation_number';
        }
        return $this;
    }

    private function validateLoginData()
    {
        $this->validate($this->data, [
            "email" => ["required"],
            "password" => ["required", "string"]
        ]);
        return $this;
    }

}
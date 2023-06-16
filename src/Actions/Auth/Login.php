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
        $isAuth = auth()->guard('api')->attempt([$this->username => $this->data['email'], 'password' => $this->data['password']]);
        if ($isAuth) {
            $token = auth()->guard('api')->user()->createToken(uniqid())->plainTextToken;
            return $this->sendSuccess($token, 'login successful');
        }
        return $this->sendError('authentication failed');
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
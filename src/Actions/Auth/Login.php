<?php


namespace Transave\ScolaCbt\Actions\Auth;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Transave\ScolaCbt\Helpers\ResponseHelper;
use Transave\ScolaCbt\Helpers\ValidationHelper;
use Transave\ScolaCbt\Http\Models\Student;

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
        if ($this->username != 'registration_number') {
            return $this->customLogin();
        }else {
            return $this->tokenLogin();
        }
    }

    private function customLogin()
    {
        $isAuth = auth()->guard('api')->attempt([$this->username => $this->data['email'], 'password' => $this->data['password']]);
        if ($isAuth) {
            $token = auth()->guard('api')->user()->createToken(uniqid())->plainTextToken;
            return $this->sendSuccess($token, 'login successful');
        }
        return $this->sendError('authentication failed', [], 401);
    }
    
    private function tokenLogin()
    {
        $student = Student::query()->where('registration_number', $this->username())->first();
        if (empty($student))
            return $this->sendError('token not found for student', [], 401);
        $user = $student->user;
        if (Hash::check($this->data['password'], $user->password)) {
            Auth::login($user, true);
            $token = Auth::user()->createToken(uniqid())->plainTextToken;
            return $this->sendSuccess($token, 'login successful');
        }
        return $this->sendError('authentication failed', [], 401);
    }

    private function username()
    {
        if(filter_var($this->data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->username = 'email';
        }
        elseif ($this->isPhoneNumber()) {
            $this->username = 'phone';
        }else {
            $this->username = 'registration_number';
        }
        return $this;
    }
    
    private function isPhoneNumber()
    {
        if (Str::contains($this->data['email'], "+")) {
            $trimmedEmail = Str::after($this->data['email'], '+');
            $trimmedEmail = Str::replace('-', '', $trimmedEmail);
            $trimmedEmail = Str::replace(' ', '', $trimmedEmail);
            $trimmedEmail = Str::replace('_', '', $trimmedEmail);
            $trimmedEmail = Str::replace(',', '', $trimmedEmail);
            $trimmedEmail = Str::replace('.', '', $trimmedEmail);
            if ((int)$trimmedEmail) {
                return true;
            }
        }
        return false;
    }

    private function validateLoginData()
    {
        $this->data = $this->validate($this->data, [
            "email" => ["required"],
            "password" => ["required", "string"]
        ]);
        return $this;
    }

}
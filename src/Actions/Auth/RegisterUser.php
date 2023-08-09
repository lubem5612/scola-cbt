<?php


namespace Transave\ScolaCbt\Actions\Auth;


use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Notification;
use Transave\ScolaCbt\Helpers\ResponseHelper;
use Transave\ScolaCbt\Helpers\ValidationHelper;
use Transave\ScolaCbt\Http\Models\User;
use Transave\ScolaCbt\Http\Notifications\WelcomeNotification;

class RegisterUser
{
    use ValidationHelper, ResponseHelper;
    private $request, $user;

    public function __construct(array  $request)
    {
        $this->request = $request;
    }

    public function execute()
    {
        try{
            return $this
                ->validateRequest()
                ->setUserPassword()
                ->setUserRole()
                ->setVerificationToken()
                ->sendNotification()
                ->createUser()
                ->buildResponse('created successfully', true, $this->user);
        }catch (\Exception $exception){
            return $this->sendServerError($exception);
        }
    }

    private function setUserPassword() : self
    {
        $this->request['password'] = bcrypt($this->request['password']);
        return $this;
    }

    private function setUserRole() :self
    {
        if (!array_key_exists('role', $this->request)) {
            $this->request['role'] = 'student';
        }
        return $this;
    }

    private function createUser()
    {
        $inputs = Arr::only($this->request, ['email', 'password', 'first_name', 'last_name', 'role']);
        $this->user = User::query()->create($inputs);
        if (empty($this->user)) {
            return $this->buildResponse('failed in creating user', false, null);
        }
        return $this;
    }

    private function setVerificationToken() : self
    {
        $this->request['token'] = rand(100000, 999999);
        $this->request['email_verified_at'] = Carbon::now();
        return $this;
    }

    private function sendNotification()
    {
        try {
            Notification::route('mail', $this->user->email)
                ->notify(new WelcomeNotification([
                    "token" => $this->request['token'],
                    "user" => $this->user
                ]));
        } catch (\Exception $exception) {
        }
        return $this;
    }

    private function validateRequest() : self
    {
        $this->validate($this->request, [
            "email" => 'required|string|max:80|unique:users',
            "password" => 'required|string|min:6',
            "first_name" => 'required|string|min:3|max:40',
            "last_name" => 'required|string|min:3|max:40',
            "role" => 'sometimes|required|in:student,examiner,staff,manager,admin'
        ]);

        return $this;
    }

}
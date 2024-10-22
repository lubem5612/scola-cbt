<?php


namespace Transave\ScolaCbt\Actions\Auth;


use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use Transave\ScolaCbt\Helpers\ResponseHelper;
use Transave\ScolaCbt\Helpers\ValidationHelper;
use Transave\ScolaCbt\Http\Models\User;
use Transave\ScolaCbt\Http\Notifications\WelcomeNotification;

class ResendEmailVerification
{
    use ResponseHelper, ValidationHelper;
    private $request, $user, $token;

    public function __construct(array $request)
    {
        $this->request = $request;
    }

    public function execute()
    {
        try {
            return $this
                ->validateRequest()
                ->setUser()
                ->setToken()
                ->saveToken()
                ->sendNotification()
                ->sendSuccess(null, 'token resend successfully');
        }catch (\Exception $exception) {
            return $this->sendServerError($exception);
        }
    }

    private function setToken()
    {
        $this->token = rand(100000, 999999);
        return $this;
    }

    private function setUser()
    {
        $this->user = User::query()->find($this->request['user_id']);
        return $this;
    }

    private function saveToken()
    {
        if ($this->user->is_verified) {
            return $this->sendSuccess(null, 'user already verified');
        }
        $this->user->update([
            "token" => $this->token,
            "email_verified_at" => Carbon::now()
        ]);
        return $this;
    }

    private function sendNotification()
    {
        try {
            Notification::route('mail', $this->user->email)
                ->notify(new WelcomeNotification([
                    "token" => $this->token,
                    "user" => $this->user
                ]));
        } catch (\Exception $exception) {
        }
        return $this;
    }

    private function validateRequest()
    {
        $this->validate($this->request, [
            "user_id" => 'required|exists:fc_users,id'
        ]);
        return $this;
    }
}
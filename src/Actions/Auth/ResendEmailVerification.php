<?php


namespace Transave\ScolaCbt\Actions\Auth;


use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use Transave\ScolaCbt\Helpers\ResponseHelper;
use Transave\ScolaCbt\Http\Models\User;
use Transave\ScolaCbt\Http\Notifications\WelcomeNotification;

class ResendEmailVerification
{
    use ResponseHelper;
    private $user, $token;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function execute()
    {
        try {
            return $this
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

    private function saveToken()
    {
        if ($this->user->is_verified) {
            return $this->sendSuccess(null, 'user already verified');
        }
        $updated = $this->user->update([
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

}
<?php


namespace Transave\ScolaCbt\Actions\Auth;


use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Transave\ScolaCbt\Helpers\ResponseHelper;
use Transave\ScolaCbt\Helpers\ValidationHelper;
use Transave\ScolaCbt\Http\Models\User;
use Transave\ScolaCbt\Http\Notifications\ResetPasswordNotification;

class ForgotPassword
{
    use ResponseHelper, ValidationHelper;
    private $request, $user, $token, $message;

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
                ->generateCode()
                ->deleteResetIfExists()
                ->createPasswordReset()
                ->sendNotification();
        }catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }

    private function setUser()
    {
        $this->user = User::query()->where("email", $this->request["email"])->first();
        return $this;
    }

    private function createPasswordReset()
    {
        DB::table('password_resets')->insert([
            "email" => $this->user->email,
            "token" => $this->token,
            "created_at" => Carbon::now()
        ]);

        $this->message = "A password reset token has been sent to your email";
        return $this;
    }

    private function generateCode()
    {
        $this->token = rand(100000, 999999);
        return $this;
    }

    private function deleteResetIfExists()
    {
        if (DB::table('password_resets')->where('email', $this->user->email)->exists()) {
            DB::table('password_resets')->where('email', $this->user->email)->delete();
        }
        return $this;
    }

    private function sendNotification()
    {
        try {
            Notification::route('mail', $this->user->email)
                ->notify(new ResetPasswordNotification([
                    "token" => $this->token,
                    "user" => $this->user
                ]));
        } catch (\Exception $exception) {
        }
        return $this->sendSuccess(null, $this->message);
    }

    private function validateRequest()
    {
        $this->validate($this->request, [
            "email" => 'string|email|exists:fc_users,email'
        ]);
        return $this;
    }
}
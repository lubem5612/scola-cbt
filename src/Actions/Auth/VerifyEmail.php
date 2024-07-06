<?php


namespace Transave\ScolaCbt\Actions\Auth;


use Carbon\Carbon;
use Transave\ScolaCbt\Helpers\ResponseHelper;
use Transave\ScolaCbt\Helpers\ValidationHelper;
use Transave\ScolaCbt\Http\Models\User;

class VerifyEmail
{
    use ResponseHelper, ValidationHelper;
    private User $user;
    private array $request;

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
                ->verifyUser();
        }catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }
    private function setUser()
    {
        $this->user = User::query()->where("token", $this->request["token"])->first();
        if (!$this->user) return $this->sendError("User not found", [], 404);

        if ($this->user->is_verified) return $this->sendSuccess(null, 'User already verified');

        if (Carbon::now()->gt(Carbon::parse($user->email_verified_at)->addMinutes(30))) {
            return $this->sendError("Verification Token Expire", [], 403);
        }

        return $this;
    }

    private function verifyUser()
    {
        $this->user->update([
            "email_verified_at" => Carbon::now(),
            "is_verified" => 1,
            "token" => null,
        ]);
        return $this->sendSuccess($this->user, "Email Verified");
    }

    private function validateRequest()
    {
        $this->validate($this->request, [
            "token" => 'string|digits_between:100000,999999|exists:fc_users,token'
        ]);
        return $this;
    }
}
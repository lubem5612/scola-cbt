<?php


namespace Transave\ScolaCbt\Actions\Auth;


use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Transave\ScolaCbt\Helpers\ResponseHelper;
use Transave\ScolaCbt\Helpers\ValidationHelper;
use Transave\ScolaCbt\Http\Models\User;

class ResetPassword
{
    use ResponseHelper, ValidationHelper;
    private $user, $request, $passwordReset;

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
                ->setPassword()
                ->deletePasswordReset();
        }catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }

    private function deletePasswordReset()
    {
        $this->passwordReset = DB::table('password_resets')->where("token", $this->request['token'])->delete();
        return $this->sendSuccess(null, "password reset successful");
    }
    
    private function setUser() 
    {
        $this->passwordReset = DB::table('password_resets')->where("token", $this->request['token'])->first();
        $this->user = User::query()->where("email", $this->passwordReset->email)->first();

        if (empty($this->user)) {
            return $this->sendError("No User with the token supplied", [], 404);
        }

        if (Carbon::now()->gt(Carbon::parse($this->passwordReset->created_at)->addHours(1))) {
            return $this->sendError("Token Expire", [], 403);
        }
        return $this;
    }

    private function setPassword()
    {
        $this->user->password = bcrypt($this->request["password"]);
        $this->user->save();
        return $this;
    }
    
    private function validateRequest() 
    {
        $this->validate($this->request, [
            "token" => 'required|digits_between:100000,999999',
            "password" => 'string|min:6',
        ]);
        return $this;
    }

}
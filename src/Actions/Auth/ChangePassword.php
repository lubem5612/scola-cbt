<?php

namespace Transave\ScolaCbt\Actions\Auth;

use Transave\ScolaCbt\Helpers\ResponseHelper;
use Transave\ScolaCbt\Helpers\ValidationHelper;
use Transave\ScolaCbt\Http\Models\User;

class ChangePassword
{
    use ResponseHelper, ValidationHelper;

    private $request;
    private User $user;

    public function __construct(array $request)
    {
        $this->request = $request;
    }

    public function execute()
    {
        try {
            return $this
                ->validatePassword()
                ->updatePassword()
                ->buildResponse('Password changed successfully.', true, $this->user, 200);
        } catch (\Exception $exception) {
            return $this->sendServerError($exception);
        }
    }

    private function validatePassword()
    {
        $this->validate($this->request, [
            "password" => 'string|min:6',
        ]);
        return $this;
    }

    private function updatePassword()
    {
        $this->user = User::findorfail($this->user->id);
        $this->user->password = bcrypt($this->request['password']);
        $this->user->save();
        return $this->sendSuccess($this->user, 'Password changed successfully');
    }
}
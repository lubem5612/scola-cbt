<?php

namespace Transave\ScolaCbt\Actions\Auth;

use Illuminate\Support\Facades\Hash;
use Transave\ScolaCbt\Helpers\ResponseHelper;
use Transave\ScolaCbt\Helpers\ValidationHelper;
use Transave\ScolaCbt\Http\Models\User;

class ChangePassword
{
    use ResponseHelper, ValidationHelper;

    private $request;
    private $user;

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
                ->changePassword();
        } catch (\Exception $exception) {
            return $this->sendServerError($exception);
        }
    }

    private function setUser()
    {
        $this->user = User::query()->find($this->request['user_id']);
        return $this;
    }
    private function validateRequest()
    {
        $this->validate($this->request, [
            'password' => 'required|string|min:6',
            'old_password' => 'required|string',
            'user_id' => 'required|exists:fc_users,id'
        ]);
        return $this;
    }

    private function changePassword()
    {
        if (Hash::check($this->request['old_password'], $this->user->password)) {
            $this->user->password = bcrypt($this->request['password']);
            $this->user->save();
            return $this->sendSuccess($this->user->refresh(), 'Password changed successfully');
        }
        return $this->sendError('password did not match existing one', [], 403);
    }
}

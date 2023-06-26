<?php

namespace Transave\ScolaCbt\Actions\Auth;

use Transave\ScolaCbt\Helpers\ResponseHelper;
use Transave\ScolaCbt\Helpers\ValidationHelper;
use Transave\ScolaCbt\Http\Models\User;

class ChangeEmail
{
    use ResponseHelper, ValidationHelper;
    private $request;
    private User $user;

    public function __construct(User $user, array $request)
    {
        $this->request = $request;
        $this->user = $user;
    }

    public function execute()
    {
        try {
            return $this
                ->validateNewEmail()
                ->updateEmail()
                ->buildResponse('Email address changed successfully.', true, $this->user, 200);
        } catch (\Exception $exception) {
            return $this->sendServerError($exception);
        }
    }

    private function validateNewEmail()
    {
        $this->validate($this->request, [
            'email' => 'required|email|unique:users,email'
        ]);
        return $this;
    }


    private function updateEmail()
    {
        $this->user = User::findorfail($this->user->email);
        $this->user->fill($this->request)->save();
        return $this->sendSuccess($this->user, 'Email updated successfully');

    }
}
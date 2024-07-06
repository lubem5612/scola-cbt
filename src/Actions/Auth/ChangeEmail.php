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

    public function __construct(array $request)
    {
        $this->request = $request;
    }

    public function execute()
    {
        try {
            return $this
                ->setUser()
                ->validateRequest()
                ->updateEmail();
        } catch (\Exception $exception) {
            return $this->sendServerError($exception);
        }
    }

    private function validateRequest()
    {
        $this->validate($this->request, [
            'email' => 'required|email|unique:fc_users,email',
            'user_id' => 'required|exists:fc_users,id'
        ]);
        return $this;
    }

    private function setUser()
    {
        $this->user = config('scola-cbt.auth_model')::query()->find($this->request['user_id']);
        return $this;
    }

    private function updateEmail()
    {
        $this->user->update([
            'email' => $this->request['email']
        ]);
        return $this->sendSuccess($this->user->refresh(), 'Email updated successfully');
    }
}
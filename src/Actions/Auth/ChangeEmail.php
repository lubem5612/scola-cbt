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
                ->updateEmail();
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
        $user = User::findOrFail($this->user->id);
        $user->fill($this->request)->save();
        return $this->sendSuccess($user, 'Email updated successfully');

    }
}
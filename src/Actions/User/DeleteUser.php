<?php

namespace Transave\ScolaCbt\Actions\User;

use Transave\ScolaCbt\Helpers\ResponseHelper;
use Transave\ScolaCbt\Helpers\ValidationHelper;
use Transave\ScolaCbt\Http\Models\User;

class DeleteUser
{
    use ValidationHelper, ResponseHelper;

    private $request, $user;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function execute()
    {
        try {
            return $this
                ->validateRequest()
                ->getUser()
                ->deleteUser();
        } catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }

    private function getUser()
    {
        $this->user = User::query()->find($this->request['user_id']);
        return $this;
    }

    private function deleteUser()
    {
        $this->user->delete();
        return $this->sendSuccess(null, 'user deleted successfully');
    }

    private function validateRequest(): self
    {
        $this->validate($this->request, [
            'user_id' => 'required|exists:fc_users,id',
        ]);
        return $this;
    }
}


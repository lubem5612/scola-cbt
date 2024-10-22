<?php


namespace Transave\ScolaCbt\Actions\User;


use Illuminate\Support\Arr;
use Transave\ScolaCbt\Helpers\ResponseHelper;
use Transave\ScolaCbt\Helpers\ValidationHelper;
use Transave\ScolaCbt\Http\Models\User;

class UpdateUser
{
    use ValidationHelper, ResponseHelper;

    private $request, $user, $validatedData;
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
                ->updateUser()
                ->updateAccountDetails();
        }catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }

    private function updateAccountDetails()
    {
        if ($this->user->role == 'student') {
            return (new UpdateStudent($this->request))->execute();
        }elseif ($this->user->role == 'staff') {
            return (new UpdateStaff($this->request))->execute();
        }elseif ($this->user->role == 'manager') {
            return (new UpdateManager($this->request))->execute();
        }elseif ($this->user->role == 'examiner') {
            return (new UpdateExaminer($this->request))->execute();
        }
        return $this->sendSuccess($this->user, 'user updated');
    }

    private function getUser()
    {
        $this->user = User::query()->find($this->validatedData['user_id']);
        return $this;
    }

    private function updateUser()
    {
        $userData = Arr::only($this->validatedData, ['first_name', 'last_name']);
        $this->user->fill($userData)->save();
        return $this;
    }

    private function validateRequest() : self
    {
        $this->validate($this->request, [
            'user_id' => 'required|exists:fc_users,id',
            'first_name' => 'sometimes|required|string|max:70',
            'last_name' => 'sometimes|required|string|max:70',
        ]);
        $this->validatedData = $this->validator->validated();
        return $this;
    }
}
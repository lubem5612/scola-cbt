<?php


namespace Transave\ScolaCbt\Actions\User;


use Illuminate\Support\Arr;
use Transave\ScolaCbt\Helpers\ResponseHelper;
use Transave\ScolaCbt\Helpers\ValidationHelper;

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
                ->updateAccountDetails()
                ->updateUser();
        }catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }

    private function updateAccountDetails()
    {
        if ($this->user->role == 'student') {
            (new UpdateStudent($this->request))->execute();
        }elseif ($this->user->role == 'staff') {
            (new UpdateStaff($this->request))->execute();
        }elseif ($this->user->role == 'manager') {
            (new UpdateManager($this->request))->execute();
        }elseif ($this->user->role == 'examiner') {
            (new UpdateExaminer($this->request))->execute();
        }
        return $this;
    }

    private function getUser()
    {
        $this->user = config('scola-cbt.auth_model')::query()->find($this->validatedData['user_id']);
        return $this;
    }

    private function updateUser()
    {
        $userData = Arr::only($this->validatedData, ['first_name', 'last_name']);
        $this->user->fill($userData)->save();
        return $this->sendSuccess($this->user->refresh(), 'user updated successfully');
    }

    private function validateRequest() : self
    {
        $this->validate($this->request, [
            'user_id' => 'required|exists:users,id',
            'first_name' => 'sometimes|required|string|max:70',
            'last_name' => 'sometimes|required|string|max:70',
        ]);
        $this->validatedData = $this->validator->validated();
        return $this;
    }
}
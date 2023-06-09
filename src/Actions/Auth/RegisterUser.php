<?php


namespace Transave\ScolaCbt\Actions\Auth;


use Illuminate\Support\Arr;
use Transave\ScolaCbt\Helpers\ResponseHelper;
use Transave\ScolaCbt\Helpers\ValidationHelper;

class RegisterUser
{
    use ValidationHelper, ResponseHelper;
    private $request;

    public function __construct(array  $request)
    {
        $this->request = $request;
    }

    public function execute()
    {
        try{
            return $this->validateRequest()->setUserPassword()->setUserRole()->createUser();
        }catch (\Exception $exception){
            return $this->sendServerError($exception);
        }
    }

    private function setUserPassword() : self
    {
        $this->request['password'] = bcrypt($this->request['password']);
        return $this;
    }

    private function setUserRole() :self
    {
        if (!Arr::exists($this->request, 'role')) {
            $this->request['role'] = 'student';
        }
    }

    private function createUser()
    {
        $user = \Transave\ScolaCbt\Models\User::query()->create($this->request);
        return $this->sendSuccess($user, 'created successfully');
    }

    private function validateRequest() : self
    {
        $this->validate($this->request, [
            "email" => 'required|string|max:80|unique:users',
            "password" => 'required|string|min:6',
            "first_name" => 'required|string|min:3|max:40',
            "last_name" => 'required|string|min:3|max:40',
            "role" => 'sometimes|required|in:student,examiner,staff,manager,admin'
        ]);

        return $this;
    }

}
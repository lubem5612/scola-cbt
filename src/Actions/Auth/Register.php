<?php
namespace Transave\ScolaCbt\Actions\Auth;

use Illuminate\Http\Request;
use Transave\ScolaCbt\Helpers\ResponseHelper;
use Transave\ScolaCbt\Helpers\ValidationHelper;
use Transave\ScolaCbt\Models\User;

class Register
{
    use  ResponseHelper, ValidationHelper;

    private Request $request;
    private User $user;

    public function handle(Request $request)
    {
        try {
            return $this
                ->setProperties($request)
                ->createUser()
                ->sendSuccess($this->user, 'registration successful');
        }catch (\Exception $exception){
            return $this->sendServerError($exception);
        }
    }

    private function setProperties(Request $request) : self {
        $this->request = $request;
        return $this;
    }

    private function createUser() : self {
        $this->user = new User;
        $this->user->fill([
            'first_name' => $this->request->input('first_name'),
            'last_name' => $this->request->input('last_name'),
            'email' => $this->request->input('email'),
            'password' => bcrypt($this->request->input('password')),
            'role' => 'student',
        ]);
        $this->user->save();

        return $this;
    }
}

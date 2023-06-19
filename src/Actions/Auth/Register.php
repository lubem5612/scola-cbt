<?php


namespace Transave\ScolaCbt\Actions\Auth;


use Illuminate\Support\Arr;
use Transave\ScolaCbt\Helpers\ResponseHelper;

class Register
{
    use ResponseHelper;
    private $request;
    public function __construct($request)
    {
        $this->request = $request;
    }

    public function execute()
    {
        try {
            return $this->register();
        }catch (\Exception $exception) {
            return $this->sendServerError($exception);
        }
    }

    private function register()
    {
        if (Arr::has($this->request, 'role')) {
            switch ($this->request['role']) {
                case 'admin' : {
                    $response = (new RegisterUser($this->request))->execute();
                    if ($response['success']) return $this->sendSuccess($response['data'], 'admin created successfully');
                    return $this->sendError('unable to create admin');
                    break;
                }
                case 'examiner' : {
                    return (new RegisterExaminer($this->request))->execute();
                    break;
                }
                case 'manager' : {
                    return (new RegisterManager($this->request))->execute();
                    break;
                }
                case 'staff' : {
                    return (new RegisterStaff($this->request))->execute();
                    break;
                }
                default : {
                    abort(501, 'user type not allowed');
                    break;
                }
            }
        }else {
            return (new RegisterStudent($this->request))->execute();
        }
    }
}
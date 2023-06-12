<?php

namespace Transave\ScolaCbt\Actions\Auth;

use Illuminate\Support\Arr;
use Transave\ScolaCbt\Helpers\ManagesUsers;
use Transave\ScolaCbt\Helpers\ResponseHelper;
use Transave\ScolaCbt\Helpers\ValidationHelper;
use Transave\ScolaCbt\Models\Admin;

class RegisterAdmin
{

    use ResponseHelper, ValidationHelper, ManagesUsers;

    private $request;
    public function __construct(array $request)
    {
        $this->request = $request;
    }

    public function execute()
    {
        try {
            return $this->validateRequest()->uploadProfilePicture()->createAdmin();
        }catch (\Exception $exception) {
            return $this->sendServerError($exception);
        }
    }


    private function createAdmin()
    {
        $response = $this->userRegistration($this->request);
        if (!$response['success']) {
            return $this->sendError('error in creating admin', ['message' => $response['message']]);
        }

        $adminData = Arr::except($this->request, ['first_name', 'last_name', 'email', 'role', 'password']);
        $adminData['user_id'] = $response['data']['id'];
        $admin = admin::query()->create($adminData);

        return $this->sendSuccess($admin->load('user'), 'admin created successfully');
    }

    private function uploadProfilePicture()
    {
        //function for uploading picture
        return $this;
    }

    private function validateRequest()
    {
        $this->validate($this->request, [
            'phone' => '',
            'address' => '',
        ]);

        return $this;
    }
}
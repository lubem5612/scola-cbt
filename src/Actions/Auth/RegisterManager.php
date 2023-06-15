<?php

namespace Transave\ScolaCbt\Actions\Auth;

use Illuminate\Support\Arr;
use Transave\ScolaCbt\Helpers\ManagesUsers;
use Transave\ScolaCbt\Helpers\ResponseHelper;
use Transave\ScolaCbt\Helpers\ValidationHelper;
use Transave\ScolaCbt\Http\Models\Manager;


class RegisterManager
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
            return $this->validateRequest()->uploadProfilePicture()->createManager();
        }catch (\Exception $exception) {
            return $this->sendServerError($exception);
        }
    }


    private function createManager()
    {
        $response = $this->userRegistration($this->request);
        if (!$response['success']) {
            return $this->sendError('error in creating user', ['message' => $response['message']]);
        }

        $managerData = Arr::except($this->request, ['first_name', 'last_name', 'email', 'role', 'password']);
        $managerData['user_id'] = $response['data']['id'];
        $manager = Manager::query()->create($managerData);

        return $this->sendSuccess($manager->load('user'), 'manager created successfully');
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
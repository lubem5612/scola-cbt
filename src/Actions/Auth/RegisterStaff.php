<?php

namespace Transave\ScolaCbt\Actions\Auth;


use Illuminate\Support\Arr;
use Transave\ScolaCbt\Helpers\ManagesUsers;
use Transave\ScolaCbt\Helpers\ResponseHelper;
use Transave\ScolaCbt\Helpers\ValidationHelper;
use Transave\ScolaCbt\Http\Models\Staff;

class RegisterStaff
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
            return $this->validateRequest()->uploadProfilePicture()->createStaff();
        }catch (\Exception $exception) {
            return $this->sendServerError($exception);
        }
    }


    private function createStaff()
    {
        $response = $this->userRegistration($this->request);
        if (!$response['success']) {
            return $this->sendError('error in creating staff', ['message' => $response['message']]);
        }

        $staffData = Arr::except($this->request, ['first_name', 'last_name', 'email', 'role', 'password']);
        $staffData['user_id'] = $response['data']['id'];
        $staff = Staff::query()->create($staffData);

        return $this->sendSuccess($staff->load('user'), 'staff created successfully');
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
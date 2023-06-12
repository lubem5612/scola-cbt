<?php

namespace Transave\ScolaCbt\Actions\Auth;

use Illuminate\Support\Arr;
use Transave\ScolaCbt\Helpers\ManagesUsers;
use Transave\ScolaCbt\Helpers\ResponseHelper;
use Transave\ScolaCbt\Helpers\ValidationHelper;
use Transave\ScolaCbt\Models\Student;

class RegisterStudent
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
            return $this->validateRequest()->uploadProfilePicture()->createStudent();
        }catch (\Exception $exception) {
            return $this->sendServerError($exception);
        }
    }


    private function createStudent()
    {
        $response = $this->userRegistration($this->request);
        if (!$response['success']) {
            return $this->sendError('error in creating student', ['message' => $response['message']]);
        }

        $studentData = Arr::except($this->request, ['first_name', 'last_name', 'email', 'role', 'password']);
        $studentData['user_id'] = $response['data']['id'];
        $student = Student::query()->create($studentData);

        return $this->sendSuccess($student->load('user'), 'student created successfully');
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
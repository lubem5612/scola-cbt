<?php


namespace Transave\ScolaCbt\Actions\Auth;


use Illuminate\Support\Arr;
use Transave\ScolaCbt\Helpers\ManagesUsers;
use Transave\ScolaCbt\Helpers\ResponseHelper;
use Transave\ScolaCbt\Helpers\ValidationHelper;
use Transave\ScolaCbt\Http\Models\Examiner;

class RegisterExaminer
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
            return $this->validateRequest()->uploadProfilePicture()->createExaminer();
        }catch (\Exception $exception) {
            return $this->sendServerError($exception);
        }
    }

    private function createExaminer()
    {
        $response = $this->userRegistration($this->request);
        if (!$response['success']) {
            return $this->sendError('error in creating user', ['message' => $response['message']]);
        }

        $examinerData = Arr::except($this->request, ['first_name', 'last_name', 'email', 'role', 'password']);
        $examinerData['user_id'] = $response['data']['id'];
        $examiner = Examiner::query()->create($examinerData);

        return $this->sendSuccess($examiner->load('user'), 'examiner created successfully');
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
<?php


namespace Transave\ScolaCbt\Actions\Auth;


use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Notification;
use Transave\ScolaCbt\Helpers\FileUploadHelper;
use Transave\ScolaCbt\Helpers\ResponseHelper;
use Transave\ScolaCbt\Helpers\ValidationHelper;
use Transave\ScolaCbt\Http\Models\Examiner;
use Transave\ScolaCbt\Http\Models\Manager;
use Transave\ScolaCbt\Http\Models\Staff;
use Transave\ScolaCbt\Http\Models\Student;
use Transave\ScolaCbt\Http\Notifications\WelcomeNotification;

class Register
{
    use ResponseHelper, ValidationHelper;
    private $request, $validatedData;
    private $user;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function execute()
    {
        try {
            return $this
                ->validateRequest()
                ->createUser()
                ->createExaminer()
                ->createManager()
                ->createStaff()
                ->createStudent()
                ->sendWelcomeNotification()
                ->sendSuccess($this->user, 'account created successfully');
        }catch (\Exception $exception) {
            return $this->sendServerError($exception);
        }
    }

    private function createUser()
    {
        $data = Arr::only($this->validatedData, ['first_name', 'last_name', 'email', 'password', 'role']);
        $data['remember_token'] = rand(100000, 999999);
        $data['email_verified_at'] = Carbon::now();
        $data['password'] = bcrypt($this->validatedData['password']);
        $data['role'] = $this->setUserRole();
        $data['telephone'] = $this->validatedData['phone'];

        $this->user = config('scola-cbt.auth_model')::query()->create($data);
        return $this;
    }

    private function createManager()
    {
        if (array_key_exists('role', $this->validatedData) && $this->validatedData['role'] == 'manager') {
            $data = Arr::only($this->validatedData, ['phone']);
            $data['user_id'] = $this->user->id;
            $data['photo'] = $this->uploadedPhotoUrl();

            Manager::query()->create($data);
        }
        return $this;
    }

    private function createExaminer()
    {
        if (array_key_exists('role', $this->validatedData) && $this->validatedData['role'] == 'examiner') {
            $data = Arr::only($this->validatedData, ['department_id', 'phone']);
            $data['user_id'] = $this->user->id;
            $data['photo'] = $this->uploadedPhotoUrl();

            Examiner::query()->create($data);
        }
        return $this;
    }

    private function createStudent()
    {
        if (!array_key_exists('role', $this->validatedData) || (array_key_exists('role', $this->validatedData) && $this->validatedData['role'] == 'student')) {
            $data = Arr::only($this->validatedData, ['phone', 'department_id', 'current_level', 'registration_number', 'address']);
            $data['user_id'] = $this->user->id;
            $data['photo'] = $this->uploadedPhotoUrl();

            Student::query()->create($data);
        }
        return $this;
    }

    private function createStaff()
    {
        if (array_key_exists('role', $this->validatedData) && $this->validatedData['role'] == 'staff') {
            $data = Arr::only($this->validatedData, ['department_id', 'phone', 'address']);
            $data['user_id'] = $this->user->id;
            $data['photo'] = $this->uploadedPhotoUrl();

            Staff::query()->create($data);
        }
        return $this;
    }

    private function uploadedPhotoUrl()
    {
        if (array_key_exists('photo', $this->validatedData)) {
            $response = FileUploadHelper::UploadFile($this->validatedData['photo'], 'cbt/profiles');
            if ($response['success']) {
                return $response['upload_url'];
            }
        }
        return null;
    }

    private function setUserRole()
    {
        if (array_key_exists('role', $this->validatedData) && $this->validatedData['role'])
        {
            return $this->validatedData['role'];
        }
        return 'student';
    }

    private function sendWelcomeNotification()
    {
        try {
            Notification::route('mail', $this->user->email)
                ->notify(new WelcomeNotification([
                    "token" => $this->request['token'],
                    "user" => $this->user
                ]));
        } catch (\Exception $exception) {
        }
        return $this;
    }

    private function validateRequest()
    {
        $this->validatedData = $this->validate($this->request, [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|max:80',
            'password' => 'required|string|min:6',
            'role' => 'nullable|string|in:admin,manager,student,staff,examiner',

            'department_id' => 'nullable|exists:cbt_departments,id',
            'phone' => 'required|string|min:8|max:16',
            'photo' => 'nullable|file|max:3000|mimes:jpeg,jpg,png,webp,gif',
            'address' => 'nullable|string|max:255',

            'registration_number' => 'required_if:role,==,student|max:50',
            'current_level' => 'nullable|string|max:20'
        ]);
        return $this;
    }
}
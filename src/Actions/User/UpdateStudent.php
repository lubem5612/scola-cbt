<?php


namespace Transave\ScolaCbt\Actions\User;


use Illuminate\Support\Arr;
use Transave\ScolaCbt\Helpers\FileUploadHelper;
use Transave\ScolaCbt\Helpers\ResponseHelper;
use Transave\ScolaCbt\Helpers\ValidationHelper;
use Transave\ScolaCbt\Http\Models\Student;

class UpdateStudent
{
    use ValidationHelper, ResponseHelper;

    private $request, $validatedData;
    private Student $student;
    public function __construct($request)
    {
        $this->request = $request;
    }

    public function execute()
    {
        try {
            return $this->validateRequest()->setStudent()->handleFileUpload()->updateStudent();
        }catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }

    private function setStudent() : self
    {
        $this->student = Student::query()
            ->where('user_id', $this->validatedData['user_id'])
            ->first();
        return $this;
    }

    private function handleFileUpload() : self
    {
        if (array_key_exists('photo', $this->request)) {
            $uploader = FileUploadHelper::UploadOrReplaceFile($this->request['photo'], 'cbt/profiles', $this->student, 'photo');
            if ($uploader['success']) {
                $this->validatedData['photo'] = $uploader['upload_url'];
            }
        }
       return $this;
    }

    private function updateStudent()
    {
        $input = Arr::only($this->validatedData, ['registration_number', 'phone', 'address', 'department_id', 'current_level', 'photo']);
        $this->student->fill($input)->save();
        return $this->sendSuccess($this->student->refresh(), 'student updated successfully');
    }

    private function validateRequest() : self
    {
        $this->validate($this->request, [
            'user_id' => 'required|exists:users,id',
            'registration_number' => 'sometimes|required|string',
            'phone' => 'sometimes|required|string|max:16|min:8',
            'address' => 'sometimes|required|string|max:255',
            'department_id' => 'sometimes|required|exists:departments,id',
            'photo' => 'sometimes|required|file|max:5000|mimes:jpeg,jpg,gif,png,webp',
            'current_level' => 'sometimes|required|integer|in:1,2,3,4,5,6'
        ]);
        $this->validatedData = Arr::except($this->validator->validated(), ['photo']);
        return $this;
    }
}
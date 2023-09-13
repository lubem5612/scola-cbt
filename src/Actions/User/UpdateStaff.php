<?php


namespace Transave\ScolaCbt\Actions\User;


use Illuminate\Support\Arr;
use Transave\ScolaCbt\Helpers\FileUploadHelper;
use Transave\ScolaCbt\Helpers\ResponseHelper;
use Transave\ScolaCbt\Helpers\ValidationHelper;
use Transave\ScolaCbt\Http\Models\Staff;

class UpdateStaff
{
    use ValidationHelper, ResponseHelper;

    private $request, $validatedData;
    private Staff $staff;
    public function __construct($request)
    {
        $this->request = $request;
    }

    public function execute()
    {
        try {
            return $this->validateRequest()->setStaff()->handleFileUpload()->updateStaff();
        }catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }

    private function setStaff() : self
    {
        $this->staff = Staff::query()
            ->where('user_id', $this->validatedData['user_id'])
            ->first();
        return $this;
    }

    private function handleFileUpload() : self
    {
        if (request()->hasFile('photo')) {
            $uploader = FileUploadHelper::UploadOrReplaceFile(request()->file('photo'), 'cbt/profiles', $this->staff, 'photo');
            if ($uploader['success']) {
                $this->validatedData['photo'] = $uploader['upload_url'];
            }
        }
        return $this;
    }

    private function updateStaff()
    {
        $input = Arr::only($this->validatedData, ['phone', 'address', 'department_id', 'photo']);
        $this->staff->fill($input)->save();
        return $this->sendSuccess($this->staff->user->refresh(), 'staff updated successfully');
    }

    private function validateRequest() : self
    {
        $this->validate($this->request, [
            'user_id' => 'required|exists:users,id',
            'phone' => 'sometimes|required|string|max:16|min:8',
            'address' => 'sometimes|required|string|max:255',
            'department_id' => 'sometimes|required|exists:departments,id',
            'photo' => 'sometimes|required|file|max:5000|mimes:jpeg,jpg,gif,png,webp',
        ]);
        $this->validatedData = Arr::except($this->validator->validated(), ['photo']);
        return $this;
    }
}
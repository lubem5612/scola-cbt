<?php


namespace Transave\ScolaCbt\Actions\User;


use Illuminate\Support\Arr;
use Transave\ScolaCbt\Helpers\FileUploadHelper;
use Transave\ScolaCbt\Helpers\ResponseHelper;
use Transave\ScolaCbt\Helpers\ValidationHelper;
use Transave\ScolaCbt\Http\Models\Examiner;

class UpdateExaminer
{
    use ValidationHelper, ResponseHelper;

    private $request, $validatedData;
    private Examiner $examiner;
    public function __construct($request)
    {
        $this->request = $request;
    }

    public function execute()
    {
        try {
            return $this->validateRequest()->setExaminer()->handleFileUpload()->updateExaminer();
        }catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }

    private function setExaminer() : self
    {
        $this->examiner = Examiner::query()
            ->where('user_id', $this->validatedData['user_id'])
            ->first();
        return $this;
    }

    private function handleFileUpload() : self
    {
        if (request()->hasFile('photo')) {
            $uploader = FileUploadHelper::UploadOrReplaceFile(request()->file('photo'), 'cbt/profiles', $this->examiner, 'photo');
            if ($uploader['success']) {
                $this->validatedData['photo'] = $uploader['upload_url'];
            }
        }
        return $this;
    }

    private function updateExaminer()
    {
        $input = Arr::only($this->validatedData, ['phone', 'department_id', 'photo']);
        $this->examiner->fill($input)->save();
        return $this->sendSuccess($this->examiner->user->refresh(), 'examiner updated successfully');
    }

    private function validateRequest() : self
    {
        $this->validate($this->request, [
            'user_id' => 'required|exists:users,id',
            'phone' => 'sometimes|required|string|max:16|min:8',
            'department_id' => 'sometimes|required|exists:departments,id',
            'photo' => 'sometimes|required|file|max:5000|mimes:jpeg,jpg,gif,png,webp',
        ]);
        $this->validatedData = Arr::except($this->validator->validated(), ['photo']);
        return $this;
    }

}
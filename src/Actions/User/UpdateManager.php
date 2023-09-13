<?php


namespace Transave\ScolaCbt\Actions\User;


use Illuminate\Support\Arr;
use Transave\ScolaCbt\Helpers\FileUploadHelper;
use Transave\ScolaCbt\Helpers\ResponseHelper;
use Transave\ScolaCbt\Helpers\ValidationHelper;
use Transave\ScolaCbt\Http\Models\Manager;

class UpdateManager
{
    use ValidationHelper, ResponseHelper;

    private $request, $validatedData;
    private Manager $manager;
    public function __construct($request)
    {
        $this->request = $request;
    }

    public function execute()
    {
        try {
            return $this->validateRequest()->setManager()->handleFileUpload()->updateManager();
        }catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }

    private function setManager() : self
    {
        $this->manager = Manager::query()
            ->where('user_id', $this->validatedData['user_id'])
            ->first();
        return $this;
    }

    private function handleFileUpload() : self
    {
        if (request()->hasFile('photo')) {
            $uploader = FileUploadHelper::UploadOrReplaceFile(request()->file('photo'), 'cbt/profiles', $this->manager, 'photo');
            if ($uploader['success']) {
                $this->validatedData['photo'] = $uploader['upload_url'];
            }
        }
        return $this;
    }

    private function updateManager()
    {
        $input = Arr::only($this->validatedData, ['phone', 'photo']);
        $this->manager->fill($input)->save();
        return $this->sendSuccess($this->manager->refresh(), 'manager updated successfully');
    }

    private function validateRequest() : self
    {
        $this->validate($this->request, [
            'user_id' => 'required|exists:users,id',
            'phone' => 'sometimes|required|string|max:16|min:8',
            'photo' => 'sometimes|required|file|max:5000|mimes:jpeg,jpg,gif,png,webp',
        ]);
        $this->validatedData = Arr::except($this->validator->validated(), ['photo']);
        return $this;
    }
}
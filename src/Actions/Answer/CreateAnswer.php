<?php


namespace Transave\ScolaCbt\Actions\Answer;


use Illuminate\Support\Arr;
use Transave\ScolaCbt\Helpers\FileUploadHelper;
use Transave\ScolaCbt\Helpers\ResponseHelper;
use Transave\ScolaCbt\Helpers\ValidationHelper;
use Transave\ScolaCbt\Http\Models\Answer;

class CreateAnswer
{
    use ResponseHelper, ValidationHelper;
    private $request, $validatedData;

    public function __construct(array $request)
    {
        $this->request = $request;
    }

    public function execute()
    {
        try {
            return
                $this
                    ->validateRequest()
                    ->setUser()
                    ->uploadFile()
                    ->createAnswer();
        }catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }

    private function createAnswer()
    {
        $answer = Answer::query()->create($this->validatedData);
        return $this->sendSuccess($answer->load('user', 'question', 'option'), 'answer created successfully');
    }

    private function setUser()
    {
        if (!array_key_exists('user_id', $this->validatedData))
        {
            $this->validatedData['user_id'] = auth()->id();
        }
        return $this;
    }

    private function uploadFile()
    {
        if (request()->hasFile('file'))
        {
            $response = FileUploadHelper::UploadFile(request()->file('file'), 'cbt/answers');
            if ($response['success']) {
                $this->validatedData['file'] = $response['upload_url'];
            }
        }
        return $this;
    }

    private function validateRequest()
    {
        $this->validate($this->request, [
            'user_id' => 'sometimes|required|exists:users,id',
            'question_id' => 'required|exists:questions,id',
            'option_id' => 'nullable|exists:options,id',
            'content' => 'sometimes|required|string',
            'file' => 'sometimes|required|file|max:4000|mimes:pdf,docx'
        ]);
        $this->validatedData = Arr::except($this->validator->validated(), ['file']);
        return $this;
    }
}
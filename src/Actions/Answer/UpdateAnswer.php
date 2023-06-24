<?php


namespace Transave\ScolaCbt\Actions\Answer;


use Illuminate\Support\Arr;
use Transave\ScolaCbt\Helpers\FileUploadHelper;
use Transave\ScolaCbt\Helpers\ResponseHelper;
use Transave\ScolaCbt\Helpers\ValidationHelper;
use Transave\ScolaCbt\Http\Models\Answer;

class UpdateAnswer
{
    use ResponseHelper, ValidationHelper;
    private $request, $validatedData;
    private Answer $answer;

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
                    ->setAnswer()
                    ->replaceOrUploadFile()
                    ->updateAnswer();
        }catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }

    private function updateAnswer()
    {
        $this->answer->fill($this->validatedData)->save();
        return $this->sendSuccess($this->answer->refresh()->load('user', 'question', 'option'), 'answer updated successfully');
    }

    private function setAnswer()
    {
        $this->answer = Answer::query()->find($this->validatedData['answer_id']);
        return $this;
    }

    private function setUser()
    {
        if (!array_key_exists('user_id', $this->validatedData))
        {
            $this->validatedData['user_id'] = auth()->user();
        }
        return $this;
    }

    private function replaceOrUploadFile()
    {
        if (request()->hasFile('file'))
        {
            $response = FileUploadHelper::UploadOrReplaceFile(request()->file('file'), 'cbt/answers', $this->answer, 'file');
            if ($response['success']) {
                $this->validatedData['file'] = $response['upload_url'];
            }
        }
        return $this;
    }

    private function validateRequest()
    {
        $this->validate($this->request, [
            'answer_id' => 'required|exists:answers,id',
            'user_id' => 'sometimes|required|exists:users,id',
            'question_id' => 'sometimes|required|exists:questions,id',
            'option_id' => 'sometimes|required|exists:options,id',
            'content' => 'sometimes|required|string',
            'file' => 'sometimes|required|file|max:4000|mimes:pdf,docx'
        ]);
        $this->validatedData = Arr::except($this->validator->validated(), ['file']);
        return $this;
    }
}
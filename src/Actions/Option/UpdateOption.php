<?php


namespace Transave\ScolaCbt\Actions\Option;


use Illuminate\Support\Arr;
use Transave\ScolaCbt\Helpers\FileUploadHelper;
use Transave\ScolaCbt\Helpers\ResponseHelper;
use Transave\ScolaCbt\Helpers\ValidationHelper;
use Transave\ScolaCbt\Http\Models\Option;

class UpdateOption
{
    use ResponseHelper, ValidationHelper;
    private $request, $validatedData;
    private Option $option;

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
                    ->setQuestionOption()
                    ->uploadOrReplaceFile()
                    ->updateQuestionOption();
        } catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }

    private function updateQuestionOption()
    {
        $this->option->fill($this->validatedData)->save();
        return $this->sendSuccess($this->option->refresh()->load('question'), 'question option updated successfully');
    }

    private function uploadOrReplaceFile()
    {
        if (array_key_exists('file', $this->request)) {
            $response = FileUploadHelper::UploadOrReplaceFile($this->request['file'], 'cbt/questions', $this->option, 'file');
            if ($response['success']) {
                $this->validatedData['file'] = $response['upload_url'];
            }
        }
        return $this;
    }

    private function setQuestionOption()
    {
        $this->option = Option::query()->first($this->validatedData['option_id']);
        return $this;
    }

    private function validateRequest()
    {
        $data = $this->validate($this->request, [
            'option_id' => 'required|exists:options,id',
            'question_id' => 'nullable|exists:questions,id',
            'is_correct_option' => 'nullable|in:yes,no',
            'content' => 'nullable|string',
            'file' => 'sometimes|required|file|max:4000|mimes:pdf,docx,png',
        ]);
        $this->validatedData = Arr::except($data, ['file']);
        return $this;
    }
}
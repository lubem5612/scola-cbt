<?php


namespace Transave\ScolaCbt\Actions\Option;


use Illuminate\Support\Arr;
use Transave\ScolaCbt\Helpers\FileUploadHelper;
use Transave\ScolaCbt\Helpers\ResponseHelper;
use Transave\ScolaCbt\Helpers\ValidationHelper;
use Transave\ScolaCbt\Http\Models\Option;

class CreateOption
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
                    ->uploadFile()
                    ->createQuestionOption();
        } catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }


    private function createQuestionOption()
    {
        $question = Option::query()->create($this->validatedData);
        return $this->sendSuccess($question->load('question'), 'question option created successfully');
    }

    private function uploadFile()
    {
        if (array_key_exists('file', $this->request)) {
            $response = FileUploadHelper::UploadFile($this->request['file'], 'cbt/questions');
            if ($response['success']) {
                $this->validatedData['file'] = $response['upload_url'];
            }
        }
        return $this;
    }

    private function validateRequest()
    {

        $data = $this->validate($this->request, [
            'question_id' => 'required|exists:questions,id',
            'is_correct_option' => 'nullable|in:yes,no',
            'content' => 'nullable|string',
            'file' => 'sometimes|required|file|max:4000|mimes:pdf,docx,png',
        ]);
        $this->validatedData = Arr::except($data, ['file']);
        return $this;
    }
}
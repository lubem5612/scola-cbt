<?php

namespace Transave\ScolaCbt\Actions\Question;

use Illuminate\Support\Arr;
use Transave\ScolaCbt\Helpers\FileUploadHelper;
use Transave\ScolaCbt\Helpers\ResponseHelper;
use Transave\ScolaCbt\Helpers\ValidationHelper;
use Transave\ScolaCbt\Http\Models\Question;

class UpdateQuestion
{
    use ResponseHelper, ValidationHelper;
    private $request, $validatedData;
    private Question $question;

    public function __construct(array $request)
    {
        $this->request = $request;
    }

    public function execute(){
        try {

        }catch (\Exception $e){
            return $this->sendServerError($e);
        }
    }


    private function updateQuestion(){
        $this->question->fill($this->validatedData)->save();
        return $this->sendSuccess($this->question->refresh()->load('exam'), 'question updated successfully');
    }

    private function setQuestion(){
        $this->question = Question::query()->find($this->validatedData['question_id']);
        return $this;
    }

    private function replaceOrUploadFile()
    {
        if (request()->hasFile('file'))
        {
            $response = FileUploadHelper::UploadOrReplaceFile(request()->file('file'), 'cbt/questions', $this->question, 'file');
            if ($response['success']) {
                $this->validatedData['file'] = $response['upload_url'];
            }
        }
        return $this;
    }


    private function validateRequest(){
        $this->validate($this->request, [
            'question_id' => 'required|exists:questions,id',
            'exam_id' => 'sometimes|required|exists:exams,id',
            'question_type' => 'sometimes|required|string|max:50',
            'score_obtainable' => 'sometimes|required|integer',
            'question' => 'sometimes|required|string',
            'file' => 'sometimes|required|file|max:4000|mimes:pdf,docx',
            'answers' => 'sometimes|required|string',
        ]);
        $this->validatedData = Arr::except($this->validator->validated(), ['file']);
        return $this;
    }

}
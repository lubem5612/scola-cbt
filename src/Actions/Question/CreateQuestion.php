<?php

namespace Transave\ScolaCbt\Actions\Question;

use Illuminate\Support\Arr;
use Transave\ScolaCbt\Helpers\FileUploadHelper;
use Transave\ScolaCbt\Helpers\ResponseHelper;
use Transave\ScolaCbt\Helpers\ValidationHelper;
use Transave\ScolaCbt\Http\Models\Question;

class CreateQuestion
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
                    ->createQuestion();
        } catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }


    private function createQuestion()
    {
        $question = Question::query()->create($this->validatedData);
        return $this->sendSuccess($question->load('exam'), 'question created successfully');
    }

//    private function setAnswers()
//    {
//        if (array_key_exists('answers', $this->validatedData)) {
//            //[a => big, b => medium, c => large]; [ 1, 3, 4, 6 ]
//            $this->validatedData['answers'] = json_encode( $this->validatedData['answers']);
//        }
//    }

    private function uploadFile()
    {
        if (request()->hasFile('file')) {
            $response = FileUploadHelper::UploadFile(request()->file('file'), 'cbt/questions');
            if ($response['success']) {
                $this->validatedData['file'] = $response['upload_url'];
            }
        }
        return $this;
    }

    private function validateRequest()
    {

        $this->validate($this->request, [
            'exam_id' => 'sometimes|required|exists:exams,id',
            'question_type' => 'required|string|max:50',
            'score_obtainable' => 'sometimes|required|integer',
            'question' => 'required|string',
            'file' => 'sometimes|required|file|max:4000|mimes:pdf,docx,png',
            'answers' => 'sometimes|required|string',
        ]);
        $this->validatedData = Arr::except($this->validator->validated(), ['file']);
        return $this;
    }
}
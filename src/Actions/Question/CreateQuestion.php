<?php

namespace Transave\ScolaCbt\Actions\Question;

use Illuminate\Support\Arr;
use Transave\ScolaCbt\Helpers\FileUploadHelper;
use Transave\ScolaCbt\Helpers\ResponseHelper;
use Transave\ScolaCbt\Helpers\ValidationHelper;
use Transave\ScolaCbt\Http\Models\Option;
use Transave\ScolaCbt\Http\Models\Question;

class CreateQuestion
{
    use ResponseHelper, ValidationHelper;

    private $request, $questionData;
    private ?Question $question;
    private ?Option $option;

    public function __construct(array $request)
    {
        $this->request = $request;
    }

    public function execute()
    {
        try {
            $this->validateRequest();
            $this->uploadQuestionFile();
            $this->createQuestion();
            $this->createOptions();
            return $this->sendSuccess($this->question->load('exams', 'options'), 'question created successfully');
        } catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }

    private function createQuestion()
    {
        $this->question = Question::query()->create($this->questionData);
    }

    private function uploadQuestionFile()
    {
        if (Arr::exists($this->request, 'file')) {
            $response = FileUploadHelper::UploadFile(request()->file('file'), 'cbt/questions');
            if ($response['success']) {
                $this->questionData['file'] = $response['upload_url'];
            }
        }
    }

    private function createOptions()
    {
        if (array_key_exists('options', $this->request) && is_array($this->request['options']) && count($this->request['options']) > 0) {
            foreach ($this->request['options'] as $option)
            {
                $optionData = Arr::only($option, ['is_correct_option', 'content']);
                $optionData['question_id'] = $this->question->id;
                if (Arr::exists($option, 'file')) {
                    $response = FileUploadHelper::UploadFile($option['file'], 'cbt/questions');
                    if ($response['success']) {
                        $optionData['file'] = $response['upload_url'];
                    }
                }

                Option::query()->create($optionData);
            }
        }
    }

    private function validateRequest()
    {
        $data = $this->validate($this->request, [
            'user_id' => 'required|exists:fc_users,id',
            'department_id' => 'sometimes|required|exists:cbt_departments,id',
            'course_id' => 'required|exists:cbt_courses,id',
            'level' => 'required|in:100,200,300,400,500,600',
            'question_type' => 'required|string|max:50',
            'score_obtainable' => 'sometimes|required|integer',
            'question' => 'required|string',
            'file' => 'sometimes|required|file|max:40000|mimes:jpeg,png,jpg,gif,mp4,webm,ogg,mp3,wav',
            'answers' => 'sometimes|required|string',
            'options' => 'sometimes|required|array',
            'options.*.is_correct_option' => 'nullable|in:yes,no',
            'options.*.content' => 'required_if:options.*.file,nullable|string',
            'options.*.file' => 'required_if:options.*.content,nullable|file|max:4000|mimes:pdf,docx,png,jpeg,jpg,bmp,webp'
        ]);
        $this->questionData = Arr::except($data, ['file', 'options']);
    }
}
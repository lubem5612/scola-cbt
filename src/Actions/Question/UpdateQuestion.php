<?php

namespace Transave\ScolaCbt\Actions\Question;

use Illuminate\Support\Arr;
use Transave\ScolaCbt\Helpers\FileUploadHelper;
use Transave\ScolaCbt\Helpers\ResponseHelper;
use Transave\ScolaCbt\Helpers\ValidationHelper;
use Transave\ScolaCbt\Http\Models\Option;
use Transave\ScolaCbt\Http\Models\Question;

class UpdateQuestion
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
            $this->setQuestion();
            $this->uploadOrReplaceQuestionFile();
            $this->updateQuestion();
            $this->updateOptions();
            return $this->sendSuccess($this->question->refresh()->load('exams', 'options'), 'question updated successfully');
        } catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }

    private function setQuestion()
    {
        $this->question = Question::query()->find($this->questionData['question_id']);
    }

    private function updateQuestion()
    {
        $this->question->fill(Arr::except($this->questionData, ['options', 'question_id']))->save();
    }

    private function uploadOrReplaceQuestionFile()
    {
        if (request()->hasFile('file')) {
            $response = FileUploadHelper::UploadOrReplaceFile(request()->file('file'), 'cbt/questions', $this->question, 'file');
            if ($response['success']) {
                $this->questionData['file'] = $response['upload_url'];
            }
        }
    }

    private function updateOptions()
    {
        if (array_key_exists('options', $this->request) && is_array($this->request['options']) && count($this->request['options']) > 0) {
            foreach ($this->request['options'] as $option)
            {
                if (Arr::exists($option, 'option_id')) {
                    $optionModel = Option::query()->find($option['option_id']);
                    $optionData = Arr::only($option, ['is_correct_option', 'content']);
                    $optionData['question_id'] = $this->question->id;
                    if (Arr::exists($option, 'file')) {
                        $response = FileUploadHelper::UploadOrReplaceFile($option['file'], 'cbt/questions', $optionModel, 'file');
                        if ($response['success']) {
                            $optionData['file'] = $response['upload_url'];
                        }
                    }

                    $optionModel->fill($optionData)->save();
                }
            }
        }
    }

    private function validateRequest()
    {
        $data = $this->validate($this->request, [
            'question_id' => 'required|exists:cbt_questions,id',
            'department_id' => 'sometimes|required|exists:cbt_departments,id',
            'course_id' => 'sometimes|required|exists:cbt_courses,id',
            'question_bank_id' => 'sometimes|required|exists:cbt_question_banks,id',
            'level' => 'sometimes|required|in:100,200,300,400,500,600',
            'question_type' => 'sometimes|required|string|max:50',
            'score_obtainable' => 'sometimes|required|integer',
            'question' => 'sometimes|required|string',
            'difficulty_level' => 'sometimes|required|string|in:difficult,very_difficult,moderate,easy,very_easy',
            'file' => 'sometimes|required|file|max:40000|mimes:jpeg,png,jpg,gif,mp4,webm,ogg,mp3,wav',
            'answers' => 'sometimes|required|string',
            'options' => 'sometimes|required|array',
            'options.*.option_id' => 'required_unless:options,nullable|exists:cbt_options,id',
            'options.*.is_correct_option' => 'nullable|in:yes,no',
            'options.*.content' => 'nullable|string',
            'options.*.file' => 'nullable|file|max:4000|mimes:pdf,docx,png,jpeg,jpg,bmp,webp'
        ]);
        $this->questionData = Arr::except($data, ['file', 'options']);

    }

}
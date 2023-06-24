<?php

namespace Transave\ScolaCbt\Actions\Question;

use Transave\ScolaCbt\Helpers\FileUploadHelper;
use Transave\ScolaCbt\Helpers\ResponseHelper;
use Transave\ScolaCbt\Helpers\ValidationHelper;
use Transave\ScolaCbt\Http\Models\Question;

class DeleteQuestion
{
    use ResponseHelper, ValidationHelper;

    private $request;
    private Question $question;

    public function __construct(array $request)
    {
        $this->request = $request;
    }

    public function execute()
    {
        try {
            return $this
                ->validateRequest()
                ->setQuestion()
                ->deleteFile()
                ->deleteQuestion();

        } catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }

    private function deleteQuestion()
    {
        $this->question->delete();
        return $this->sendSuccess(null, 'question deleted successfully');
    }


    private function deleteFile(): self
    {
        if ($this->question->file) {
            FileUploadHelper::DeleteFile($this->question->file);
        }
        return $this;
    }


    private function setQuestion(): self
    {
        $this->question = Question::query()->find($this->question['id']);
        return $this;
    }


    private function validateRequest(): self
    {
        $this->validate($this->request, [
            'id' => 'required|exists:questions,id'
        ]);
        return $this;
    }

}
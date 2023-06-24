<?php

namespace Transave\ScolaCbt\Actions\Question;

use Transave\ScolaCbt\Helpers\ResponseHelper;
use Transave\ScolaCbt\Helpers\ValidationHelper;
use Transave\ScolaCbt\Http\Models\Question;

class GetQuestion
{
    use ValidationHelper, ResponseHelper;

    private $request;
    private Question $question;


    public function __construct($request)
    {
        $this->request = $request;
    }

    public function execute()
    {
        try {
            return $this
                ->validateRequest()
                ->setQuestion()
                ->sendSuccess($this->question, 'question fetched successfully');

        } catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }

    private function setQuestion(): self
    {
        $this->question = Question::query()
            ->with('exam')
            ->find($this->request['id']);
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


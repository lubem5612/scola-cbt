<?php


namespace Transave\ScolaCbt\Actions\Answer;


use Transave\ScolaCbt\Helpers\ResponseHelper;
use Transave\ScolaCbt\Helpers\ValidationHelper;
use Transave\ScolaCbt\Http\Models\Answer;

class GetAnswer
{
    use ValidationHelper, ResponseHelper;
    private $request;
    private Answer $answer;

    /**
     * GetAnswer constructor.
     * @param $request
     */
    public function __construct($request)
    {
        $this->request = $request;
    }

    public function execute()
    {
        try {
            return $this
                ->validateRequest()
                ->setAnswer()
                ->sendSuccess($this->answer, 'answer fetched successfully');
        }catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }

    private function setAnswer() :self
    {
        $this->answer = Answer::query()
            ->with(['user', 'question', 'option'])
            ->find($this->request['id']);
        return  $this;
    }

    private function validateRequest() : self
    {
        $this->validate($this->request, [
            'id' => 'required|exists:answers,id'
        ]);
        return $this;
    }
}
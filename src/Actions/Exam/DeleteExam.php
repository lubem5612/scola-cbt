<?php


namespace Transave\ScolaCbt\Actions\Exam;


use Transave\ScolaCbt\Helpers\ResponseHelper;
use Transave\ScolaCbt\Helpers\ValidationHelper;
use Transave\ScolaCbt\Http\Models\Exam;

class DeleteExam
{
    use ResponseHelper, ValidationHelper;
    private $request;
    private Exam $exam;

    public function __construct(array $request)
    {
        $this->request = $request;
    }

    public function execute()
    {
        try {
            return $this
                ->validateRequest()
                ->setExam()
                ->deleteExam();
        }catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }

    private function deleteExam()
    {
        $this->exam->delete();
        return $this->sendSuccess(null, 'exam deleted successfully');
    }

    private function setExam() :self
    {
        $this->exam = Exam::query()->find($this->request['id']);
        return  $this;
    }

    private function validateRequest() : self
    {
        $this->validate($this->request, [
            'id' => 'required|exists:exams,id'
        ]);
        return $this;
    }

}
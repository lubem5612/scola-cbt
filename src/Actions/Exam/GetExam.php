<?php


namespace Transave\ScolaCbt\Actions\Exam;


use Transave\ScolaCbt\Helpers\ResponseHelper;
use Transave\ScolaCbt\Helpers\ValidationHelper;
use Transave\ScolaCbt\Http\Models\Exam;

class GetExam
{
    use ValidationHelper, ResponseHelper;
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
                ->sendSuccess($this->exam, 'exam fetched successfully');
        }catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }

    private function setExam() :self
    {
        $this->exam = Exam::query()
            ->with(['user', 'course', 'department', 'department.faculty', 'session', 'questions'])
            ->find($this->request['id']);
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
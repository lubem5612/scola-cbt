<?php


namespace Transave\ScolaCbt\Actions\Exam;


use Transave\ScolaCbt\Helpers\ResponseHelper;
use Transave\ScolaCbt\Helpers\ValidationHelper;
use Transave\ScolaCbt\Http\Models\Exam;
use Transave\ScolaCbt\Http\Models\ExamDepartment;

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
                ->deleteExamDepartments()
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

    private function deleteExamDepartments()
    {
        ExamDepartment::query()->where('exam_id', $this->exam->id)->delete();
        return $this;
    }

    private function validateRequest() : self
    {
        $this->validate($this->request, [
            'id' => 'required|exists:cbt_exams,id'
        ]);
        return $this;
    }

}
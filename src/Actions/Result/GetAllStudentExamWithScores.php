<?php


namespace Transave\ScolaCbt\Actions\Result;


use Illuminate\Database\Eloquent\Builder;
use Transave\ScolaCbt\Helpers\ResponseHelper;
use Transave\ScolaCbt\Helpers\ValidationHelper;
use Transave\ScolaCbt\Http\Models\StudentExam;

class GetAllStudentExamWithScores
{
    use ResponseHelper, ValidationHelper;
    private $queryBuilder, $search, $perPage, $output, $records = [];
    public function __construct()
    {
        $this->search = request()->query('search');
        $this->perPage = request()->query('per_page');
    }

    public function execute()
    {
        try {
            $this->initiateQueryBuilder();
            $this->filterBySearchParam();
            $this->queryByPage();
            $this->iterateOverUserExams();
            return $this->sendSuccess($this->records, 'records retrieved successfully');

        }catch (\Exception $exception)
        {
            return $this->sendServerError($exception);
        }
    }

    private function iterateOverUserExams()
    {
        $exam = request()->query('exam_id');

        foreach ($this->output as $output) {
            if (isset($exam)) {
                $value = (new GetStudentExamWithScores(['user_id' => $output->student->user_id, 'exam_id' => $exam]))->execute();
            }else {
                $value = (new GetStudentExamWithScores(['user_id' => $output->student->user_id, 'exam_id' => $output->exam_id]))->execute();
            }

            if ($value['success'] && isset($value['data'])) {
                array_push($this->records, $value['data']);
            }
        }
    }

    private function initiateQueryBuilder()
    {
        $this->queryBuilder = StudentExam::query()->with(['student.user', 'exam']);
    }

    private function queryByPage()
    {
        if (isset($this->perPage)) {
            $this->output = $this->queryBuilder->paginate($this->perPage);
        }else {
            $this->output = $this->queryBuilder->get();
        }
    }

    private function filterBySearchParam()
    {
        $search = $this->search;

        $student = request()->query('student_id');

        if (isset($student)) {
            $this->queryBuilder->where('student_id', $student);
        }

        $this->queryBuilder->whereHas('exam', function (Builder $builder) use ($search) {
            $builder->where('exam_name', 'like', "%$search%")
                ->orWhere('exam_mode', 'like', "%$search%");
        })->orWhereHas('student', function (Builder $query1) use ($search) {
            $query1->where('registration_number', 'like', "%$search%")
                ->orWhere('current_level', 'like', "%$search%");
        });
    }
}

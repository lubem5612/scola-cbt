<?php


namespace Transave\ScolaCbt\Actions\Exam;


use Transave\ScolaCbt\Helpers\SearchHelper;
use Transave\ScolaCbt\Http\Models\Exam;

class GetExamTimetable
{
    use SearchHelper;

    public function initQueryForModel()
    {
        $this->queryBuilder = Exam::query()->with(['departments:name']);
    }

    public function searchTerms()
    {
        $department = request()->query('department_id');
        $course = request()->query('course_id');

        if (isset($department)) {
            $this->queryBuilder = $this->queryBuilder
                ->whereHas('departments', function ($query) use ($department) {
                    $query->where('departments.id', $department);
                });
        }

        if (isset($course)) {
            $this->queryBuilder = $this->queryBuilder->where('course_id', $course);
        }
    }

    public function handlePagination()
    {
        if (is_null($this->id) && !isset($this->id)) {
            if (isset($this->perPage)) {
                $this->output = $this->queryBuilder->paginate($this->perPage);
            }else
                $this->output = $this->queryBuilder->paginate(10);

            $this->output->setCollection($this->output->getCollection()
                ->makeHidden(['user_id', 'course_id', 'session_id', 'semester', 'level', 'exam_mode', 'max_score_obtainable', 'instruction']));
        }
        return $this;
    }
}

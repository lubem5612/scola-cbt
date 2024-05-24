<?php


namespace Transave\ScolaCbt\Actions\Exam;


use Transave\ScolaCbt\Helpers\SearchHelper;
use Transave\ScolaCbt\Http\Models\Exam;

class GetExamTimetable
{
    use SearchHelper;

    public function initQueryForModel()
    {
        $this->queryBuilder = Exam::query()
            ->with('departments:name')
            ->select('exam_name', 'start_time', 'duration', 'unit_of_time', 'exam_date', 'venue');
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
}
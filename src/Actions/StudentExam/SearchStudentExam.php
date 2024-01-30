<?php


namespace Transave\ScolaCbt\Actions\StudentExam;


use Transave\ScolaCbt\Helpers\SearchHelper;


class SearchStudentExam
{
    use SearchHelper;
    public function searchTerms()
    {
        $search = $this->searchParam;
        $student = request()->query('student_id');
        $exam = request()->query('exam_id');
        if (isset($student)) $this->queryBuilder->where('student_id', $student);
        if (isset($exam)) $this->queryBuilder->where('exam_id', $exam);

        $this->queryBuilder->where(function ($query) use ($search) {
            $query
                ->orWhereHas('student', function ($query1) use ($search) {
                    $query1->where('registration_number', 'like', "%$search%")
                        ->orWhere('current_level', 'like', "%$search%");
                })
                ->orWhereHas('exam', function ($query2) use ($search) {
                    $query2->where('level', 'like', "%$search%")
                        ->orWhere('semester', 'like', "%$search%")
                        ->orWhere('exam_mode', 'like', "%$search%")
                        ->orWhere('exam_name', 'like', "%$search%");
                });
        });
    }
}
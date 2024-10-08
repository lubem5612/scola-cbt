<?php


namespace Transave\ScolaCbt\Actions\Exam;


use Transave\ScolaCbt\Helpers\SearchHelper;

class SearchExam
{
    use SearchHelper;

    private function searchTerms()
    {
        $search = $this->searchParam;
        $department = request()->query('department_id');
        $faculty = request()->query('faculty_id');
        $course = request()->query('course_id');
        $user = request()->query('user_id');
        $session = request()->query('session_id');
        $level = request()->query('level');
        $semester = request()->query('semester');
        $mode = request()->query('exam_mode');
        $student = request()->query('student_id');
    
        if (isset($student)) {
            $this->queryBuilder = $this->queryBuilder
                ->whereHas('students', function ($query) use ($student) {
                    $query->where('cbt_students.id', $student);
                });
        }

        if (isset($department)) {
            $this->queryBuilder = $this->queryBuilder
                ->whereHas('departments', function ($query) use ($department) {
                    $query->where('cbt_departments.id', $department);
                });
        }
        if (isset($faculty)) {
            $this->queryBuilder = $this->queryBuilder
                ->whereHas('departments', function ($query) use ($faculty) {
                    $query->whereHas('faculty', function ($query2) use ($faculty) {
                        $query2->where('id', $faculty);
                    });
                });
        }
        if (isset($course)) {
            $this->queryBuilder = $this->queryBuilder->where('course_id', $course);
        }
        if (isset($user)) {
            $this->queryBuilder = $this->queryBuilder->where('user_id', $user);
        }
        if (isset($session)) {
            $this->queryBuilder = $this->queryBuilder->where('session_id', $session);
        }
        if (isset($level)) {
            $this->queryBuilder = $this->queryBuilder->where('level', $level);
        }
        if (isset($semester)) {
            $this->queryBuilder = $this->queryBuilder->where('semester', $semester);
        }
        if (isset($mode)) {
            $this->queryBuilder = $this->queryBuilder->where('exam_mode', $mode);
        }

        $this->queryBuilder = $this->queryBuilder->where(function ($query) use ($search) {
            $query
                ->where('level', 'like', "%$search%")
                ->orWhere('semester', 'like', "%$search%")
                ->orWhere('exam_mode', 'like', "%$search%")
                ->orWhere('exam_name', 'like', "%$search%");
        });

        return $this;
    }
}
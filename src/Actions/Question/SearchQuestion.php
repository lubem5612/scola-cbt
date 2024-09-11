<?php

namespace Transave\ScolaCbt\Actions\Question;

use Transave\ScolaCbt\Helpers\SearchHelper;

class SearchQuestion
{
    use SearchHelper;

    private function searchTerms()
    {
        $course = request()->query('course_id');
        if (isset($course)) {
            $this->queryBuilder->where('course_id', $course);
        }
        if (isset($user)) {
            $this->queryBuilder->where('user_id', $user);
        }
        $department = request()->query('department_id');
        if (isset($department)) {
            $this->queryBuilder->where('department_id', $department);
        }
        $exam = request()->query('exam_id');
        if (isset($exam)) {
            $this->queryBuilder
                ->whereHas('exams', function ($query) use ($exam) {
                    $query->where('cbt_exams.id', $exam);
                });
        }
        
        $search = $this->searchParam;
        $this->queryBuilder->where(function ($query) use ($search) {
            $query->where('question_type', 'like', "%$search%")
                ->orWhere('question', 'like', "%$search%")
                ->orWhere('level', 'like', "%$search%")
                ->orWhere('score_obtainable', 'like', "%$search%")
                ->orWhereHas('user', function ($query3) use ($search) {
                    $query3->where('first_name', 'like', "%$search%")
                        ->orWhere('last_name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%");
                })
                ->orWhereHas('course', function ($query4) use ($search) {
                    $query4->where('name', 'like', "%$search%")
                        ->orWhere('code', 'like', "%$search%");
                });
        });

        return $this;
    }

    public function handlePagination()
    {
        if (is_null($this->id) && !isset($this->id)) {
            if (isset($this->perPage)) {
                $this->output = $this->queryBuilder->paginate($this->perPage);
            }else
                $this->output = $this->queryBuilder->get();
        }
        return $this;
    }
}
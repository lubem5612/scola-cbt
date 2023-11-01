<?php

namespace Transave\ScolaCbt\Actions\Search;

use Transave\ScolaCbt\Helpers\SearchHelper;

class SearchStudentExams
{
    use SearchHelper;

    public function searchTerms()
    {
        $search = $this->searchParam;
        $this->queryBuilder->where(function ($query) use ($search) {
            $query
                ->orWhereHas('student', function ($query1) use ($search) {
                    $query1->where('registration_number', 'like', "%$search%")
                        ->orWhere('current_level', 'like', "%$search%");
                })
                ->orWhereHas('exams', function ($query2) use ($search) {
                    $query2->where('level', 'like', "%$search%")
                        ->orWhere('semester', 'like', "%$search%")
                        ->orWhere('exam_mode', 'like', "%$search%")
                        ->orWhere('exam_name', 'like', "%$search%");
                });
        });

        return $this;
    }
}
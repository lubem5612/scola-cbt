<?php

namespace Transave\ScolaCbt\Actions\Question;

use Transave\ScolaCbt\Helpers\SearchHelper;

class SearchQuestion
{
    use SearchHelper;

    private function searchTerms()
    {
        $filter = request()->query('is_assigned');
        if (isset($filter)) {
            if ($filter == 'true') {
                $this->queryBuilder->whereNotNull('exam_id');
            }elseif ($filter == 'false') {
                $this->queryBuilder->whereNull('exam_id');
            }
        }
        $search = $this->searchParam;
        $this->queryBuilder->where(function ($query) use ($search) {
            $query->where('question_type', 'like', "%$search%")
                ->orWhere('question', 'like', "%$search%")
                ->orWhere('score_obtainable', 'like', "%$search%")
                ->orWhereHas('exam', function ($query1) use ($search) {
                    $query1->where('semester', 'like', "%$search%")
                        ->orWhere('level', 'like', "%$search%")
                        ->orWhere('max_score_obtainable', 'like', "%$search%")
                        ->orWhere('exam_mode', 'like', "%$search%")
                        ->orWhere('venue', 'like', "%$search%")
                        ->orWhereHas('user', function ($query3) use ($search) {
                            $query3->where('first_name', 'like', "%$search%")
                                ->orWhere('last_name', 'like', "%$search%")
                                ->orWhere('email', 'like', "%$search%");
                        })
                        ->orWhereHas('department', function ($query2) use ($search) {
                            $query2->where('name', 'like', "%$search%");
                        })
                        ->orWhereHas('course', function ($query4) use ($search) {
                            $query4->where('name', 'like', "%$search%")
                                ->orWhere('code', 'like', "%$search%");
                        })
                        ->orWhereHas('session', function ($query5) use ($search) {
                            $query5->where('name', 'like', "%$search%");
                        });
                });
        });

        return $this;
    }
}
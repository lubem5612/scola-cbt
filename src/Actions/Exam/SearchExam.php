<?php


namespace Transave\ScolaCbt\Actions\Exam;


use Transave\ScolaCbt\Helpers\SearchHelper;

class SearchExam
{
    use SearchHelper;

    private function searchTerms()
    {
        $search = $this->searchParam;
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
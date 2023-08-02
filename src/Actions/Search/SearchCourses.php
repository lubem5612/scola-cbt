<?php


namespace Transave\ScolaCbt\Actions\Search;


use Transave\ScolaCbt\Helpers\SearchHelper;

class SearchCourses
{
    use SearchHelper;

    private function searchTerms()
    {
        $search = $this->searchParam;
        $this->queryBuilder
            ->where('name', 'like', "%$this->searchParam%")
            ->orWhere('code', 'like', "%$this->searchParam%")
            ->orWhere('department_id', 'like', "%$this->searchParam%")
            ->orWhere('credit_load', 'like', "%$this->searchParam%")
            ->orWhereHas('department', function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhereHas('faculty', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%$search%");
                    });
            });
        return $this;
    }
}
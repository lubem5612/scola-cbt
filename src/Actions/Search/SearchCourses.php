<?php


namespace Transave\ScolaCbt\Actions\Search;


use Transave\ScolaCbt\Helpers\SearchHelper;

class SearchCourses
{
    use SearchHelper;

    private function searchTerms()
    {
        $this->queryBuilder
            ->where('name', 'like', "%$this->searchParam%")
            ->orWhere('code', 'like', "%$this->searchParam%")
            ->orWhere('credit_load', 'like', "%$this->searchParam%");
        return $this;
    }
}
<?php


namespace Transave\ScolaCbt\Actions\Search;


use Transave\ScolaCbt\Helpers\SearchHelper;

class SearchFaculties
{
    use SearchHelper;

    private function searchTerms()
    {
        $this->queryBuilder
            ->where('name', 'like', "%$this->searchParam%");
        return $this;
    }
}
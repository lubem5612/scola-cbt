<?php


namespace Transave\ScolaCbt\Actions\Search;


use Transave\ScolaCbt\Helpers\SearchHelper;

class SearchSessions
{
    use SearchHelper;

    private function searchTerms()
    {
        $this->queryBuilder
            ->where('name', 'like', "%$this->searchParam%")
            ->orWhere('is_active', 'like', "%$this->searchParam%");
        return $this;
    }
}
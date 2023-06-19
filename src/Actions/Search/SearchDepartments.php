<?php


namespace Transave\ScolaCbt\Actions\Search;


use Transave\ScolaCbt\Helpers\SearchHelper;

class SearchDepartments
{
    use SearchHelper;

    private function searchTerms()
    {
        $search = $this->searchParam;
        $this->queryBuilder
            ->where('name', 'like', "%{$search}%")
            ->orWhereHas('faculty', function ($query) use ($search) {
                $query->where('name', 'like', "%$search%");
            });
        return $this;
    }
}
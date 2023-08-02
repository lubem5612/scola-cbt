<?php


namespace Transave\ScolaCbt\Actions\Search;


use Transave\ScolaCbt\Helpers\SearchHelper;

class SearchCourses
{
    use SearchHelper;

    private function searchTerms()
    {
        $search = $this->searchParam;
        $this->queryBuilder->where(function ($query) use ($search) {
            $query->where('name', 'like', "%$search")
                ->orWhere('code', 'like', "%$search")
                ->orWhere('credit_load', 'like', "%$search")
                ->orWhereHas('department', function ($q) use ($search) {
                    $q->where('name', 'like', "%$search")
                        ->orWhere('id', 'like', "%$search")
                        ->orWhereHas('faculty', function ($x) use ($search) {
                            $x->where('name', 'like', "%$search%");
                        });
                });
        });

        return $this;
    }
}
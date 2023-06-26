<?php

namespace Transave\ScolaCbt\Actions\Question;

use Transave\ScolaCbt\Helpers\SearchHelper;

class SearchQuestion
{
    use SearchHelper;

    private function searchTerms()
    {
        $search = $this->searchParam;
        $this->queryBuilder = $this->queryBuilder->where(function ($query) use ($search) {
            $query
                ->where('score_obtainable', 'like', "%$search%")
                ->orWhere('question_type', 'like', "%$search%")
                ->orWhere('question', 'like', "%$search%")
                ->orWhere('answers', 'like', "%$search%");
        });

        return $this;
    }
}
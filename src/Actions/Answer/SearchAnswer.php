<?php


namespace Transave\ScolaCbt\Actions\Answer;


use Transave\ScolaCbt\Helpers\SearchHelper;

class SearchAnswer
{
    use SearchHelper;

    private function searchTerms()
    {
        $search = $this->searchParam;
        $this->queryBuilder->where(function ($query) use ($search) {
            $query
                ->orWhereHas('user', function ($query1) use ($search) {
                    $query1->where('first_name', 'like', "%$search%")
                        ->orWhere('last_name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%");
                })
                ->orWhereHas('option', function ($query2) use ($search) {
                    $query2->where('is_correct_option', 'like', "%$search%")
                        ->orWhere('content', 'like', "%$search%");
                })
                ->orWhereHas('question', function ($query3) use ($search) {
                    $query3->where('score_obtainable', 'like', "%$search%")
                        ->orWhere('question_type', 'like', "%$search%");
                });
        });

        return $this;
    }
}
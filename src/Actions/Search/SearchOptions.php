<?php


namespace Transave\ScolaCbt\Actions\Search;


use Transave\ScolaCbt\Helpers\SearchHelper;

class SearchOptions
{
    use SearchHelper;

    public function searchTerms()
    {
        $search = $this->searchParam;
        $question = request()->query('question_id');
        if (isset($question)) {
            $this->queryBuilder->where('question_id', $question);
        }
        $this->queryBuilder
            ->where('is_correct_score', 'like', "%{$search}%")
            ->orWhereHas('question', function ($query) use ($search) {
                $query->Where('question_type', 'like', "%$search%")
                    ->orWhere('score_obtainable', 'like', "%$search%");
            });
        return $this;
    }
}
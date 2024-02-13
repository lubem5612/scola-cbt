<?php


namespace Transave\ScolaCbt\Actions\Option;


use Transave\ScolaCbt\Helpers\SearchHelper;

class SearchOption
{
    use SearchHelper;

    private function searchTerms()
    {
        $question = request()->query('question_id');

        if (isset($question)) {
            $this->queryBuilder->where('question_id', $question);
        }
        $search = $this->searchParam;
        $this->queryBuilder->where('is_correct_option', 'like', "%$search%")->orWhere('content', 'like', "%$search%");
        return $this;
    }
}
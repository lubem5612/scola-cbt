<?php


namespace Transave\ScolaCbt\Http\Controllers;


use Illuminate\Http\Request;
use Transave\ScolaCbt\Actions\Answer\CreateAnswer;
use Transave\ScolaCbt\Actions\Answer\DeleteAnswer;
use Transave\ScolaCbt\Actions\Answer\GetAnswer;
use Transave\ScolaCbt\Actions\Answer\SearchAnswer;
use Transave\ScolaCbt\Actions\Answer\UpdateAnswer;
use Transave\ScolaCbt\Http\Models\Answer;

class AnswerController extends Controller
{
    /**
     * AnswerController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Get a listing for all answers
     *
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaCbt\Helpers\Response
     */
    public function index()
    {
        return (new SearchAnswer(Answer::class, ['option', 'user', 'question']))->execute();
    }

    /**
     * Get a specified answer
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaCbt\Helpers\Response
     */
    public function show($id)
    {
        return (new GetAnswer(['id' => $id]))->execute();
    }

    /**
     * Store a new answer
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaCbt\Helpers\Response
     */
    public function create(Request $request)
    {
        return (new CreateAnswer($request->all()))->execute();
    }

    /**
     * Update a specified answer
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaCbt\Helpers\Response
     */
    public function update(Request $request, $id)
    {
        $inputs = $request->merge(['answer_id' => $id])->all();
        return (new UpdateAnswer($inputs))->execute();
    }

    /**
     * Delete a specified answer
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaCbt\Helpers\Response
     */
    public function destroy($id)
    {
        return (new DeleteAnswer(['id' => $id]))->execute();
    }
}
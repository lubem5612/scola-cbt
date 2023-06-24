<?php

namespace Transave\ScolaCbt\Http\Controllers;


use Illuminate\Http\Request;
use Transave\ScolaCbt\Actions\Question\CreateQuestion;
use Transave\ScolaCbt\Actions\Question\DeleteQuestion;
use Transave\ScolaCbt\Actions\Question\GetQuestion;
use Transave\ScolaCbt\Actions\Question\SearchQuestion;
use Transave\ScolaCbt\Actions\Question\UpdateQuestion;
use Transave\ScolaCbt\Http\Models\Question;

class QuestionController extends Controller
{
    /**
     * AnswerController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index(){
        return (new SearchQuestion(Question::class, ['exam']))->execute();
    }


    public function show($id){
        return (new GetQuestion(['id' => $id]))->execute();
    }

    public function create(Request $request){
        return (new CreateQuestion($request->all()))->execute();
    }

    public function update(Request $request, $id){
        $inputs = $request->merge(['answer_id' => $id])->all();
        return (new UpdateQuestion($inputs))->execute();
    }

    public function destroy($id){
        return (new DeleteQuestion(['id' => $id]))->execute();
    }
}
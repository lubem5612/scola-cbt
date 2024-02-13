<?php


namespace Transave\ScolaCbt\Http\Controllers;


use Illuminate\Http\Request;
use Transave\ScolaCbt\Actions\Option\CreateOption;
use Transave\ScolaCbt\Actions\Option\DeleteOption;
use Transave\ScolaCbt\Actions\Option\SearchOption;
use Transave\ScolaCbt\Actions\Option\UpdateOption;
use Transave\ScolaCbt\Http\Models\Option;

class QuestionOptionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index(){
        return (new SearchOption(Option::class, ['question']))->execute();
    }

    public function show($id){
        return (new SearchOption(Option::class, ['question'], $id))->execute();
    }

    public function create(Request $request){
        return (new CreateOption($request->all()))->execute();
    }

    public function update(Request $request, $id){
        $inputs = $request->merge(['option_id' => $id])->all();
        return (new UpdateOption($inputs))->execute();
    }

    public function destroy($id){
        return (new DeleteOption(['id' => $id]))->execute();
    }
}
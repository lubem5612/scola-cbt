<?php

namespace Transave\ScolaCbt\Http\Controllers;




use Illuminate\Http\Request;
use Transave\ScolaCbt\Actions\Exam\CreateExam;
use Transave\ScolaCbt\Actions\Exam\DeleteExam;
use Transave\ScolaCbt\Actions\Exam\GetExam;
use Transave\ScolaCbt\Actions\Exam\SearchExam;
use Transave\ScolaCbt\Actions\Exam\UpdateExam;
use Transave\ScolaCbt\Http\Models\Exam;

class ExamController extends Controller
{
    public function __construct()
    {

    }

    public function index()
    {
        return (new SearchExam(Exam::class, ['user', 'course', 'department', 'session']))->execute();
    }

    public function show($id)
    {
        return (new GetExam(['id' => $id]))->execute();
    }

    public function create(Request $request)
    {
        return (new CreateExam($request->all()))->execute();
    }

    public function update(Request $request, $id)
    {
        return (new UpdateExam($request->all(), $id))->execute();
    }

    public function destroy($id)
    {
        return (new DeleteExam(['id' => $id]))->execute();
    }
}
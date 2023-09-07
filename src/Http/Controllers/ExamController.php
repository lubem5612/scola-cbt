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
    /**
     * ExamController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth:sanctum']);
        $this->middleware(['student'])->only(['show', 'index']);
    }

    /**
     * Get a listing of exams
     *
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaCbt\Helpers\Response
     */
    public function index()
    {
        return (new SearchExam(Exam::class, ['user', 'course', 'department', 'session', 'questions']))->execute();
    }

    /**
     * Show a specified exam
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaCbt\Helpers\Response
     */
    public function show($id)
    {
        return (new GetExam(['id' => $id]))->execute();
    }

    /**
     * Create an exam
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaCbt\Helpers\Response
     */
    public function create(Request $request)
    {
        return (new CreateExam($request->all()))->execute();
    }

    /**
     * Update a specified exam
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaCbt\Helpers\Response
     */
    public function update(Request $request, $id)
    {
        $inputs = $request->merge(['exam_id' => $id])->all();
        return (new UpdateExam($inputs))->execute();
    }

    /**
     * Delete a specified exam
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaCbt\Helpers\Response
     */
    public function destroy($id)
    {
        return (new DeleteExam(['id' => $id]))->execute();
    }
}
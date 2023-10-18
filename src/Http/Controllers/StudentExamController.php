<?php

namespace Transave\ScolaCbt\Http\Controllers;

use Illuminate\Http\Request;
use Transave\ScolaCbt\Actions\StudentExam\CreateStudentExam;

class StudentExamController extends Controller
{
    /**
     * ExamController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth:sanctum']);
        $this->middleware(['student'])->only(['show', 'create']);
    }


    /**
     * Store a new student finished exam
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaCbt\Helpers\Response
     */
    public function create(Request $request)
    {
        return (new CreateStudentExam($request->all()))->execute();
    }



}
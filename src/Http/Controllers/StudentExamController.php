<?php


namespace Transave\ScolaCbt\Http\Controllers;


use Illuminate\Http\Request;
use Transave\ScolaCbt\Actions\StudentExam\CreateStudentExam;
use Transave\ScolaCbt\Actions\StudentExam\DeleteStudentExam;
use Transave\ScolaCbt\Actions\StudentExam\SearchStudentExam;
use Transave\ScolaCbt\Actions\StudentExam\UpdateStudentExam;
use Transave\ScolaCbt\Http\Models\StudentExam;

class StudentExamController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index(){
        return (new SearchStudentExam(StudentExam::class, ['exam', 'student']))->execute();
    }


    public function show($id){
        return (new SearchStudentExam(StudentExam::class, ['exam', 'student'], $id))->execute();
    }

    public function create(Request $request){
        return (new CreateStudentExam($request->all()))->execute();
    }

    public function update(Request $request, $id){
        $data = $request->merge(['student_exam_id' => $id]);
        return (new UpdateStudentExam($data->all()))->execute();
    }

    public function destroy($id){
        return (new DeleteStudentExam(['student_exam_id' => $id]))->execute();
    }

}
<?php


namespace Transave\ScolaCbt\Http\Controllers;


use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Transave\ScolaCbt\Actions\User\BatchStudentUpload;
use Transave\ScolaCbt\Exports\StudentsExport;

class StudentController extends Controller
{

    public function export()
    {
        return Excel::download(new StudentsExport(), Carbon::now()->format('Y-m-d-Hi').'-students.xlsx');
    }

    public function upload(Request $request)
    {
        return (new BatchStudentUpload($request->all()))->execute();
    }
}
<?php


namespace Transave\ScolaCbt\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class StudentsExport implements FromView
{
    public function view(): View
    {
        return view('cbt::exports.students', [
            'students' => [
                0 => ['first_name' => 'Scola', 'last_name' =>'Student', 'email' => 'student@gmail.com', 'registration_number' => 'SCBT-005-001']
            ]
        ]);
    }
}
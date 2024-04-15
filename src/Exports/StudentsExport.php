<?php


namespace Transave\ScolaCbt\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class StudentsExport implements FromArray
{
    public function array(): array
    {
        // TODO: Implement array() method.
    }
//    public function view(): View
//    {
//        return view('cbt::exports.students', [
//            'students' => [
//                0 => ['first_name' => 'Scola', 'last_name' =>'Student', 'email' => 'student@gmail.com', 'registration_number' => 'SCBT-005-001']
//            ]
//        ]);
//    }

}
<?php


namespace Transave\ScolaCbt\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class StudentsExport implements FromArray
{
    private $studentsExcel;

    public function __construct(array $data)
    {
        $this->studentsExcel = $data;
    }
    public function array(): array
    {
        return $this->studentsExcel;
    }

}
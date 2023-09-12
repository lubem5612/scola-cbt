<?php


namespace Transave\ScolaCbt\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class StudentImport implements ToCollection, WithHeadingRow, WithValidation
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        // TODO: Implement collection() method.
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [

        ];
    }
}
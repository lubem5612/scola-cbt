<?php


namespace Transave\ScolaCbt\Console;


use Illuminate\Console\Command;
use Transave\ScolaCbt\Http\Models\Exam;
use Transave\ScolaCbt\Http\Models\ExamDepartment;

class ExamDepartmentSeeder extends Command
{
    protected $signature = 'exams:populate';
    protected $description = 'populate exam_departments table with exams data';

    public function handle()
    {
        foreach (Exam::query()->get() as $exam) {
            ExamDepartment::query()->updateOrCreate([
                'exam_id' => $exam->id,
                'department_id' => $exam->department_id
            ]);
        }
    }
}
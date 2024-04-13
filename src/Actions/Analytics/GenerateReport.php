<?php


namespace Transave\ScolaCbt\Actions\Analytics;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Transave\ScolaCbt\Helpers\ResponseHelper;
use Transave\ScolaCbt\Http\Models\Answer;
use Transave\ScolaCbt\Http\Models\Exam;
use Transave\ScolaCbt\Http\Models\ExamSetting;
use Transave\ScolaCbt\Http\Models\Faculty;
use Transave\ScolaCbt\Http\Models\StudentExam;

class GenerateReport
{
    use ResponseHelper;
    private array $data = [];
    private array $countDistribution = [];
    private array $limitDistribution = [];
    public function __construct()
    {
        $this->limitDistribution = [0, 10, 20, 30, 40, 50, 60, 70, 80, 90, 100];
        $this->countDistribution = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
    }

    public function execute()
    {
        try {
            $this->getTests();
            $this->getMarksAnalysis();
            $this->getScoreDistributionData();
            $this->getStudentDistribution();
            return $this->sendSuccess($this->data, 'report analysis obtained');
        }catch (\Exception $exception) {
            return $this->sendServerError($exception);
        }
    }

    private function getTests()
    {
        $this->data['test_created'] = Exam::query()->count();
        $this->data['test_taken'] = StudentExam::query()
            ->whereHas('student', function (Builder $builder) {
                $builder->whereHas('answers', function (Builder $builder2) {
                    $builder2->whereNotNull('answers.user_id');
                });
            })->count();
        $this->data['test_completed'] = Exam::query()
            ->whereHas('questions', function (Builder $builder) {
                $builder->whereHas('answers', function (Builder $builder2) {
                    $builder2->whereNotNull('answers.user_id');
                });
            })->count();
    }

    private function getMarksAnalysis()
    {
        $scores = 0; $averageScore = 0; $belowAverage = 0; $aboveAverage = 0; $total = 0; $differenceInMinutes = 0; $averageDuration = 0;
        foreach (StudentExam::lazy() as $studentExams) {
            $exam = $studentExams->exam;
            $questions = $exam->questions;
            $total = $total + 1;
            $totalQuestions = 0;
            foreach ($questions as $question_index => $question) {
                $answers = Answer::query()->where('question_id', $question->id)->orderByDesc('created_at')->get();

                $totalQuestions = $totalQuestions + 1;
                if (!empty($answers) && count($answers) > 0) {
                    $latest = $answers->toArray()[0];
                    $oldest = $answers->toArray()[count($answers) - 1];

                    $differenceInMinutes = $differenceInMinutes + Carbon::parse($latest['created_at'])->diffInMinutes($oldest['created_at']);

                    foreach ($answers as $answer) {
                        if (!empty($answer) && $answer->isCorrectOption()) {
                            $scores = $scores + (float)$question->score_obtainable;
                        }elseif (!is_null($answer->score) && floatval($answer->score)) {
                            $scores = $scores + floatval($answer->score);
                        }
                    }
                }
            }

            $averageScore = $scores / $totalQuestions;
            $averageDuration = $averageDuration + $differenceInMinutes / $totalQuestions;

            $examSetting = ExamSetting::query()->where('exam_id', $exam->id)->first();

            if (!empty($examSetting)) {
                $cutOffMarks = $examSetting->pass_mark_value;
                if ($cutOffMarks > $averageScore) $belowAverage = $belowAverage + 1;
                else $aboveAverage = $aboveAverage + 1;
            }

            $this->calculateScoreDistribution($averageScore);
        }

        $this->data['cumulative_below_average_score'] = $belowAverage;
        $this->data['cumulative_above_average_score'] = $aboveAverage;

        $this->data['cumulative_pass_ratio'] = $aboveAverage/$total;
        $this->data['cumulative_failure_ratio'] = $belowAverage/$total;

        $this->data['average_time_per_exam_in_minutes'] = $averageDuration/$total;
        return $this->data;
    }

    private function calculateScoreDistribution($score)
    {
        foreach ($this->limitDistribution as $index => $limit) {
            if ($index <= 9 ) {
                if ($score >= $limit && $score < $this->limitDistribution[$index + 1]) {
                    $this->countDistribution[$index] = $this->countDistribution[$index] + 1;
                }
            }elseif ($index == 10) {
                if ($score == $this->limitDistribution[10]) {
                    $this->countDistribution[$index] = $this->countDistribution[$index] + 1;
                }
            }
        }

    }

    private function getScoreDistributionData()
    {
        $scoreData = [];
        foreach ($this->countDistribution as $index => $score) {
            $item['lower_boundary'] = $this->limitDistribution[$index]; $item['upper_boundary'] = $this->limitDistribution[$index] + 10;
            $item['count'] = $score;
            array_push($scoreData, $item);
        }
        $this->data['result_distribution'] = $scoreData;
    }

    private function getStudentDistribution()
    {
        $studentCount = 0; $departmentScore = 0;
        $studentDistributionData = [];
        foreach (Faculty::query()->lazy() as $faculty)
        {
            $departments = $faculty->departments;
            foreach ($departments as $department) {
                $students = $department->students;
                foreach ($students as $student) {
                    $studentCount = $studentCount + 1;
                    $exams = $student->exams;
                    foreach ($exams as $exam) {
                        $questions = $exam->questions;
                        foreach ($questions as $question) {
                            $answers = Answer::query()->where('question_id', $question->id)->lazy(); $scores = 0;
                            foreach ($answers as $answer) {
                                if (!empty($answer) && $answer->isCorrectOption()) {
                                    $scores = $scores + (float)$question->score_obtainable;
                                }elseif (!is_null($answer->score) && floatval($answer->score)) {
                                    $scores = $scores + floatval($answer->score);
                                }
                            }
                            $departmentScore = $departmentScore + $scores;
                        }
                    }
                }
            }

            $item['average_score'] = $departmentScore / $studentCount;
            $item['faculty'] = $faculty->name;
            array_push($studentDistributionData, $item);
        }

        $this->data['student_distribution'] = $studentDistributionData;
    }
}

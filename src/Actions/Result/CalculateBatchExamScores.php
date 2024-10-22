<?php


namespace Transave\ScolaCbt\Actions\Result;


use Illuminate\Support\Facades\Log;
use Transave\ScolaCbt\Helpers\ValidationHelper;
use Transave\ScolaCbt\Http\Models\User;

class CalculateBatchExamScores
{
    use ValidationHelper;
    private $request, $student, $user;
    private $exams;
    private $scores=0, $responseMessage='', $isSuccess=false;

    public function __construct(array $request)
    {
        $this->request = $request;
    }

    public function execute()
    {
        try {
            $this->validateRequest();
            $this->getStudentUser();
            $this->getExams();
            $this->filterBySession();
            $this->filterBySemester();
            $this->calculateExamScores();
        } catch (\Exception $e) {
            Log::error($e);
            $this->responseMessage = $e->getMessage();
        }
        return $this->buildResponse();
    }

    private function getStudentUser()
    {
        $this->user = User::query()->find($this->request['user_id']);
        $this->student = $this->user->student;
        return $this;
    }

    private function getExams()
    {
        $this->exams = $this->student->exams;
    }

    private function filterBySession()
    {
        if (array_key_exists('session_id', $this->request) && $this->request['session_id'])
        {
            $this->exams->where('session_id', $this->request['session_id']);
        }
    }

    private function filterBySemester()
    {
        if (array_key_exists('session_id', $this->request)
            && $this->request['session_id']
            && array_key_exists('semester', $this->request)
            && $this->request['semester'])
        {
            $this->exams->where('semester', $this->request['semester']);
        }

        $this->exams = $this->exams->all();
    }

    private function calculateExamScores()
    {
        $scores = 0;
        if (!empty($this->exams)) {
            foreach ($this->exams as $exam) {
                $response = (new CalculateExamScore(['user_id' => $this->user->id, 'exam_id' => $exam->id]))->execute();
                if ($response['success']) {
                    $scores = $scores + (float)$response['data'];
                }
            }
        }
        $this->scores = $scores;
        $this->responseMessage = 'batch exam scores returned successfully';
        $this->isSuccess = true;
    }

    private function buildResponse()
    {
        return [
            'success' => $this->isSuccess,
            'message' => $this->responseMessage,
            'data' => $this->scores,
        ];
    }

    private function validateRequest()
    {
        $this->validate($this->request, [
            'user_id' => 'required|exists:fc_users,id',
            'session_id' => 'sometimes|required|exists:cbt_sessions,id',
            'semester' => 'sometimes|required|string'
        ]);

        return $this;
    }
}
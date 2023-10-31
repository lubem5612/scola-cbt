<?php


namespace Transave\ScolaCbt\Http\Controllers;


use Illuminate\Http\Request;
use Transave\ScolaCbt\Actions\Result\CalculateBatchExamScores;
use Transave\ScolaCbt\Actions\Result\CalculateExamScore;
use Transave\ScolaCbt\Helpers\ResponseHelper;

class ResultController extends Controller
{
    use ResponseHelper;

    /**
     * ResultController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * @param Request $request
     * @return \Transave\ScolaCbt\Helpers\Response
     */
    public function calculateSingleExam(Request $request)
    {
        $response = (new CalculateExamScore($request->all()))->execute();
        if ($response['success']) return $this->sendSuccess($response['data'], $response['message']);
        return $this->sendError($response['message']);
    }

    /**
     * @param Request $request
     * @return \Transave\ScolaCbt\Helpers\Response
     */
    public function calculateBatchExams(Request $request)
    {
        $response = (new CalculateBatchExamScores($request->all()))->execute();
        if ($response['success']) return $this->sendSuccess($response['data'], $response['message']);
        return $this->sendError($response['message']);
    }
}
<?php


namespace Transave\ScolaCbt\Helpers;


use Illuminate\Support\Facades\Log;

trait ResponseHelper
{
    /**
     * success response method.
     *
     * @param $result
     * @param $message
     * @param string $status
     * @return Response
     */
    public function sendSuccess($result, $message)
    {
        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];

        return response()->json($response, 200, [], JSON_INVALID_UTF8_SUBSTITUTE );
    }

    /**
     * return error response.
     *
     * @param $error
     * @param array $errorMessages
     * @param int $code
     * @return Response
     */
    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if(!empty($errorMessages)){
            $response['errors'] = $errorMessages;
        }

        return response()->json($response, $code, [], JSON_INVALID_UTF8_SUBSTITUTE );
    }

    /**
     * structured response returned for methods
     *
     * @param $message
     * @param bool $success
     * @param $data
     * @return mixed
     */
    public function buildResponse($message, $success=false, $data=null)
    {
        return [
            "message" => $message,
            "success" => $success,
            "data" => $data,
        ];
    }

    public function sendServerError(\Exception $exception, $code=500)
    {
        $response = [
            "success" => false,
            "message" => $exception->getMessage(),
            "data" => [],
            "errors" => $exception->getTraceAsString(),
        ];
        Log::error($exception->getTraceAsString());
        return response()->json($response, $code, [], JSON_INVALID_UTF8_SUBSTITUTE );
    }
}
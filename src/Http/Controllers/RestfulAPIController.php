<?php


namespace Transave\ScolaCbt\Http\Controllers;
use Illuminate\Http\Request;
use Transave\ScolaCbt\Helpers\RestFulAPIHelper;
use function Illuminate\Routing\Controllers\except;


class RestfulAPIController extends Controller
{
    private $api;

    public function __construct()
    {
        $this->api = new RestFulAPIHelper();
        $this->middleware('auth:sanctum')->except(['index', 'show']);
    }

    public function index(Request $request, $endpoint)
    {
        $data = $this->api->fetchAllResources($request, $endpoint);
        return $this->processData($data, 'resources retrieved successfully');
    }

    public function store(Request $request, $endpoint)
    {
        $data = $this->api->saveResource($request, $endpoint);
        return $this->processData($data, 'resource created successfully');
    }

    public function show($endpoint, $id)
    {
        $data = $this->api->fetchResource($endpoint, $id);
        return $this->processData($data, 'resource retrieved successfully');
    }

    public function update(Request $request, $endpoint, $id)
    {
        $data = $this->api->updateResource($request, $endpoint, $id);
        return $this->processData($data, 'resource updated successfully');
    }

    public function destroy($endpoint, $id)
    {
        $data = $this->api->deleteResource($endpoint, $id);
        return $this->processData($data, 'resource deleted successfully');
    }

    private function returnSuccess($result, $message, $code = '')
    {
        $code = isset($code)? $code : http_response_code();
        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];

        return response()->json($response, $code, [], JSON_INVALID_UTF8_SUBSTITUTE );
    }

    private function returnError($error, $errorMessages = [], $code = '')
    {
        $code = isset($code)? $code : http_response_code();
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if(!empty($errorMessages)){
            $response['errors'] = $errorMessages;
        }

        return response()->json($response, $code, [], JSON_INVALID_UTF8_SUBSTITUTE );
    }

    private function processData($data, $message='')
    {
        if ($data['errors']) {
            $msg = $data['errors']['message'];
            $result = null;
            if (array_key_exists('data', $data['errors'])) {
                $result = $data['errors']['data'];
            }
            return $this->returnError($msg, $result, $data['code']);
        }
        return $this->returnSuccess($data['data'], $message, http_response_code());
    }
}
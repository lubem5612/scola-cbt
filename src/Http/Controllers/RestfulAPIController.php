<?php


namespace Transave\ScolaCbt\Http\Controllers;
use Illuminate\Http\Request;
use Transave\ScolaCbt\Helpers\ResponseHelper;
use Transave\ScolaCbt\Helpers\RestFulAPIHelper;
use function Illuminate\Routing\Controllers\except;


class RestfulAPIController extends Controller
{
    use ResponseHelper;
    private $api;

    public function __construct()
    {
        $this->api = new RestFulAPIHelper();
        $this->middleware('auth:sanctum')->except(['index', 'show']);
    }

    public function index(Request $request, $endpoint)
    {
        return $this->structureResponse($this->api->fetchAllResources($request, $endpoint));
    }

    public function store(Request $request, $endpoint)
    {
        return $this->structureResponse($this->api->saveResource($request, $endpoint));
    }

    public function show($endpoint, $id)
    {
        return $this->structureResponse($this->api->fetchResource($endpoint, $id));
    }

    public function update(Request $request, $endpoint, $id)
    {
        return $this->structureResponse($this->api->updateResource($request, $endpoint, $id));
    }

    public function destroy($endpoint, $id)
    {
        return $this->structureResponse($this->api->deleteResource($endpoint, $id));
    }

    private function structureResponse(array $data)
    {
        if ($data['success']) {
            return $this->sendSuccess($data['data'], $data['message']);
        }
        return $this->sendError($data['message'], $data['data'], $data['code']);
    }
}
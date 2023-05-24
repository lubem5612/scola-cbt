<?php


namespace Transave\ScolaCbt\Helpers;


use Illuminate\Support\Facades\Validator;

class RestFulAPIHelper
{
    /**
     * @var \Illuminate\Config\Repository|mixed
     */
    public $routes;

    public function __construct()
    {
        $this->routes = config('endpoints.routes');
    }

    public function fetchAllResources($request, $endpoint)
    {
        try {

            if(!array_key_exists($endpoint, $this->routes)) {
                return $this->returnResponse(null, ['message' => 'endpoint not fount'], 400);
            }

            if (!array_key_exists('model', $this->routes[$endpoint])) {
                return $this->returnResponse(null, ['message' => 'model not specified'], 500);
            }

            if (array_key_exists('order', $this->routes[$endpoint]) &&
                array_key_exists('column', $this->routes[$endpoint]['order']) &&
                array_key_exists('pattern', $this->routes[$endpoint]['order'])) {
                $builder = $this->routes[$endpoint]['model']::orderBy($this->routes[$endpoint]['order']['column'], $this->routes[$endpoint]['order']['pattern']);
            }else {
                $builder = $this->routes[$endpoint]['model']::orderBy('created_at', 'desc');
            }

            $query = $request->query();

            if(isset($query) && count($query) > 0) {
                unset($query['page']);
                unset($query['limit']);
                $builder->where($query);
            }

            if ($request->query('page')) {
                $limit = $request->query('limit');
                $results = isset($limit)? $builder->paginate($limit) : $builder->paginate(10);
            }else {
                $results = $builder->get();
            }

            return $this->returnResponse($results->toArray());

        } catch (\Exception $exception) {
            return $this->returnResponse(null, ['message' => $exception->getMessage(), 'data' =>$exception->getTrace()], 500);
        }
    }

    /**
     * create a new resource
     *
     * @param $request
     * @param $endpoint
     * @return mixed
     */
    public function saveResource($request, $endpoint)
    {
        try {
            if(!array_key_exists($endpoint, $this->routes)) {
                return $this->returnResponse(null, ['message' => 'endpoint not fount'], 400);
            }

            if (array_key_exists('rules', $this->routes[$endpoint]) && array_key_exists('store', $this->routes[$endpoint]['rules'])) {
                $rules = $this->routes[$endpoint]['rules']['store'];
                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    $error = $validator->errors();
                    return $this->returnResponse(null, ['message' => 'Validation Error', 'data' => $error], 422);
                }
            }

            $input = $request->all();
            if (!array_key_exists('model', $this->routes[$endpoint])) {
                return $this->returnResponse(null, ['message' => 'model not specified'], 500);
            }
            $response = $this->routes[$endpoint]['model']::create($input);
            return $this->returnResponse($response);
        } catch (\Exception $exception) {
            return $this->returnResponse(null, ['message' => $exception->getMessage(), 'data' =>$exception->getTrace()], 500);
        }
    }

    /**
     * get a specified resource
     *
     * @param $endpoint
     * @param $id
     * @return mixed
     */
    public function fetchResource($endpoint, $id)
    {
        try {
            if(!array_key_exists($endpoint, $this->routes)) {
                return $this->returnResponse(null, ['message' => 'endpoint not fount'], 400);
            }

            if (!array_key_exists('model', $this->routes[$endpoint])) {
                return $this->returnResponse(null, ['message' => 'model not specified'], 500);
            }
            $builder = $this->routes[$endpoint]['model']::query();
            if (array_key_exists('relationships', $this->routes[$endpoint])) {
                if (count($this->routes[$endpoint]['relationships']) > 0) {
                    $builder = $builder->with($this->routes[$endpoint]['relationships']);
                }
            }
            $resource = $builder->find($id);
            if (empty($resource)) {
                return $this->returnResponse(null, ['message' => 'resource not fount'], 400);
            }

            return $this->returnResponse($resource, 'resource retrieved successfully.');
        } catch (\Exception $exception) {
            return $this->returnResponse(null, ['message' => $exception->getMessage(), 'data' =>$exception->getTrace()], 500);
        }
    }

    /**
     * update a specified resource
     *
     * @param $request
     * @param $endpoint
     * @param $id
     * @return mixed
     */
    public function updateResource($request, $endpoint, $id)
    {
        try {
            if(!array_key_exists($endpoint, $this->routes)) {
                return $this->returnResponse(null, ['message' => 'endpoint not fount'], 400);
            }

            if (array_key_exists('rules', $this->routes[$endpoint]) && array_key_exists('update', $this->routes[$endpoint]['rules'])) {
                $rules = $this->routes[$endpoint]['rules']['update'];
                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    $error = $validator->errors();
                    return $this->returnResponse(null, ['message' => 'Validation Error', 'data' => $error], 422);
                }
            }

            if (!array_key_exists('model', $this->routes[$endpoint])) {
                return $this->returnResponse(null, ['message' => 'model not specified'], 500);
            }
            $resource = $this->routes[$endpoint]['model']::find($id);
            if (empty($resource)) {
                return $this->returnResponse(null, ['message' => 'resource not fount'], 400);
            }
            $updated = $resource->fill($request->all())->save();

            if ($updated) {
                return $this->returnResponse($resource);
            }
            return $this->returnResponse(null, ['message' => 'error in updating resource', 'data' => []], 500);

        } catch (\Exception $exception) {
            return $this->returnResponse(null, ['message' => $exception->getMessage(), 'data' =>$exception->getTrace()], 500);
        }
    }

    /**
     * delete specified resource
     *
     * @param $endpoint
     * @param $id
     * @return mixed
     */
    public function deleteResource($endpoint, $id)
    {
        try {
            if(!array_key_exists($endpoint, $this->routes)) {
                return $this->returnResponse(null, ['message' => 'endpoint not fount'], 400);
            }

            if (!array_key_exists('model', $this->routes[$endpoint])) {
                return $this->returnResponse(null, ['message' => 'model not specified'], 500);
            }

            if ($this->routes[$endpoint]['model']::where('id', $id)->exists()) {
                $resource = $this->routes[$endpoint]['model']::find($id)->delete();

                return $this->returnResponse($resource);
            } else {
                return $this->returnResponse(null, ['message' => 'resource not found in database.', 'data' => []], 500);
            }
        } catch (\Exception $exception) {
            return $this->returnResponse(null, ['message' => $exception->getMessage(), 'data' =>$exception->getTrace()], 500);
        }
    }

    private function returnResponse($data, $error = [], $code = '')
    {
        $response['data'] = $data; $response['errors'] = null;
        if ($error) {
            $response['errors'] = $error;
        }
        if ($code) {
            $response['code'] = $code;
        }
        return $response;
    }

}
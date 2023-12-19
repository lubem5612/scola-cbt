<?php


namespace Transave\ScolaCbt\Actions\Resource;


use Carbon\Carbon;
use Transave\ScolaCbt\Helpers\ResponseHelper;

class SearchResource
{
    use ResponseHelper;

    private $request;
    private $route;
    private $relationships = [];
    private $model;
    private $routeConfig = [];
    private $queryBuilder;
    private $searchParam;
    private $perPage = 10;
    private $startAt = '';
    private $endAt = '';
    private $resources = [];

    public function __construct(array $request)
    {
        $this->request = $request;
        $this->routeConfig = config('endpoints.routes');
    }

    public function execute()
    {
        try {
            return $this
                ->validateAndSetDefaults()
                ->setModel()
                ->setModelRelationship()
                ->searchTerms()
                ->filterWithTimeStamps()
                ->filterWithOrder()
                ->getResources()
                ->sendSuccess($this->resources, 'resources returned');
        }catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }

    private function setModel()
    {
        abort_if(!array_key_exists('model', $this->route), 401, 'model not configured');
        $this->model = $this->route['model'];
        $this->queryBuilder = $this->model::query();
        return $this;
    }

    private function searchTerms()
    {
        switch ($this->request['endpoint'])
        {
            case "sessions": {
                $this->queryBuilder->where('name', 'like', "%$this->searchParam%")
                    ->orWhere('is_active', 'like', "%$this->searchParam%");
                break;
            }
            case "faculties": {
                $this->queryBuilder->where('name', 'like', "%$this->searchParam%");
                break;
            }
            case "courses": {
                $search = $this->searchParam;
                $item = request()->query('department_id');
                if (isset($item)) {
                    $this->queryBuilder->where('department_id', $item);
                }
                $this->queryBuilder->where(function ($query) use($search) {
                    $query->where('name', 'like', "%$search%")
                        ->orWhere('code', 'like', "%$search%")
                        ->orWhere('credit_load', 'like', "%$search%")
                        ->orWhereHas('department', function ($q) use($search) {
                            $q->where('name', 'like', "%$search%")
                                ->orWhere('id', 'like', "%$search%")
                                ->orWhereHas('faculty', function ($x) use($search) {
                                    $x->where('name', 'like', "%$search%");
                                });
                        });
                });
                break;
            }
            case "departments": {
                $search = $this->searchParam;
                $item = request()->query('faculty_id');
                if (isset($item)) {
                    $this->queryBuilder->where('faculty_id', $item);
                }
                $this->queryBuilder
                    ->where('name', 'like', "%{$search}%")
                    ->orWhereHas('faculty', function ($query) use ($search) {
                        $query->where('name', 'like', "%$search%");
                    });
                break;
            }
            case "question-options": {
                $search = $this->searchParam;
                $question = request()->query('question_id');
                if (isset($question)) {
                    $this->queryBuilder->where('question_id', $question);
                }
                $this->queryBuilder
                    ->where('is_correct_score', 'like', "%{$search}%")
                    ->orWhereHas('question', function ($query) use ($search) {
                        $query->Where('question_type', 'like', "%$search%")
                            ->orWhere('score_obtainable', 'like', "%$search%");
                    });
                break;
            }
            case "student-exams": {
                $search = $this->searchParam;
                $this->queryBuilder->where(function ($query) use ($search) {
                    $query
                        ->orWhereHas('student', function ($query1) use ($search) {
                            $query1->where('registration_number', 'like', "%$search%")
                                ->orWhere('current_level', 'like', "%$search%");
                        })
                        ->orWhereHas('exam', function ($query2) use ($search) {
                            $query2->where('level', 'like', "%$search%")
                                ->orWhere('semester', 'like', "%$search%")
                                ->orWhere('exam_mode', 'like', "%$search%")
                                ->orWhere('exam_name', 'like', "%$search%");
                        });
                });
                break;
            }
            case "exam-settings": {

                break;
            }
            default:
                return $this;
        }
        return $this;
    }

    private function filterWithTimeStamps()
    {
        if ($this->startAt!="null" || $this->endAt!="null" || $this->startAt!=null || $this->endAt!=null) {
            if (isset($this->startAt) && isset($this->endAt)) {
                $start = Carbon::parse($this->startAt);
                $end = Carbon::parse($this->endAt);
                $this->queryBuilder = $this->queryBuilder
                    ->whereBetween('created_at', [$start, $end]);
            }
        }
        return $this;
    }

    private function getResources()
    {
        if (isset($this->perPage)) {
            $this->resources = $this->queryBuilder->paginate($this->perPage);
        }else
            $this->resources = $this->queryBuilder->get();
        return $this;
    }

    private function filterWithOrder()
    {
        if (array_key_exists('order', $this->route)) {
            if (array_key_exists('column', $this->route['order']) && array_key_exists('pattern', $this->route['order'])) {
                $this->queryBuilder = $this->queryBuilder->orderBy($this->route['order']['column'], $this->route['order']['pattern']);
            }
        }else {
            $this->queryBuilder = $this->queryBuilder->orderBy('created_at', 'desc');
        }
        return $this;
    }

    private function setModelRelationship()
    {
        if (array_key_exists('relationships', $this->route) && count($this->route['relationships']) > 0) {
            $this->relationships = $this->route['relationships'];
            $this->queryBuilder = $this->queryBuilder->with($this->relationships);
        }
        return $this;
    }

    private function validateAndSetDefaults()
    {
        abort_if(!array_key_exists($this->request['endpoint'], $this->routeConfig), 401, 'endpoint not found');
        $this->route = $this->routeConfig[$this->request['endpoint']];
        $this->startAt = request()->query('start');
        $this->endAt = request()->query('end');
        $this->searchParam = request()->query("search");
        $this->perPage = request()->query("per_page");
        return $this;
    }
}

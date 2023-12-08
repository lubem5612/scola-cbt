<?php

namespace Transave\ScolaCbt\Actions\Exam;

use Transave\ScolaCbt\Helpers\ResponseHelper;
use Transave\ScolaCbt\Helpers\ValidationHelper;

class ExamLink
{
    use ValidationHelper, ResponseHelper;
    private string $exam_id;
    private string $url;

    public function __construct(string $exam_id)
    {
        $this->exam_id = $exam_id;
    }

    public function execute()
    {
        try {
            return $this
                ->createLink()
                ->sendSuccess($this->url, 'link created');
        } catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }

    private function createLink()
    {
        $routeName = 'cbt.exams.show';
        $this->url = route($routeName, ['id' => $this->exam_id]);

        return $this;
    }
}

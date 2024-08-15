<?php


namespace Transave\ScolaCbt\Actions\Option;


use Transave\ScolaCbt\Helpers\FileUploadHelper;
use Transave\ScolaCbt\Helpers\ResponseHelper;
use Transave\ScolaCbt\Helpers\ValidationHelper;
use Transave\ScolaCbt\Http\Models\Option;

class DeleteOption
{
    use ResponseHelper, ValidationHelper;

    private $request, $validatedData;
    private Option $option;

    public function __construct(array $request)
    {
        $this->request = $request;
    }

    public function execute()
    {
        try {
            return $this
                ->validateRequest()
                ->setQuestionOption()
                ->deleteFile()
                ->deleteQuestionOption();

        } catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }

    private function deleteQuestionOption()
    {
        $this->option->delete();
        return $this->sendSuccess(null, 'question option deleted successfully');
    }

    private function deleteFile(): self
    {
        if ($this->option->file) {
            FileUploadHelper::DeleteFile($this->option->file);
        }
        return $this;
    }

    private function setQuestionOption(): self
    {
        $this->option = Option::query()->find($this->validatedData['id']);
        return $this;
    }

    private function validateRequest(): self
    {
        $this->validatedData = $this->validate($this->request, [
            'id' => 'required|exists:cbt_options,id'
        ]);
        return $this;
    }
}
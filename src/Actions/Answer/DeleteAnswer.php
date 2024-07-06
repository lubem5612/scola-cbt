<?php


namespace Transave\ScolaCbt\Actions\Answer;


use Transave\ScolaCbt\Helpers\FileUploadHelper;
use Transave\ScolaCbt\Helpers\ResponseHelper;
use Transave\ScolaCbt\Helpers\ValidationHelper;
use Transave\ScolaCbt\Http\Models\Answer;

class DeleteAnswer
{
    use ResponseHelper, ValidationHelper;
    private $request;
    private Answer $answer;

    /**
     * DeleteAnswer constructor.
     * @param array $request
     */
    public function __construct(array $request)
    {
        $this->request = $request;
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function execute()
    {
        try {
            return $this
                ->validateRequest()
                ->setAnswer()
                ->deleteFile()
                ->deleteAnswer();
        }catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }

    /**
     * @return \Illuminate\Http\Response
     */
    private function deleteAnswer()
    {
        $this->answer->delete();
        return $this->sendSuccess(null, 'answer deleted successfully');
    }

    /**
     * @return $this
     */
    private function deleteFile() : self
    {
        if ($this->answer->file) {
            FileUploadHelper::DeleteFile($this->answer->file);
        }
        return $this;
    }

    /**
     * @return $this
     */
    private function setAnswer() :self
    {
        $this->answer = Answer::query()->find($this->request['id']);
        return  $this;
    }

    /**
     * @return $this
     * @throws \Illuminate\Validation\ValidationException
     */
    private function validateRequest() : self
    {
        $this->validate($this->request, [
            'id' => 'required|exists:cbt_answers,id'
        ]);
        return $this;
    }

}
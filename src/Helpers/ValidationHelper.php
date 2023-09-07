<?php


namespace Transave\ScolaCbt\Helpers;





use Illuminate\Support\Facades\Validator;

trait ValidationHelper
{
    public $validator;

    /**
     * @param array $input
     * @param array $rules
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validate(array $input, array $rules)
    {
        $this->validator = Validator::make($input, $rules);
        if ($this->validator->passes()) {
            return $this->validator->validated();
        }
        abort(422, response()->json($this->validator->errors())->getContent());
    }

}
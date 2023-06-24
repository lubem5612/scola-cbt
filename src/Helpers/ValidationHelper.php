<?php


namespace Transave\ScolaCbt\Helpers;





use Illuminate\Support\Facades\Validator;

trait ValidationHelper
{
    public $validator;
    /**
     * @param array $input
     * @param array $rules
     */
    protected function validate(array $input, array $rules)
    {
        $this->validator = Validator::make($input, $rules);
        abort_if($this->validator->fails(), 422, response()->json($this->validator->errors())->getContent());
    }

}
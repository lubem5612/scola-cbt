<?php


namespace Transave\ScolaCbt\Helpers;





use Illuminate\Support\Facades\Validator;

trait ValidationHelper
{
    /**
     * @param array $input
     * @param array $rules
     */
    protected function validate(array $input, array $rules)
    {
        $validator = Validator::make($input, $rules);
        abort_if($validator->fails(), 422, response()->json($validator->errors())->getContent());
    }

}
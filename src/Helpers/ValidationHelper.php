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
        if ($validator->fails()) {
            abort(response()->json(['message' => 'validation failed', 'errors' => $validator->errors()], 422));
        }else {
            return;
        }
    }
}
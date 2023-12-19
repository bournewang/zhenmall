<?php
namespace App\Helpers;

// use App\Exceptions\Exception;
use Illuminate\Support\Facades\Validator;

class ValidatorHelper
{
    static public function validate($rules, $data)
    {
        $validator = Validator::make($data, $rules);
        if ($validator->fails()){
            $messages = [];
            foreach ($validator->errors()->getMessages() as $field => $msgs) {
                $messages = array_merge($messages, $msgs);
            }
            $msg = implode(' ', $messages);
            // throw new \Exception( $msg );
            return $msg;
        }
        return null;
    }
}

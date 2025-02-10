<?php


namespace App\Validator;


use Illuminate\Validation\Validator;

class CustomValidationRules
{

    public function rejectDictionaryWords($attribute, $value, $parameters, $validator)
    {
        $stripped_special_chars = preg_replace('/\W|[0-9]/', '', strtolower($value));
        $password_splits = preg_split('/(\W|[0-9])+/', strtolower($value));
        $password_splits[] = $stripped_special_chars;

        $path = realpath(__DIR__ . '/../../resources/files/english-words.txt');
        $words = explode("\n", file_get_contents($path));

        foreach ($words as $word){
            $word = trim(strtolower($word));
            if (array_search($word,$password_splits)!==false){
                return false;
            }
        }

        return true;
    }



    public function checkSafeInput($attribute, $value, $parameters,Validator $validator)
    {
        $max = $parameters[0]??100;
        if (strlen($value) > $max){
            $validator->setCustomMessages(['safe'=>":attribute cannot be more than $max characters long"]);
            return false;
        }elseif (preg_replace ('/<[^>]*>/', '', $value)!=$value){
            $validator->setCustomMessages(['safe'=>":attribute should not contain HTML tags"]);
            return false;
        }
        return true;
    }

}

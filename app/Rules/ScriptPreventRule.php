<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ScriptPreventRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if(str_contains(strtolower($value),'javascript')){
            return false;
        }else if(str_contains(strtolower($value),'script')){
            return false;
        }else if(str_contains(strtolower($value),'createelement')){
            return false;
        }else if(str_contains(strtolower($value),'appendchild')){
            return false;
        }else{
            return true;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Please provide valid information';
    }
}

<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;

class UniqueInRequest implements Rule
{
    protected $request;
    protected $attributeName;

    public function __construct(Request $request, $attributeName)
    {
        $this->request = $request;
        $this->attributeName = $attributeName;
    }

    public function passes($attribute, $value)
    {
        $values = $this->request->input($this->attributeName);
        $count = 0;

        foreach ($values as $val) {
            if ($val === $value) {
                $count++;
            }
            if ($count > 1) {
                return false;
            }
        }

        return true;
    }

    public function message()
    {
        return 'The :attribute value is not unique within the request.';
    }
}

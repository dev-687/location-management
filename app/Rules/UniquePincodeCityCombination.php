<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class UniquePincodeCityCombination implements Rule
{

    public function passes($attribute, $value)
    {
        $cities = request()->input('city');
        $pincodes = request()->input('pincode');

        if (!is_array($cities) || !is_array($pincodes) || count($cities) !== count($pincodes)) {
            return true; // Invalid input, no duplicates to check
        }

        $combinations = [];

        for ($i = 0; $i < count($cities); $i++) {
            $combination = $cities[$i] . '-' . $pincodes[$i];

            if (in_array($combination, $combinations)) {
                return false; // Duplicate found
            }

            $combinations[] = $combination;
        }

        return true;
    }

    public function message()
    {
        return 'City-pincode combinations must be unique within the request.';
    }
}

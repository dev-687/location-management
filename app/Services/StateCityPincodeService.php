<?php

namespace App\Services;

use App\Models\StateCityPincodeRecord;
use App\Models\StateCityPincodeHistory;

class StateCityPincodeService
{
    public function updateRecord(StateCityPincodeRecord $record, array $data)
    {
        StateCityPincodeHistory::create([
            'state_id' => $record->state_id,
            'city_id' => $record->city_id,
            'pincode' => $record->pincode,
            'user_id' => $record->user_id,
            'state_city_pincode_record_id' => $record->id,
        ]);

        $record->update($data);

        return $record;
    }
}

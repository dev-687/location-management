<?php

namespace Database\Seeders;

use App\Models\State;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = [
            ['name' => 'Visakhapatnam', 'state' => 'Andhra Pradesh'],
            ['name' => 'Vijayawada', 'state' => 'Andhra Pradesh'],
            ['name' => 'Itanagar', 'state' => 'Arunachal Pradesh'],
            ['name' => 'Guwahati', 'state' => 'Assam'],
            ['name' => 'Dispur', 'state' => 'Assam'],
            ['name' => 'Patna', 'state' => 'Bihar'],
            ['name' => 'Raipur', 'state' => 'Chhattisgarh'],
            ['name' => 'Panaji', 'state' => 'Goa'],
            ['name' => 'Ahmedabad', 'state' => 'Gujarat'],
            ['name' => 'Surat', 'state' => 'Gujarat'],
            ['name' => 'Chandigarh', 'state' => 'Haryana'],
            ['name' => 'Shimla', 'state' => 'Himachal Pradesh'],
            ['name' => 'Ranchi', 'state' => 'Jharkhand'],
            ['name' => 'Bengaluru', 'state' => 'Karnataka'],
            ['name' => 'Mysuru', 'state' => 'Karnataka'],
            ['name' => 'Thiruvananthapuram', 'state' => 'Kerala'],
            ['name' => 'Kochi', 'state' => 'Kerala'],
            ['name' => 'Bhopal', 'state' => 'Madhya Pradesh'],
            ['name' => 'Indore', 'state' => 'Madhya Pradesh'],
            ['name' => 'Mumbai', 'state' => 'Maharashtra'],
            ['name' => 'Pune', 'state' => 'Maharashtra'],
            ['name' => 'Imphal', 'state' => 'Manipur'],
            ['name' => 'Shillong', 'state' => 'Meghalaya'],
            ['name' => 'Aizawl', 'state' => 'Mizoram'],
            ['name' => 'Kohima', 'state' => 'Nagaland'],
            ['name' => 'Bhubaneswar', 'state' => 'Odisha'],
            ['name' => 'Chandigarh', 'state' => 'Punjab'],
            ['name' => 'Jaipur', 'state' => 'Rajasthan'],
            ['name' => 'Gangtok', 'state' => 'Sikkim'],
            ['name' => 'Chennai', 'state' => 'Tamil Nadu'],
            ['name' => 'Hyderabad', 'state' => 'Telangana'],
            ['name' => 'Agartala', 'state' => 'Tripura'],
            ['name' => 'Lucknow', 'state' => 'Uttar Pradesh'],
            ['name' => 'Varanasi', 'state' => 'Uttar Pradesh'],
            ['name' => 'Dehradun', 'state' => 'Uttarakhand'],
            ['name' => 'Kolkata', 'state' => 'West Bengal'],
            ['name' => 'Port Blair', 'state' => 'Andaman and Nicobar Islands'],
            ['name' => 'Chandigarh', 'state' => 'Chandigarh'],
            ['name' => 'Daman', 'state' => 'Dadra and Nagar Haveli and Daman and Diu'],
            ['name' => 'Delhi', 'state' => 'Delhi'],
            ['name' => 'Srinagar', 'state' => 'Jammu and Kashmir'],
            ['name' => 'Leh', 'state' => 'Ladakh'],
            ['name' => 'Kavaratti', 'state' => 'Lakshadweep'],
            ['name' => 'Puducherry', 'state' => 'Puducherry'],
        ];

        foreach ($cities as $city) {
            $state = State::where('name', $city['state'])->first();

            if ($state) {
                DB::table('cities')->insert([
                    'name' => $city['name'],
                    'state_id' => $state->id,
                ]);
            }
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\State;
use App\Models\StateCityPincodeRecord;
use App\Rules\UniqueCities;
use App\Rules\UniqueInRequest;
use App\Rules\UniquePincodeCityCombination;
use App\Services\StateCityPincodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StateCityPincodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $locations = StateCityPincodeRecord::with(['state', 'city'])->get();
        $states = State::all();
        $cities = City::all();
        return view('stored_location', compact('locations', 'states','cities'));
    }

    public function getCities($stateId,$location_id=null)
    {
        if($location_id){
            $storedCityIds = StateCityPincodeRecord::where('id','!=',$location_id)->pluck('city_id')->toArray();
        }else{
            $storedCityIds = StateCityPincodeRecord::pluck('city_id')->toArray();
        }
        $cities = City::where('state_id', $stateId)->whereNotIn('id', $storedCityIds)->get();
        return response()->json($cities);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $states = State::all();
        return view('state_city_pincode_form', compact('states'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'state.*' => 'required|exists:states,id',
            'city.*' => 'required|exists:cities,id',
            'pincode.*' => [
                'required',
                'regex:/^[1-9][0-9]{5}$/',
                'unique:state_city_pincode_records,pincode',
                new UniqueInRequest($request, 'pincode'),
            ],
            'city' => [new UniqueCities, new UniquePincodeCityCombination],
        ], [
            'pincode.*.regex' => 'Pincode must be 6 digits.',
            'pincode.*.unique' => 'The pincode has already been taken.',
            'pincode.*.unique_in_request' => 'Pincode must be unique within the request.',
        ]);

        try {
            DB::beginTransaction();

            foreach ($request->state as $key => $stateId) {

                StateCityPincodeRecord::create([
                    'state_id' => $stateId,
                    'city_id' => $request->city[$key],
                    'user_id' => auth()->user()->id,
                    'pincode' => $request->pincode[$key],
                ]);
            }

            DB::commit();

            return response()->json(['message' => 'Data saved successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,StateCityPincodeService $service)
    {
        $request->validate([
            'state.*' => 'required|exists:states,id',
            'city.*' => 'required|exists:cities,id',
            'pincode.*' => [
                'required',
                'regex:/^[1-9][0-9]{5}$/',

                new UniqueInRequest($request, 'pincode'),
            ],
            'city' => [new UniqueCities, new UniquePincodeCityCombination],
        ], [
            'pincode.*.regex' => 'Pincode must be 6 digits.',
            'pincode.*.unique_in_request' => 'Pincode must be unique within the request.',
        ]);

        try {
            DB::beginTransaction();

            foreach ($request->location_id as $key => $locationId) {
                $request->validate([
                    'pincode.' . $key => 'unique:state_city_pincode_records,pincode,' . $locationId,
                ]);
                $record = StateCityPincodeRecord::findOrFail($locationId);
                $data=[
                    'state_id' => $request->state[$key],
                    'city_id' => $request->city[$key],
                    'pincode' => $request->pincode[$key],
                    'user_id' => auth()->user()->id,
                ];
                // dd($record->pincode);
                $service->updateRecord($record, $data);

            }

            DB::commit();

            return response()->json(['message' => 'Locations updated successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


}

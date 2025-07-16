<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\State;
use App\Models\City;

class LocationController extends BaseController
{
    public function countries()
    {
        $countries = Country::where('is_active', true)
            ->select('id', 'name', 'code', 'phone_code')
            ->orderBy('name')
            ->get();

        return $this->successResponse($countries, 'Countries retrieved successfully');
    }

    public function states(Request $request)
    {
        $countryId = $request->query('country_id');

        if (!$countryId) {
            return $this->errorResponse('Country ID is required', 400);
        }

        $states = State::where('country_id', $countryId)
            ->where('is_active', true)
            ->select('id', 'name', 'code')
            ->orderBy('name')
            ->get();

        return $this->successResponse($states, 'States retrieved successfully');
    }

    public function cities(Request $request)
    {
        $countryId = $request->query('country_id');
        $stateId = $request->query('state_id');

        $query = City::where('is_active', true);

        if ($countryId) {
            $query->where('country_id', $countryId);
        }

        if ($stateId) {
            $query->where('state_id', $stateId);
        }

        $cities = $query->select('id', 'name', 'latitude', 'longitude')
            ->orderBy('name')
            ->get();

        return $this->successResponse($cities, 'Cities retrieved successfully');
    }

    public function searchCity(Request $request)
    {
        $q = $request->query('q', '');
        $countryId = $request->query('country_id');
        $stateId = $request->query('state_id');

        if (strlen($q) < 2) {
            return $this->successResponse([], 'No cities found');
        }

        $query = City::where('is_active', true)
            ->where('name', 'LIKE', "%{$q}%");

        if ($countryId) {
            $query->where('country_id', $countryId);
        }

        if ($stateId) {
            $query->where('state_id', $stateId);
        }

        $cities = $query->select('id', 'name', 'latitude', 'longitude')
            ->with(['state:id,name', 'country:id,name'])
            ->orderBy('name')
            ->limit(20)
            ->get();

        return $this->successResponse($cities, 'Cities search completed successfully');
    }
}

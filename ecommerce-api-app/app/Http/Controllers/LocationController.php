<?php

namespace App\Http\Controllers;

use App\Models\Locations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocationController extends Controller
{



    public function store(Request $request)
    {
        $validated = $request->validate([
            'street' => 'required',
            'building' => 'required',
            'area' => 'required',
        ]);
        Locations::create([
            'street' => $request->street,
            'building' => $request->building,
            'area' => $request->area,
            'user_id' => Auth::id(),
        ]);

        return response()->json('Location created successfully', 201);
    }

    public function updateLocation($id, Request $request)
    {


        $request->validate([
            'street' => 'required',
            'building' => 'required',
            'area' => 'required',
        ]);
        $location  = Locations::find($id);
        if ($location) {
            $location->street = $request->street;
            $location->building = $request->building;
            $location->area = $request->area;
            $location->save();
            return response()->json('Location updated successfully', 200);
        } else {
            return response()->json('Location Id not found', 400);
        }
    }


    public function destroy($id)
    {
        $location  = Locations::findOrFail($id);
        if ($location) {
            $location->delete();
            return response()->json('Location deleted successfully', 200);
        } else {
            return response()->json('Location Id not found', 400);
        }
    }
}

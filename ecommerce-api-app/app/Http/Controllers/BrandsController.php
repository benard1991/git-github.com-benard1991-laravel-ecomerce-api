<?php

namespace App\Http\Controllers;

use App\Models\Brands;
use Exception;
use Illuminate\Http\Request;

class BrandsController extends Controller
{


    public function index()
    {
        $brands = Brands::paginate(10);
        return response()->json($brands, 200);
    }

    public function show($id)
    {
        $brand = Brands::findOrFail($id);
        if ($brand) {
            return response()->json($brand, 200);
        } else {
            return response()->json('Brand Id not found', 400);
        }
    }

    public function store(Request $request)
    {

        try {
           $request->validate([
                'name' => 'required|unique:brands,name',
            ]);
            $brand = new Brands();
            $brand->name = $request->name;
            $brand->save();
            return response()->json('Brand added successfully', 201);
        } catch (Exception $e) {
            return response()->json($e, 500);
        }
    }

    public function updateBrand($id, Request $request)
    {
        try {
           $request->validate([
                'name' => 'required|unique:brands,name',
            ]);
            $brand = Brands::where('id', $id);
            $brand->update(['name' => $request->name]);
            return response()->json('Brand updated successfully', 200);
        } catch (Exception $e) {
            return response()->json($e, 500);
        }
    }

    public function deleteBrand($id)
    {
        try {
            $brand = Brands::find($id);
            if ($brand) {
                $brand->delete();
                return response()->json('Brand deleted successfully', 200);
            } else {
                return response()->json('Brand ID not found', 400);
            }
        } catch (Exception $e) {
            return response()->json($e, 500);
        }
    }
}

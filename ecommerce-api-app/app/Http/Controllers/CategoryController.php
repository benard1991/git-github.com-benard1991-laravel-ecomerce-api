<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Categories;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class CategoryController extends Controller
{


    public function index()
    {
        $categories = Categories::paginate(10);
        return response()->json($categories, 200);
    }

    public function show($id)
    {
        $category = Categories::findOrFail($id);
        if ($category) {
            return response()->json($category, 200);
        } else {
            return response()->json('Category Id not found', 400);
        }
    }

    public function store(Request $request)
    {
    
        try {
            $request->validate([
                'name' => 'required|unique:categorys,name',
                'image' => 'required'
            ]);
            $category = new Categories();
            if ($request->hasFile('image')) {
                $path = 'asset/uploads/category/' . $request->image;
                if (File::delete($path)) {
                    File::delete($path);
                }
                $file = $request->file('image');
                $ext = $file->getClientOriginalExtension();
                $filename = time() . rand(999, 9999) . '-' . $ext;

                try {
                    $file->move('asset/uploads/category', $filename);
                } catch (FileException $e) {
                    return response()->json($e, 500);
                }
            }
            $category->image = $filename;
            $category->name = $request->name;
            $category->save();
            return response()->json('Category added successfully', 201);
        } catch (Exception $e) {
            return response()->json($e, 500);
        }
    }

    public function updateCategory($id, Request $request)
    {
        try {

            $request->validate([
                'nam' => 'required|unique:categorys,name',
                'image' => 'required'

            ]);

            $category = Categories::find($id);
      ;
            if ($request->hasFile('image')) {
                $path = 'asset/uploads/category/' . $category->image;
                if (File::exists($path)) {
                    File::delete($path);
                }
                $file = $request->file('image');
                $ext = $file->getClientOriginalExtension();
                echo   $ext;
                exit;
                $filename = time() . rand(999, 9999) . '-' . $ext;

                try {
                    $file->move('asset/uploads/category', $filename);
                } catch (FileException $e) {
                    dd($e);
                }
                $category->image = $filename;
            }
            $category->name = $request->name;
            $category->update();
            return response()->json('Category updated successfully', 200);
        } catch (FileException $e) {
            return response()->json($e, 500);
        }
    }

    public function deleteCategory($id)
    {


        try {
            $category = Categories::findOrFail($id);
            if ($category) {
                $category->delete();
                return response()->json('Category deleted successfully', 200);
            } else {
                return response()->json('Category not found', 400);
            }
        } catch (Exception $e) {
            return response()->json($e, 500);
        }
    }
}

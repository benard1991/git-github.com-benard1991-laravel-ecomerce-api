<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ProductController extends Controller
{

    public function index()
    {
        $products = Products::paginate(10);
        if ($products) {
            return response()->json(['data' => $products, 'status' => 200, 'message' => 'success']);
        } else {
            return response()->json("No product available", 400);
        }
    }


    public function show($id)
    {
        $product = Products::find($id);
        if ($product) {
            return response()->json($product, 200);
        } else {
            return response()->json("Product ID not found", 400);
        }
    }

    public function store(Request $request)
    {

        Validator::make($request->all(), [
            'name' => 'required',
            'price' => 'required|numeric',
            'discount' => 'required|numeric',
            'amount' => 'required|numeric',
            'image' => 'required|image',
            'category_id' => 'required|numeric',
            'brand_id' => 'required|numeric',
        ]);

        $product = new Products();
        $product->name = $request->name;
        $product->price = $request->price;
        $product->discount = $request->discount;
        $product->amount = $request->amount;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;

        if ($request->hasFile('image')) {
            $path = 'asset/uploads/product/' . $product->image;
            if (File::delete($path)) {
                File::delete($path);
            }
            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $filename = time() . rand(999, 9999) . '-' . $ext;

            try {
                $file->move('asset/uploads/product', $filename);
            } catch (FileException $e) {
                dd($e);
            }
            $product->image = $filename;
        }
        $product->save();
        return response()->json("Product added successfully", 201);
    }

    public  function updateProduct($id, Request $request)
    {

        Validator::make($request->all(), [

            'name' => 'required',
            'price' => 'required|numeric',
            'discount' => 'numeric',
            'amount' => 'required|numeric',
            'image' => 'required|image',
            'category_id' => 'required|numeric',
            'brand_id' => 'required|numeric',
        ]);

        $product = Products::findOrFail($id);
        if ($product) {
            $product->name = $request->name;
            $product->price = $request->price;
            $product->discount = $request->discount;
            $product->amount = $request->amount;
            $product->category_id = $request->category_id;
            $product->brand_id = $request->brand_id;

            if ($request->hasFile('image')) {
                $path = 'asset/uploads/product/' . $product->image;
                if (File::exists($path)) {
                    File::delete($path);
                }
                $file = $request->file('image');
                $ext = $file->getClientOriginalExtension();
                $filename = time() . rand(999, 9999) . '-' . $ext;

                try {
                    $file->move('asset/uploads/product', $filename);
                } catch (FileException $e) {
                    dd($e);
                }
                $product->image = $filename;
            }
            $product->update();
            return response()->json("Product updated successfully", 200);
        } else {
            return response()->json("Product not  found", 400);
        }
    }


    public function destory($id)
    {
        $product = Products::findOrFail($id);
        if ($product) {
            $product->delete();
            return response()->json("Product deleted successfully", 200);
        } else {
            return response()->json("Product not  found", 400);
        }
    }
}

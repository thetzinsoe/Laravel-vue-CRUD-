<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // return Product::orderBy('id','desc')->paginate(5);
        return Product::when(request('search'), function ($query) {
            $query->where('name', 'like', '%' . request('search') . '%');
        })->orderBy('id', 'desc')->paginate(5);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'price' => 'required|numeric',
        ], [
            'name.required' => 'နာမည်ထည့်ရန်လိုပါသည်။',
            'name.string' => 'နာမည်သည်စာသားဖြစ်ရမည်။',
            'price.required' => 'စျေးနှုန်းထည့်ရန်လိုပါသည်။',
            'price.numeric' => 'စျေးနှုန်းသည် ဂဏန်းဖြစ်ရမည်။',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
        $product = Product::create($request->only('name', 'price'));
        return $product;
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return $product;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'price' => 'required|numeric',
        ], [
            'name.required' => 'နာမည်ထည့်ရန်လိုပါသည်။',
            'name.string' => 'နာမည်သည်စာသားဖြစ်ရမည်။',
            'price.required' => 'စျေးနှုန်းထည့်ရန်လိုပါသည်။',
            'price.numeric' => 'စျေးနှုန်းသည် ဂဏန်းဖြစ်ရမည်။',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
        return $product->update($request->only('name', 'price'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        return $product->delete();
    }
}

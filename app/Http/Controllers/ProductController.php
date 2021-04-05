<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products =Product::all();

        return response()->json(  $products );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'price' => 'required|integer'
        ]);

        $product = new Product();
        $product->name = $request->name;
        $product->price = $request->price;

        if (auth('api')->user()->products()->save($product))
            return response()->json(
                 [
                 'success' => true,
                 'data' => $product->toArray()
             ]
            );
        else
            return response()->json(
                     [
                     'success' => false,
                     'message' => 'Product could not be added'
                 ]
                , 500);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {

        return response()->json( $product , 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $updated = $product->fill($request->all())->save();

        if ($updated)
            return response()->json(
                 [
                 'success' => true
             ]
            );
        else
            return response()->json(
                     [
                     'success' => false,
                     'message' => 'Product could not be updated'
                 ]
                , 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        if (!$product) {
            return response()->json(
                     [
                     'success' => false,
                     'message' => 'Product with id ' . $product->id . ' not found'
                 ]
                , 400);
        }

        if ($product->delete()) {
            return response()->json(
                 [
                 'success' => true
             ]
            );
        } else {
            return response()->json(
                     [
                     'success' => false,
                     'message' => 'Product could not be deleted'
                 ]
                , 500);
        }
    }
}

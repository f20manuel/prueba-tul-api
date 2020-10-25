<?php

namespace App\Http\Controllers\Api;

use App\Product;
use App\CartProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Exception;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->has("search") && $request->search != '') {
            $search = $request->search;
            $products = Product::where("name", "LIKE", "%{$search}%")
                ->orWhere("SKU", "LIKE", "%{$search}%")
                ->with('carts')
                ->get();

                foreach($products as $product) {
                    if ($product->carts->count() > 0) {
                        foreach($product->carts as $cart) {
                            $cart->quantity = CartProduct::where('cart_id', $cart->id)->first()->quantity;
                        }
                    }
                }

            return response()->json([
                "status" => "success",
                "data" => $products,
            ], 200);
        }

        $products = Product::with('carts')->get();

        foreach($products as $product) {
            if ($product->carts->count() > 0) {
                foreach($product->carts as $cart) {
                    $cart->quantity = CartProduct::where('cart_id', $cart->id)->first()->quantity;
                }
            }
        }

        return response()->json([
            "status" => "success",
            "data" => $products,
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'name' => 'required', 
            'SKU' => 'required|unique:products',
        ]);
        
        if ($validator->fails()) { 
            return response()->json([
                "status" => "error",
                "message" => $validator->errors(),
            ], 400);
        }

        try {
            $product = Product::create($request->all());

            return response()->json([
                "status" => "success",
                "message" => "Producto creado con éxtio",
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "status" => "error",
                "message" => $e,
            ], 400);
        }
    }

    public function list(Request $request)
    {
        if ($request->has("search") && $request->search != '') {
            $search = $request->search;
            $products = Product::where("name", "LIKE", "%{$search}%")
                ->orWhere("SKU", "LIKE", "%{$search}%")
                ->get();
            return response()->json([
                "status" => "success",
                "data" => $products,
            ], 200);
        }

        return response()->json([
            "status" => "success",
            "data" => Product::all(),
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return response()->json([
            "status" => "success",
            "data" => $product,
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        return response()->json([
            "status" => "success",
            "data" => $product,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        try {
            $product->update($request->all());

            return response()->json([
                "status" => "success",
                "message" => "Producto actualizado con éxito!",
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "status" => "error",
                "message" => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        try {
            $product->delete();
            return response()->json([
                "status" => "success",
                "message" => $product,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "status" => "error",
                "message" => $product,
                //"message" => "Algio salio mal y no se pudo borrar le producto, intente luego.",
            ], 400);
        }
    }
}
<?php

namespace App\Http\Controllers\Api;

use App\Cart;
use App\Product;
use App\CartProduct;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $carts = Cart::whereStatus(false)
                     ->with("products")
                     ->get();

        foreach($carts as $cart) {
            $cart->quantity = CartProduct::where('cart_id', $cart->id)->first()->quantity;
        }

        return response()->json([
            "data" => $carts
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $cart = Cart::create([]);

            $cart->products()->attach($request->product);

            return response()->json([
                "status" => "success",
                "message" => "Producto añadido al carrito",
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "status" => "error",
                "message" => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cart $cart)
    {
        try {
            $cart->products()->detach($request->product);
            $cart->products()->attach([$request->product => ['quantity' => $request->quantity]]);
            return response()->json([
                "status" => "success",
                "message" => "Producto actualizado con éxito",
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "status" => "error",
                "message" => $e->getMessage(),
            ], 400);
        }
    }

    public function checkout() {
        try {
            $carts = Cart::whereStatus(false)
                         ->with("products")
                         ->get();
            foreach($carts as $cart) {
                $cart->update(["status" => true]);
                foreach($cart->products as $product) {
                    $cart->products()->detach($product->id);
                }
            }
            return response()->json([
                "status" => "success",
                "message" => "Pago realizado con éxito.",
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
     * @param  \App\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Cart $cart)
    {
        try {
            $cart->products()->detach($request->product);
            $cart->delete();
            return response()->json([
                "status" => "success",
                "message" => "Se quito el producto del carrito",
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "status" => "error",
                "message" => $e->getMessage(),
            ], 400);
        }
    }
}

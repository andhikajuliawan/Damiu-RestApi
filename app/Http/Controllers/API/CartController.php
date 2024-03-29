<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        // Perbaikan Tambah Keranjang agar tidak ada produk dobel

        $findCart = Cart::where([
            ['customer_id', '=', $request->customer_id],
            ['depo_id', '=', $request->depo_id],
            ['product_id', '=', $request->product_id]
        ])->get();
        if (count($findCart) != 0) {
            $cart = Cart::findOrFail($findCart[0]->id);
            $cart->product_amount = $findCart[0]->product_amount + $request->product_amount;
            $cart->product_price =  $findCart[0]->product_price + $request->product_price;
            if ($cart->save()) {
                return new CartResource($cart);
            }
        } else {
            $cart = new Cart;

            $cart->customer_id = $request->customer_id;
            $cart->depo_id = $request->depo_id;
            $cart->product_id = $request->product_id;
            $cart->product_amount = 1;
            $cart->product_price = $cart->product->product_price;

            if ($cart->save()) {
                return new CartResource($cart);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cart_query = Cart::with('product');
        $cart_where = $cart_query->where('depo_id', ($id));
        $cart = $cart_where->get();
        return response()->json([
            'cart' => $cart

        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $cart = Cart::findOrFail($id);
        $cart->product_amount = $request->product_amount;
        $cart->product_price = $request->product_price;
        if ($cart->save()) {
            return new CartResource($cart);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cart = Cart::findOrFail($id);
        if ($cart->delete()) {
            return new CartResource($cart);
        }
    }
}

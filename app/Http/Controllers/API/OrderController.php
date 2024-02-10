<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Cart;
use App\Models\Customer_Order;
use App\Models\Order_Detail;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrderController extends Controller
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
        $order = new Customer_Order;

        $huruf = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
        $kodePemesanan = strtoupper(substr(str_shuffle($huruf), 0, 10));

        $order->no_order = $kodePemesanan;
        $order->customer_id = $request->customer_id;
        $order->depo_id = $request->depo_id;
        $order->order_datetime = Carbon::now();
        $order->order_total_product = $request->order_total_product;
        $order->order_price = $request->order_price;
        $order->order_location = $request->order_location;
        $order->destination_X = $request->destination_X;
        $order->destination_Y = $request->destination_Y;
        $order->notes = $request->notes;
        $order->order_status = $request->order_status;

        // FITUR MENAMBAHKAN ORDER SEKALIGUS ORDER DETAIL SECARA BERSAMAAN
        $order->save();
        $getId = $order->id;
        $cart = Cart::where([['customer_id', '=', $request->customer_id], ['depo_id', '=', $request->depo_id]])->get();
        $totalCart = count($cart);

        for ($i = 0; $i < $totalCart; $i++) {
            $detail = new Order_Detail;

            $detail->product_id = $cart[$i]->product_id;
            $detail->order_id = $getId;
            $detail->order_amount = $cart[$i]->product_amount;
            $detail->order_price = $cart[$i]->product_price;
            $detail->save();

            // MENGHAPUS DATA DARI CHART
            $cart[$i]->delete();
        }
        return response()->json([
            'order' => $order
        ], 200);


        // if ($order->save()) {
        //     return new OrderResource($order);
        // }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $order = Customer_Order::findOrFail($id);
        $order->order_status = $request->order_status;
        $order->save();

        return response()->json([
            'order_update' => $order

        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function history_order($id)
    {
        // $history_order = Customer_Order::all();
        $history_order = Customer_Order::where('customer_id', $id)->get();

        return response()->json([
            'history_order' => $history_order
        ], 200);
    }

    public function history_order_depo($id)
    {
        // $history_order = Customer_Order::all();
        $history_order_depo = Customer_Order::where('depo_id', ($id))->get();

        return response()->json([
            'history_order_depo' => $history_order_depo


        ], 200);
    }
}

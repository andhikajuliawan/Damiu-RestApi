<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Customer_OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('customer_orders')->insert([
            [
                'no_order' => 'AA123',
                'customer_id' => 1,
                'depo_id' => 1,
                'order_datetime' => Carbon::now(),
                'order_total_product' => 1,
                'order_price' => 10000,
                'order_location' => 'Jl Ngagel Surabaya',
                'order_status' => 'Berhasil',
                'destination_X' => '-7.2919396',
                'destination_Y' => '112.747509',
                'notes' => 'pagar warna hitam',
                'order_finish_datetime' => Carbon::now(),
            ],
        ]);
    }
}

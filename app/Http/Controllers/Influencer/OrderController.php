<?php

namespace App\Http\Controllers\Influencer;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController
{
    public function store(Request $request) {
        $order = new Order();

        $order->first_name = $request->input('first_name');
        $order->first_name = $request->input('last_name');
    }
}

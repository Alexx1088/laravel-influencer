<?php

namespace App\Console\Commands;

use App\Jobs\AdminAdded;
use App\Jobs\OrderCompleted;
use App\Models\Order;
use Illuminate\Console\Command;

class FireEventCommand extends Command
{
       protected $signature = 'fire';


    public function handle()
    {
        $order = Order::find(1);

        $data = $order->toArray();
        $data['admin_total'] = $order->admin_total;
        $data['influencer_total'] = $order->influencer_total;

        OrderCompleted::dispatch($data);

     //   AdminAdded::dispatch('qq@qwe.ru');
    }
}

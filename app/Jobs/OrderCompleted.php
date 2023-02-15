<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Message;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class OrderCompleted implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

       /* \Mail::send('influencer.admin', [

        ], function (Message $message) use ($order) {
            $message->to('admin@adm.com');
            $message->subject('A new order has been completed!');
        });
        \Mail::send('influencer.influencer', ['order' => $order], function (Message $message) use ($order) {
            $message->to($order->influencer_email);
            $message->subject('A new order has been completed!');
        });*/

    }
}

<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Log\Logger;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class SendOrderConfirmation implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderPlaced $event): void
    {
        $order = $event->order;
        // Send order confirmation email to the admin
        try {
            DB::transaction(function () use ($order) {
                $order->update(['status' => 'notified']);
                $order->products->each(function ($product) {
                    $product->update(['stock' => $product->stock - $product->pivot->quantity]);
                });
            });
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            // notify admin with error !!
            logger()->error('Error sending order confirmation: '. $e->getMessage());
        }

    }
}

<?php

namespace App\Observers;
use App\Models\Cart;

class CartObserver
{
    //
    public function created(Cart $cart)
    {
        
    }
    
    public function updated(Cart $cart)
    {
        \Log::debug(__CLASS__.'->'.__FUNCTION__. " $cart->id");
        $cart->refresh();
        $dispatcher = $cart->getEventDispatcher();
        $cart->unsetEventDispatcher();
        
        $cart->update([
            'total_price' => $cart->goods->sum('pivot.subtotal'),
            'total_quantity' => $cart->goods->sum('pivot.quantity'),
        ]);
        // 
        $cart->setEventDispatcher($dispatcher);
    }
}

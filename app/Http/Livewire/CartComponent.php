<?php

namespace App\Http\Livewire;

use Gloudemans\Shoppingcart\Facades\Cart;
use Livewire\Component;

class CartComponent extends Component
{
    //Cart::get()
    // If you want to get an item from the cart using its rowId, you can simply call the get() method on the cart and pass it the rowId. 
    public function increaseQuantity($rowId){
        $product = Cart::instance('cart')->get($rowId);
        $qty = $product->qty +1;
        Cart::instance('cart')->update($rowId,$qty);
        // tambahan dari cart Icon dan  ttg event
        $this->emitTo('cart-icon-component','refreshComponent'); 
    }

    public function decreaseQuantity($rowId){
        $product = Cart::instance('cart')->get($rowId);
        $qty = $product->qty -1;
        Cart::instance('cart')->update($rowId,$qty);
        // tambahan dari cart Icon dan  ttg event
        $this->emitTo('cart-icon-component','refreshComponent'); 
    }

    
    // Cart::remove()
    // To remove an item for the cart, you'll again need the rowId. This rowId you simply pass to the remove() method and it will remove the item from the cart.
    public function destroy($rowId){
        Cart::instance('cart')->remove($rowId);
        // tambahan dari cart Icon dan  ttg event
        $this->emitTo('cart-icon-component','refreshComponent'); 
        session()->flash("success_message","Item berhasil dihapus");
    }

    // Cart::destroy()
    // If you want to completely remove the content of a cart, you can call the destroy method on the cart. This will remove all CartItems from the cart for the current cart instance.
    public function clearAll(){
        Cart::instance('cart')->destroy();
        // tambahan dari cart Icon dan  ttg event
        $this->emitTo('cart-icon-component','refreshComponent'); 
        session()->flash("success_message","Item berhasil dihapus");
    }

    public function render()
    {
        return view('livewire.cart-component');
    }
}

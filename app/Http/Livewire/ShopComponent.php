<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;
// agar livewire menggunakan paginasi
use Livewire\WithPagination;
use Gloudemans\Shoppingcart\Facades\Cart;


class ShopComponent extends Component
{
    use WithPagination;

    // buat fungsi store bawaanya hardevine
    public function store($product_id,$product_name,$product_price){
        // specify the $id, $name, quantity(1), $price of the product you'd like to add to the cart.
        // associate('\App\Models\Product'); => artinya dikaitakan dengan Model Product
        Cart::add($product_id,$product_name,1,$product_price)->associate('\App\Models\Product');
        // session()->flash =>digunakan  untuk membuat pesan notofikasi berhasil/gagal/error.Bersidat sekali pakai
        session()->flash('success_message','Item ditambahkan ke Kranjang');
        return redirect()->route('shop.cart');
    }
    public function render()
    {
        // menampilkan 9 produk perpage
        $products = Product::paginate(9);
        return view('livewire.shop-component',['products'=>$products]);
    }
}

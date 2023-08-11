<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Livewire\Component;

class DetailsComponent extends Component
{
    // ntuk menerima data terlebih dahulu kita buat property $slug
    public $slug;

    // function mount() untuk menerima parameter
    public function mount($slug){
        $this->slug =$slug;
    }

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
        // cara simple crud tanpa fungsi mount
        // $data = DataPenduduk::where('id', $id)->first();
        // return view('admin.show')->with('variabelview', $data);

        // 
        $product = Product::where('slug',$this->slug)->first();
        // membuat related produk jadi dinamis
        // semua produk yang memiliki categori id yang sama (total ada 6 category id)
        // limit(4) artinya max ditampilkan 4 produk
        $rproducts = Product::where('category_id',$product->category_id)->inRandomOrder()->limit(4)->get(); 
        // membuat new produk jadi dinamis
        // ->take(4) ditampilkan 4 produk(id 1,2,3,4)
        $nproducts = Product::latest()->take(4)->get();
        return view('livewire.details-component',['product'=>$product,'rproducts'=>$rproducts,'nproducts'=>$nproducts]);
    }
}

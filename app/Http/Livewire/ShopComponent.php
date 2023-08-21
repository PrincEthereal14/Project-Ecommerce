<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;
// agar livewire menggunakan paginasi
use Livewire\WithPagination;
use Gloudemans\Shoppingcart\Facades\Cart;


class ShopComponent extends Component
{
    use WithPagination;
    // 9 hanya sebagai awalan,setelah itu akan berubah sesuai paramater
    public $pageSize = 9;
    // 
    public $orderBy = "Default Sorting";
    //filter by price epsd 15 
    public $min_value = 0;
    public $max_value = 1000;

    // buat fungsi store bawaanya hardevine
    public function store($product_id, $product_name, $product_price)
    {
        // specify the $id, $name, quantity(1), $price of the product you'd like to add to the cart.
        // associate('\App\Models\Product'); => artinya dikaitakan dengan Model Product
        Cart::instance('cart')->add($product_id, $product_name, 1, $product_price)->associate('\App\Models\Product');
        // session()->flash =>digunakan  untuk membuat pesan notofikasi berhasil/gagal/error.Bersidat sekali pakai
        session()->flash('success_message', 'Item ditambahkan ke Kranjang');
        return redirect()->route('shop.cart');
    }

    // fungsi untung mengubah2 paginasi
    public function changePageSize($size)
    {
        // 9 = $size
        //  $this->pageSize <- size dari wireclick
        $this->pageSize = $size;
    }

    // fungsi untung mengubah2 paginasi
    public function changeOrderBy($order)
    {

        $this->orderBy = $order;
    }

    //fungsi add product to wl epsd 16
    public function addToWishlist($product_id, $product_name, $product_price)
    {
        Cart::instance('wishlist')->add($product_id, $product_name, 1, $product_price)->associate('\App\Models\Product');
        $this->emitTo('wishlist-icon-component', 'refreshComponent');
    }

    //fungsi remove product to wl epsd 16
    public function removeFromWishlist($product_id)
    {
        foreach (Cart::instance('wishlist')->content() as $witem) {
            if ($witem->id == $product_id) {
                Cart::instance('wishlist')->remove($witem->rowId);
                $this->emitTo('wishlist-icon-component', 'refreshComponent');
                return;
            }
        }
    }

    public function render()
    {
        if ($this->orderBy == 'Price: Low to High') {
            // asc = ascending desc =descending
            // The whereBetween method verifies that a column's value is between two values:
            // whereBetween('colom',[nilai min,nilai max])
            $products = Product::whereBetween('regular_price', [$this->min_value, $this->max_value])->orderBy('regular_price', 'ASC')->paginate($this->pageSize);
        } else if ($this->orderBy == 'Price: High to Low') {
            $products = Product::whereBetween('regular_price', [$this->min_value, $this->max_value])->orderBy('regular_price', 'DESC')->paginate($this->pageSize);
        } else if ($this->orderBy == 'Sort By Newness') {
            $products = Product::whereBetween('regular_price', [$this->min_value, $this->max_value])->orderBy('created_at', 'DESC')->paginate($this->pageSize);
        } else {
            // menampilkan 9 produk perpage
            // $products = Product::paginate(9) = $products = Product::paginate($this->pageSize);;
            $products = Product::whereBetween('regular_price', [$this->min_value, $this->max_value])->paginate($this->pageSize);
        }

        // tambahan agar categories dinamis
        $categories = Category::orderby('name', 'ASC')->get();
        return view('livewire.shop-component', ['products' => $products, 'categories' => $categories]);
    }
}

<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;
// agar livewire menggunakan paginasi
use Livewire\WithPagination;
use Gloudemans\Shoppingcart\Facades\Cart;


class SearchComponent extends Component
{
    use WithPagination;
    // 9 hanya sebagai awalan,setelah itu akan berubah sesuai paramater
    public $pageSize=9;
    // 
    public $orderBy= "Default Sorting";
    // sorting by categories
    public $slug;
    // sorting dari fitur search
    public $q;
    public $search_term;

    //
    public function mount(){
        $this->fill(request()->only('q'));
        $this->search_term = '%'.$this->q. '%';
    } 

    // buat fungsi store bawaanya hardevine
    public function store($product_id,$product_name,$product_price){
        // specify the $id, $name, quantity(1), $price of the product you'd like to add to the cart.
        // associate('\App\Models\Product'); => artinya dikaitakan dengan Model Product
        Cart::instance('cart')->add($product_id,$product_name,1,$product_price)->associate('\App\Models\Product');
        // session()->flash =>digunakan  untuk membuat pesan notofikasi berhasil/gagal/error.Bersidat sekali pakai
        session()->flash('success_message','Item ditambahkan ke Kranjang');
        return redirect()->route('shop.cart');
    }

    // fungsi untung mengubah2 paginasi
    public function changePageSize($size){
        // 9 = $size
        //  $this->pageSize <- size dari wireclick
        $this->pageSize =$size;
    }

    // fungsi untung mengubah2 paginasi
    public function changeOrderBy($order){
       
        $this->orderBy =$order;
    }

    // // fungsi sorting by categories
    // public function mount($slug){
    //     $this->slug = $slug;
    // }
    public function render()
    {
        // $category = Category::where('slug',$this->slug)->first();
        // $category_id = $category->id;
        // $category_name = $category->name;
        if($this->orderBy == 'Price: Low to High'){
            // asc = ascending desc =descending
            $products = Product::where('name','like',$this->search_term)->orderBy('regular_price','ASC')->paginate($this->pageSize);

        }else if($this->orderBy == 'Price: High to Low'){
            $products = Product::where('name','like',$this->search_term)->orderBy('regular_price','DESC')->paginate($this->pageSize);
        }

        else if($this->orderBy == 'Sort By Newness'){
            $products = Product::where('name','like',$this->search_term)->orderBy('created_at','DESC')->paginate($this->pageSize);
            
        }else{
            // menampilkan 9 produk perpage
        // $products = Product::paginate(9) = $products = Product::paginate($this->pageSize);;
        $products = Product::where('name','like',$this->search_term)->paginate($this->pageSize);
        }
        
        // tambahan agar categories dinamis
        $categories = Category::orderby('name','ASC')->get();
        return view('livewire.search-component',['products'=>$products,'categories'=>$categories]);
    }
}

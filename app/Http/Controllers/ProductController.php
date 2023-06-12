<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    function __construct()
    {
        $this->middleware('permission:المنتجات', ['only' => ['products', 'index']]);
        $this->middleware('permission:اضافة منتج', ['only' => ['products', 'store']]);
        $this->middleware('permission:تعديل منتج', ['only' => ['products', 'update']]);
        $this->middleware('permission:حذف منتج', ['only' => ['products', 'destroy']]);
    }

    public function index()
    {
        $categories = Category::all();
        $products = Product::all();
        return view('products.products', compact('categories', 'products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        $validated = $request->validated();

        Product::create([
            'Product_name' => $request->product_name,
            'category_id' => $request->category_id,
            'description' => $request->description,
        ]);
        session()->flash('Add', 'تم اضافة المنتج بنجاح ');
        return redirect('/products');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request)
    {
        $id = Category::where('category_name', $request->category_name)->first()->id;

        $products = Product::findOrFail($request->pro_id);

        // Retrieve the validated input data...
        $validated = $request->validated();


        $products->update([
            'Product_name' => $request->product_name,
            'category_name' => $request->category_name,
            'description' => $request->description,
            'category_id' => $id
        ]);

        session()->flash('edit', 'تم تعديل القسم بنجاج');
        return redirect('/products');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = $request->pro_id;
        Product::findOrFail($id)->delete();
        session()->flash('delete', 'تم حذف القسم بنجاح');
        return redirect('/products');
    }
}

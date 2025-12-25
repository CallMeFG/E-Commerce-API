<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class ProductController extends Controller
{
    public function index(){
        $products=Product::with('shop')->latest()->paginate(10);
        return ProductResource::collection($products);
    }
    public function store(Request $request){
        $request->validate([
            'name'=>'required|string|max:255',
            'price'=>'required|numeric|min:100',
            'stock'=>'required|integer|min:1',
        ]);
        $user=$request->user();
        if(!$user->shop){
            return response()->json(['message'=>'harus membuat toko terlebih dahulu'],403);
        }
        $product=$user->shop->products()->create([
            'name'=>$request->name,
            'slug'=>Str::slug($request->name).'-'.time(),
            'price'=>$request->price,
            'stock'=>$request->stock,
        ]);
        return new ProductResource($product);
    }
    public function show($id){
        $product=Product::with('shop')->findOrFail($id);
        return new ProductResource($product);
    }
}

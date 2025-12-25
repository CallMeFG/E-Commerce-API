<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class ShopController extends Controller
{
    public function Store(Request $request){
        $request->validate([
            'name'=>'required|string|max:255|unique:shops,name',
            'description'=>'nullable|string',
        ]);
        $user=$request->user();
        if($user->shop){
            return response()->json(['message'=>'User sudah memiliki toko'],400);
        }
        $shop=$user->shop()->create([
            'name'=>$request->name,
            'slug'=>Str::slug($request->name),
            'description'=>$request->description,
        ]);
        return response()->json(['message'=>'Toko berhasil dibuat','data'=>$shop],201);
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'slug'=>$this->slug,
            'price'=>(float) $this->price,
            'stock'=>$this->stock,
            'shop_name'=>$this->shop->name,
            'created_at_human'=>$this->created_at->diffForHumans(),
        ];
    }
}

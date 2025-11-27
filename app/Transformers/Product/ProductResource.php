<?php

namespace App\Transformers\Product;

use App\Models\Product\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Transformador o mapper del modelo Product
 * 
 * @mixin Product
 * @property int    $id 
 * @property int    $category_id
 * @property string $name
 * @property string $description
 * @property int    $price
 * @property int    $stock
 * @property \Illuminate\Support\Carbon $created_at
 *
 */
class ProductResource extends JsonResource {

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     */
    public function toArray(Request $request): array {
        return [
            'id'            => $this->id,
            'category_id'   => $this->category_id,
            'category_name' => $this->category?->name,
            'name'          => $this->name,
            'description'   => $this->description,
            'price'         => $this->price,
            'stock'         => $this->stock,
            'created_at'    => $this->created_at?->format('d/m/Y H:i:s'),
            /** Relations */
            'category'      => $this->whenLoaded('category')
        ];
    }
}
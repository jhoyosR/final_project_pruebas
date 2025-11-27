<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model {

    use HasFactory;

    protected $table = 'products';

    protected $dates  = ['created_at', 'updated_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'stock',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'category_id' => 'integer',
        'name'        => 'string',
        'description' => 'string',
        'price'       => 'integer',
        'stock'       => 'integer',
        'created_at'  => 'datetime'
    ];

    /**
     * Obtiene la categoria asociada
     *
     * @return BelongsTo
     */
    public function category(): BelongsTo {
        return $this->belongsTo(Category::class, 'category_id');
    }
}

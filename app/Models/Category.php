<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model {

    use HasFactory;

    protected $table = 'categories';

    protected $dates  = ['created_at', 'updated_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'name' => 'string',
    ];

    /**
     * Obtiene los productos asociadas
     *
     * @return HasMany
     */
    // public function products(): HasMany {
    //     return $this->hasMany(Product::class, 'category_id');
    // }
}

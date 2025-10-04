<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    /** @use HasFactory<\Database\Factories\ProductoFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'codigo',
        'qr',
        'description',
        'price',
        'stock',
        'image',
        'images',
    ];

    protected $casts = [
        'images' => 'array',
    ];
    
}

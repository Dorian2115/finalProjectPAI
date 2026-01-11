<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'rating',
        'cover_path',
        'reading_status',
        'user_id',
        'is_favorite',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'decimal:1',
            'is_favorite' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}

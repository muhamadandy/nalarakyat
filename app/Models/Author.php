<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Author extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'occupation',
        'avatar',
        'slug',
    ];

    protected static function booted(): void
    {
        static::creating(function (Author $author) {
            $baseSlug = Str::slug($author->name);
            $slug = $baseSlug;
            $counter = 2;

            while (
                static::withTrashed()
                    ->where('slug', $slug)
                    ->exists()
            ) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }

            $author->slug = $slug;
        });
    }

    public function news(): HasMany
    {
        return $this->hasMany(ArticleNews::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ArticleNews extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'thumbnail',
        'content',
        'category_id',
        'author_id',
        'is_featured',
    ];

    protected static function booted(): void
    {
        static::creating(function (ArticleNews $article) {
            $slug = Str::slug($article->name);
            $original = $slug;
            $i = 2;

            while (static::withTrashed()->where('slug', $slug)->exists()) {
                $slug = $original . '-' . $i++;
            }

            $article->slug = $slug;
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id')->withTrashed();
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class, 'author_id')->withTrashed();
    }
}
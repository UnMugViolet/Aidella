<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Orchid\Attachment\Models\Attachment;
use Orchid\Filters\Filterable;
use Orchid\Filters\Types\Where;
use Orchid\Filters\Types\Like;

class PostCategory extends Model
{
    use Filterable;
    /** @use HasFactory<\Database\Factories\PostCategoryFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'slug',
    ];

    /* The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that are allowed to be sorted in the admin panel.
     *
     * @var list<string>
     */
    protected $allowedSorts = [
        'id',
        'name',
        'description',
        'slug',
    ];

    protected $allowedFilters = [
        'id'            => Where::class,
        'name'          => Like::class,
        'description'   => Like::class,
        'slug'          => Like::class,
    ];

    public function attachments(): MorphToMany
    {
        return $this->morphToMany(
            Attachment::class,
            'attachmentable',
            'attachmentable',
        );
    }


    public function posts()
    {
        return $this->hasMany(BlogPost::class, 'category_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($category) {
            $category->posts()->each(function ($post) {
                $post->delete();
            });
        });
    }
}

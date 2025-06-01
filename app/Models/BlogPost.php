<?php

namespace App\Models;

use Orchid\Filters\Filterable;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Types\Like;
use Orchid\Filters\Types\Where;

class BlogPost extends Model
{
    use CrudTrait;
    use Filterable;
    /** @use HasFactory<\Database\Factories\BlogPostFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'content',
        'status',
        'meta_title',
        'meta_description',
        'slug',
        'author_id',
        'published_at',
        'category_id',
        'dog_race_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'published_at' => 'datetime',
    ];
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array<int, string>
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
        'title',
        'content',
        'status',
        'author_id',
        'slug',
        'published_at',
        'category_id',
        'dog_race_id',
    ];

    protected $allowedFilters = [
        'id'            => Where::class,
        'title'         => Like::class,
        'content'       => Like::class,
        'status'        => Like::class,
        'author_id'     => Where::class,
        'slug'          => Like::class,
        'published_at'  => Where::class,
        'category_id'   => Where::class,
        'dog_race_id'   => Where::class,
    ];

    /**
     * Get all the images for the BlogPost.
     * 
     * @return string|null
     */
    public function images()
    {
        $images = $this->pictures()->pluck('path')->toArray();
        return !empty($images) ? implode(',', $images) : null;
    }


    /**
     * Relationship: A BlogPost can belongs to a DogRace.
     * Whatch out this function can return NULL.
     */
    public function dogRace()
    {
        return $this->belongsTo(DogRace::class, 'dog_race_id');
    }

    /**
     * Relationship: A BlogPost belongs to a PostCategory.
     * Returns the category linked to the post.
     */
    public function category()
    {
        return $this->belongsTo(PostCategory::class, 'category_id');
    }

    /**
     * Relationship: A BlogPost belongs to an Author.
     * Returns the author linked to the post.
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Relationship: A BlogPost can have many pictures.
     * Returns the pictures linked to the post.
     */
    public function pictures()
    {
        return $this->morphMany(Pictures::class, 'imageable');
    }
}

<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Filters\Types\Where;
use Orchid\Filters\Types\Like;

class PostCategory extends Model
{
    use CrudTrait;
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

    public function pictures()
    {
        return $this->morphMany(Pictures::class, 'imageable');
    }
}

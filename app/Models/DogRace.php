<?php

namespace App\Models;

use Orchid\Filters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Facades\Storage;
use Orchid\Attachment\Attachable;
use Orchid\Attachment\Models\Attachment;
use Orchid\Filters\Types\Like;
use Orchid\Filters\Types\Where;

class DogRace extends Model
{
    use Attachable;
    use Filterable;
    /** @use HasFactory<\Database\Factories\DogRaceFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'description',
        'order',
        'slug',
    ];

    /**
     * The attributes that should be hidden for serialization.
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
        'order',
        'name',
        'description',
        'slug',
    ];

    protected $allowedFilters = [
        'order'         => Where::class,
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

    public function blogPost()
    {
        return $this->hasOne(BlogPost::class);
    }

    /**
     * Get the image name without the prefix and underscore
     */
    public function getThumbnail()
    {
        $main = $this->attachments()->where('group', 'thumbnail')->first();
        if ($main) {
            return pathinfo($main->name, PATHINFO_FILENAME);
        }
        return null;
    }
}

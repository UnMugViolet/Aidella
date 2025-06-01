<?php

namespace App\Models;

use Orchid\Filters\Filterable;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Orchid\Filters\Types\Like;
use Orchid\Filters\Types\Where;

class DogRace extends Model
{
    use CrudTrait;
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


    public function pictures()
    {
        return $this->morphMany(Pictures::class, 'imageable');
    }

    
    public function parent()
    {
        return $this->belongsTo(DogRace::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(DogRace::class, 'parent_id');
    }

    /**
     * Get related dog races
     */
    public function getRelated($limit = 5)
    {
        return static::where('id', '!=', $this->id)
                    ->limit($limit)
                    ->get();
    }

    /**
     * Get the image name without the prefix and underscore
     */
    public function getMainImageName()
    {
        $mainPicture = $this->pictures()->where('is_main', 1)->first();
        if (!$mainPicture) {
            return '';
        }
        $filename = basename($mainPicture->path);
        $pos = strpos($filename, '_');
        if (!$pos ) {
            return $filename;
        }
        return substr($filename, $pos + 1);
    }

    public function getMainImageAttribute()
    {
        $main = $this->pictures()->where('is_main', 1)->first();
        return $main ? $main->path : null;
    }

    /**
     * Boot the model and add event listeners
     */
    protected static function boot()
    {
        parent::boot();

        // Delete related pictures when dog race is deleted
        static::deleting(function ($dogRace) {
            // Get all pictures before deleting database records
            $pictures = $dogRace->pictures;
            
            // Delete image files from storage
            foreach ($pictures as $picture) {
                if ($picture->path && Storage::disk('public')->exists($picture->path)) {
                        Storage::disk('public')->delete($picture->path);
                }
            }
            
            // Delete database records
            $dogRace->pictures()->delete();
        });
    }
}

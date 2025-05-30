<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DogRace extends Model
{
    use CrudTrait;
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
        'slug',
        // Do NOT include 'images', 'main_image', 'picture_name', etc. It is handled by the Pictures model
        // and the morphMany relationship defined below.
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

    public function pictures()
    {
        return $this->morphMany(Pictures::class, 'imageable', 'model_type', 'imageable_id');
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
     * Get all the images for the DogRace.
     * 
     * @return string|null
     */
    public function images()
    {
        $images = $this->pictures()->pluck('path')->toArray();
        return !empty($images) ? implode(',', $images) : null;
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
                if ($picture->path) {
                    // The path already includes the full path and extension
                    if (\Storage::disk('public')->exists($picture->path)) {
                        \Storage::disk('public')->delete($picture->path);
                    }
                }
            }
            
            // Delete database records
            $dogRace->pictures()->delete();
        });
    }
}

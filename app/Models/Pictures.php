<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pictures extends Model
{
    use CrudTrait;
    /** @use HasFactory<\Database\Factories\PicturesFactory> */
    use HasFactory;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'path',
        'is_main',
        'alt_text',
        'model_type',
        'imageable_id',
    ];

    /**
     * Attributes that have default values.
     */
    protected $attributes = [
        'is_main' => false,
    ];

    /**
     * Get all of the owning imageable models.
     */
    public function imageable()
    {
        return $this->morphTo();
    }
}

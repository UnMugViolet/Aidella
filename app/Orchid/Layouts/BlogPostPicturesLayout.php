<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\Picture;
use Orchid\Screen\Layouts\Rows;

class BlogPostPicturesLayout extends Rows
{
    /**
     * Used to create the title of a group of form elements.
     *
     * @var string|null
     */
    protected $title;

    /**
     * Get the fields elements to be displayed.
     *
     * @return Field[]
     */
    protected function fields(): iterable
    {
        return [
            Picture::make('post.thumbnail')
                ->title('Images carrousel')
                ->storage('public')
                ->path('uploads/posts')
                ->acceptedFiles('image/*')
                ->maxFiles(5)
                ->help('Téléchargez jusqu\'à 5 images pour le carrousel du post'),
        ];
    }
}

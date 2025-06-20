<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\Attach;
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
            Attach::make('post.gallery')
                ->title('Images carrousel')
                ->maxCount(5)
                ->save()
                ->multiple()
                ->groups('gallery')
                ->storage('public')
                ->path('uploads/dog-races')
                ->acceptedFiles('image/*')
                ->help('Téléchargez jusqu\'à 5 images pour l\'article'),
        ];
    }
}

<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Rows;
use Illuminate\Support\Str;

class BlogPostSeoLayout extends Rows
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
            Input::make('post.meta_title')
                ->title('Titre SEO')
                ->placeholder('Meta Title du post')
                ->default(Str::limit('Titre du post', 60))
                ->help('Le titre SEO est utilisé pour le référencement dans les moteurs de recherche'),

            TextArea::make('post.meta_description')
                ->title('Description SEO')
                ->rows(3)
                ->placeholder('Meta Description du post')
                ->default(Str::limit('Description du post', 160))
                ->help('Meta description de la page, utilisée pour le référencement dans les moteurs de recherche'),
        ];
    }
}

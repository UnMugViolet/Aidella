<?php

namespace App\Orchid\Layouts;

use App\Models\DogRace;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;

class BlogPostDogRaceLayout extends Rows
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
        Input::make('post.title')
            ->title('Titre')
            ->placeholder('Titre du post')
            ->required(),

        Select::make('post.status')
            ->title('Statut du post')
            ->options([
                'draft'     => 'Brouillon',
                'published' => 'Publié',
                'archived'  => 'Archivé',
            ])
            ->value('draft')
            ->help('Choisissez le statut du post'),

        Quill::make('html')
            ->title('Contenu')
            ->placeholder('Contenu du post')
            ->required(),

        Relation::make('post.dog_race_id')
            ->title('Race de chien')
            ->fromModel(DogRace::class, 'name')
            ->displayAppend('name')
            ->required()
            ->help('Sélectionnez la race de chien associée au post'),

        Relation::make('post.author_id')
            ->title('Auteur')
            ->fromModel(\App\Models\User::class, 'name')
            ->displayAppend('name')
            ->default(1)
            ->help('Sélectionnez l\'auteur du post'),
        ];
    }
}

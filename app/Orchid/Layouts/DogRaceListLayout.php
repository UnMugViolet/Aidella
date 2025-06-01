<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class DogRaceListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'dogRaces';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('order', 'Ordre')
                ->sort()
                ->cantHide()
                ->filter(TD::FILTER_NUMERIC)
                ->render(fn ($dogRace) => $dogRace->order),
            TD::make('name', 'Nom')
                ->sort()
                ->cantHide()
                ->filter(TD::FILTER_TEXT)
                ->render(fn ($dogRace) => $dogRace->name),

            TD::make('description', 'Description')
                ->sort()
                ->cantHide()
                ->filter(TD::FILTER_TEXT)
                ->render(fn ($dogRace) => $dogRace->description),

            TD::make('slug', 'Slug')
                ->sort()
                ->cantHide()
                ->filter(TD::FILTER_TEXT)
                ->render(fn ($dogRace) => $dogRace->slug),
        ];
    }
}

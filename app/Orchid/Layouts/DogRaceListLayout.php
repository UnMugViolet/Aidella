<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Illuminate\Support\Str;
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
                ->render(fn ($dogRace) => Str::limit($dogRace->name, 40)),


            TD::make('description', 'Description')
                ->sort()
                ->cantHide()
                ->filter(TD::FILTER_TEXT)
                ->render(fn ($dogRace) => Str::limit($dogRace->description, 40)),

            TD::make('slug', 'Voir la page')
                ->sort()
                ->cantHide()
                ->filter(TD::FILTER_TEXT)
                ->render(fn ($dogRace) =>
                    $dogRace->blogPost
                        ? Link::make(Str::limit($dogRace->blogPost->slug, 20))
                            ->href(config('app.url') . $dogRace->blogPost->slug)
                            ->target('_blank')
                            ->icon('bs.eye')
                        : ''
                ),

            TD::make('actions', 'Actions')
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(fn ($dogRace) =>
                    DropDown::make()
                        ->icon('bs.three-dots-vertical')
                        ->list([
                            Link::make(__('Edit'))
                                ->route('platform.dog-races.edit', $dogRace->id)
                                ->icon('bs.pencil'),

                            Button::make(__('Delete'))
                                ->icon('bs.trash3')
                                ->confirm(__('Etes vous sûr de vouloir supprimer cette race de chien ? Cela supprimera egalement toutes les images associées et la page du chien.'))
                                ->method('remove', [
                                    'id' => $dogRace->id,
                                ]),
                        ])
                ),
            ];
    }
}

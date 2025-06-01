<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class PostCategoryListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'postCategories';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('id', 'ID')
                ->sort()
                ->cantHide()
                ->filter(TD::FILTER_NUMERIC)
                ->render(fn ($postCategory) => $postCategory->id),

            TD::make('name', 'Nom')
                ->sort()
                ->cantHide()
                ->filter(TD::FILTER_TEXT)
                ->render(fn ($postCategory) => $postCategory->name),

            TD::make('slug', 'Slug')
                ->sort()
                ->cantHide()
                ->filter(TD::FILTER_TEXT)
                ->render(fn ($postCategory) => $postCategory->slug),
            TD::make('description', 'Description')
                ->sort()
                ->cantHide()
                ->filter(TD::FILTER_TEXT)
                ->render(fn ($postCategory) => $postCategory->description),

            TD::make('actions', 'Actions')
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(fn ($postCategory) =>
                    DropDown::make()
                        ->icon('bs.three-dots-vertical')
                        ->list([
                            Link::make(__('Edit'))
                                ->route('platform.post-categories.edit', $postCategory->id)
                                ->icon('bs.pencil'),

                            Button::make(__('Delete'))
                                ->icon('bs.trash3')
                                ->confirm(__('Etes vous sûr de vouloir supprimer cette race de chien ? Cela supprimera egalement toutes les images associées et la page du chien.'))
                                ->method('remove', [
                                    'id' => $postCategory->id,
                                ]),
                        ])
                ),
        ];
    }
}

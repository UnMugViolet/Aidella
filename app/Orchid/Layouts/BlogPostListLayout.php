<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\Layouts\Table;
use Illuminate\Support\Str;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;


use Orchid\Screen\TD;


class BlogPostListLayout extends Table
{
    /**
     * The date format used in the table columns.
     */
    private const DATE_FORMAT = 'd/m/Y H:i';

    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'blogPosts';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('title', 'Titre')
                ->sort()
                ->cantHide()
                ->filter(TD::FILTER_TEXT)
                ->render(fn ($blogPost) => Str::limit($blogPost->title, 40)),

            TD::make('content', 'Contenu')
                ->sort()
                ->cantHide()
                ->filter(TD::FILTER_TEXT)
                ->render(fn ($blogPost) => Str::limit(strip_tags($blogPost->content), 100)),

            TD::make('status', 'Statut')
                ->sort()
                ->cantHide()
                ->filter(TD::FILTER_TEXT)
                ->render(fn ($blogPost) => $blogPost->status),

            TD::make('category.name', 'Catégorie')
                ->sort()
                ->cantHide()
                ->filter(TD::FILTER_TEXT)
                ->render(fn ($blogPost) => Str::limit(strip_tags($blogPost->category->name), 9)),

            TD::make('dogRace.name', 'Race de chien')
                ->sort()
                ->cantHide()
                ->filter(TD::FILTER_TEXT)
                ->render(fn ($blogPost) => Str::limit(strip_tags($blogPost->dogRace->name), 9)),
            
            TD::make('author.name', 'Auteur')
                ->sort()
                ->cantHide()
                ->filter(TD::FILTER_TEXT)
                ->render(fn ($blogPost) => Str::limit(strip_tags($blogPost->author->name), 10)),
            
            TD::make('published_at', 'Créé le')
            ->sort()
            ->cantHide()
                ->render(fn ($blogPost) => $blogPost->published_at->format(self::DATE_FORMAT)),
            
            TD::make('slug', 'Voir la page')
                ->sort()
                ->cantHide()
                ->filter(TD::FILTER_TEXT)
                ->render(fn ($blogPost) =>
                    Link::make(Str::limit($blogPost->slug, 13))
                        ->href(config('app.url') . '/' . $blogPost->category->slug . '/' . $blogPost->slug)
                        ->target('_blank')
                        ->icon('bs.eye')
            ),

            TD::make('actions', 'Actions')
                ->align(TD::ALIGN_CENTER)
                ->width('10px')
                ->render(fn ($blogPost) =>
                    DropDown::make()
                        ->icon('bs.three-dots-vertical')
                        ->list([
                            Link::make(__('Edit'))
                                ->route('platform.posts.edit', $blogPost->id)
                                ->icon('bs.pencil'),

                            Button::make(__('Delete'))
                                ->icon('bs.trash3')
                                ->confirm(__('Etes vous sûr de vouloir supprimer cette race de chien ? Cela supprimera egalement toutes les images associées et la page du chien.'))
                                ->method('remove', [
                                    'id' => $blogPost->id,
                                ]),
                        ])
            ),
        ];
    }
}

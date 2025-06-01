<?php

namespace App\Orchid\Screens;

use App\Models\BlogPost;
use App\Orchid\Layouts\BlogPostListLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class BlogPostListScreen extends Screen
{
    public $name = 'Liste des articles de blog';
    public $description = 'Retrouvez ici la liste des articles de blog. Vous pourrez les modifier ou les supprimer depuis l\'option "Action"';

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'blogPosts' => BlogPost::filters()
                ->defaultSort('title', 'desc')
                ->paginate(),
        ];
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            BlogPostListLayout::class,
        ];
    }

    public function remove(Request $request): void
    {
        BlogPost::findOrFail($request->get('id'))->delete();

        Toast::info(__('Race de chien supprimée'));
    }
}

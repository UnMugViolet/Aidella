<?php

namespace App\Orchid\Screens;

use App\Models\BlogPost;
use App\Orchid\Layouts\BlogPostListLayout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
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
            'blogPosts' => BlogPost::whereNotNull('category_id')
            ->filters()
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

}

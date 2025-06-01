<?php

namespace App\Orchid\Screens;

use App\Models\PostCategory;
use App\Orchid\Layouts\PostCategoryListLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class PostCategoryListScreen extends Screen
{
    public $name = 'Liste des catégories de blog';
    public $description = 'Retrouvez ici la liste des catégories de blog. Vous pourrez les modifier ou les supprimer depuis l\'option "Action"';
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'postCategories' => PostCategory::filters()->defaultSort('id', 'asc')->paginate(),
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
            PostCategoryListLayout::class,
        ];
    }

    public function remove(Request $request): void
    {
        PostCategory::findOrFail($request->get('id'))->delete();

        Toast::info(__('Catégorie de blog supprimée'));
    }
}

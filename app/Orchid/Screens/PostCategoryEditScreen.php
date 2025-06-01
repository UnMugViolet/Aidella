<?php

namespace App\Orchid\Screens;

use App\Models\PostCategory;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class PostCategoryEditScreen extends Screen
{
    public $postCategory;
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(PostCategory $postCategory): iterable
    {
        $this->postCategory = $postCategory;
        return [
            'postCategory' => $postCategory,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Modifier la catégorie de blog';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Supprimer')
                ->icon('trash')
                ->confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?')
                ->method('remove')
                ->parameters([
                    'id' => $this->postCategory->id,
                ]),
            Button::make('Enregistrer')
                ->icon('check')
                ->method('save'),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::rows([
                Input::make('postCategory.name')
                    ->title('Nom')
                    ->placeholder('Nom de la catégorie')
                    ->required(),

                TextArea::make('postCategory.description')
                    ->title('Description')
                    ->rows(5)
                    ->placeholder('Description de la catégorie'),
            ]),
        ];
    }

    public function remove(Request $request)
    {
        PostCategory::findOrFail($request->get('id'))->delete();

        Toast::info(__('Catégorie de blog supprimée'));
        
        return redirect()->route('platform.post-categories');
    }
}

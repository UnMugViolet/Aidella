<?php

namespace App\Orchid\Screens;

use App\Models\PostCategory;
use Illuminate\Http\Request;
use Orchid\Support\Facades\Toast;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Screen;

class BlogPostScreen extends Screen
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
        return 'Gestion des catégories de blog';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
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

    public function save(PostCategory $postCategory, Request $request)
    {
        $data = $request->get('postCategory');

        // Validate the data
        $request->validate([
            'postCategory.name' => 'required|string|max:255',
            'postCategory.description' => 'nullable|string|max:255',
        ]);

        // Save only if the name is not already taken
        if (PostCategory::where('name', $data['name'])->where('id', '!=', $postCategory->id)->exists()) {
            Toast::error('Cette categorie existe déjà.');
            return null;
        }
        // Save the post category
        $postCategory->fill($data);
        $postCategory->save();

        Toast::success('Catégorie de blog enregistrée avec succès.');

        return redirect()->route('platform.post-categories');
    }
}



<?php

namespace App\Orchid\Screens;

use App\Models\PostCategory;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Picture;
use Orchid\Support\Facades\Layout;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\Radio;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Screen;
use Illuminate\Support\Str;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Fields\Select;

class BlogPostScreen extends Screen
{

    public $name = 'Ajouter un Post';
    public $description = 'Cette page permet d\'ajouter un post.';

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [];
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
                ->method('publish')
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
            Layout::tabs([
                'Contenu' => Layout::rows([
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

                    Relation::make('post.category_id')
                        ->title('Catégorie')
                        ->fromModel(PostCategory::class, 'name')
                        ->displayAppend('name')
                        ->default($this->getGeneralCategoryId())
                        ->help('Sélectionnez la categorie du post'),
                    Relation::make('post.author_id')
                        ->title('Auteur')
                        ->fromModel(\App\Models\User::class, 'name')
                        ->displayAppend('name')
                        ->default(1)
                        ->help('Sélectionnez l\'auteur du post'),
                ]),
                'SEO' => Layout::rows([
                    Input::make('post.meta_title')
                        ->title('Titre SEO')
                        ->placeholder('Meta Title du post')
                        ->default(Str::limit('Titre du post', 60))
                        ->help('Le titre SEO est utilisé pour le référencement dans les moteurs de recherche'),

                    TextArea::make('post.meta_description')
                        ->title('Description SEO')
                        ->rows(3)
                        ->placeholder('Meta Description du post')
                        ->default(Str::limit('Description du post', 160))
                        ->help('Meta description de la page, utilisée pour le référencement dans les moteurs de recherche'),
                ]),
                'Images' => Layout::rows([
                    Picture::make('post.thumbnail')
                        ->title('Images carrousel')
                        ->storage('public')
                        ->path('uploads/posts')
                        ->acceptedFiles('image/*')
                        ->maxFiles(5)
                        ->help('Téléchargez jusqu\'à 5 images pour le carrousel du post'),
                ]),
            ]),
        ];
    }

    public function getGeneralCategoryId(): int
    {
        $generalCategory = PostCategory::where('slug', 'general')->first();
        return $generalCategory ? $generalCategory->id : 0;
    }
}

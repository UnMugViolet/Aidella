<?php

namespace App\Orchid\Screens;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Picture;
use Orchid\Support\Facades\Layout;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Screen;

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
                ->method('save')
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
                Input::make('post.title')
                ->title('Titre')
                ->placeholder('Titre du post')
                ->required(),
                
            Picture::make('post.thumbnail')
                ->title('Images carrousel')
                ->storage('public')
                ->path('uploads/posts')
                ->acceptedFiles('image/*')
                ->maxFiles(5)
                ->help('Téléchargez jusqu\'à 5 images pour le carrousel du post'),

            Quill::make('html')
                ->title('Contenu')
                ->placeholder('Contenu du post')
                ->required(),

            ]),
        ];
    }

}



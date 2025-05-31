<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BlogPostRequest;
use App\Models\PostCategory;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class BlogPostCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class BlogPostCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\BlogPost::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/blog-post');
        CRUD::setEntityNameStrings('blog post', 'blog posts');
    }

    protected function setupListOperation()
    {
        // CRUD::setFromDb(); // set columns from db columns.

        CRUD::column('titre')
            ->label('Titre')
            ->type('text');
        CRUD::column('slug')
            ->label('Slug')
            ->type('text')
            ->hint('Généré automatiquement à partir du titre');
        CRUD::column('category.name')
            ->label('Catégorie')
            ->type('relationship')
            ->entity('category')
            ->attribute('name') // the attribute on the related model to show
            ->model(PostCategory::class); // the model to use for the select
        CRUD::column('content')
            ->label('Contenu')
            ->type('textarea')
            ->limit(100); // Limit the content displayed in the list view
        CRUD::column('status')
            ->label('Statut')
            ->type('text')
            ->options([
                'draft' => 'Brouillon',
                'published' => 'Publié',
                'archived' => 'Archivé'
            ]);
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        // CRUD::setFromDb(); // set fields from db columns.
        CRUD::setValidation(BlogPostRequest::class);
        CRUD::field('title')
            ->label('Titre')
            ->type('text')
            ->attributes(['placeholder' => 'Entrez le titre du blog post'])
            ->hint('Le titre de l\'article de blog');
        CRUD::field('category_id')
            ->label('Catégorie')
            ->type('select')
            ->entity('category') // the method that defines the relationship in the model
            ->attribute('name') // the attribute on the related model to show
            ->model(PostCategory::class) // the model to use for the select
            ->hint('Sélectionnez la catégorie de l\'article de blog');
        CRUD::field('content')
            ->label('Contenu')
            ->type('textarea')
            ->attributes(['placeholder' => 'Entrez le contenu de l\'article de blog', 'rows' => 10])
            ->hint('Le contenu principal de l\'article de blog');
        CRUD::field('status')
            ->label('Statut')
            ->type('enum')
            ->options([
                'draft' => 'Brouillon',
                'published' => 'Publié',
                'archived' => 'Archivé'
            ])
            ->hint('Le statut de l\'article de blog');

        // Add slug field that auto-generates from name
        CRUD::field('slug')
            ->type('text')
            ->hint('Généré automatiquement à partir du titre');
        
        // Add JavaScript to auto-generate slug
        CRUD::addField([
            'name' => 'slug_generator',
            'type' => 'custom_html',
            'value' => '
            <script>
            document.addEventListener("DOMContentLoaded", function() {
                const titleField = document.querySelector(\'input[name="title"]\');
                const slugField = document.querySelector(\'input[name="slug"]\');
                
                function createSlug(text) {
                    return text
                        .toLowerCase()
                        .normalize("NFD")
                        .replace(/[\u0300-\u036f]/g, "") // Remove accents
                        .replace(/[^a-z0-9\s-]/g, "") // Remove special characters except spaces and hyphens
                        .replace(/\s+/g, "-") // Replace spaces with hyphens
                        .replace(/-+/g, "-") // Replace multiple hyphens with single hyphen
                        .trim();
                }
                
                if (titleField && slugField) {
                    titleField.addEventListener("input", function() {
                        const slug = createSlug(this.value);
                        slugField.value = slug;
                    });
                }
            });
            </script>'
        ]);
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}

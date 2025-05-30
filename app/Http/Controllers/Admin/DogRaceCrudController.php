<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\ReorderOperation;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Storage;
use Prologue\Alerts\Facades\Alert as FacadesAlert;

/**
 * Class DogRaceCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class DogRaceCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ReorderOperation; 

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\DogRace::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/dog-race');
        CRUD::setEntityNameStrings('race de chien', 'race de chiens');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        // CRUD::setFromDb();

        CRUD::column('name')
            ->label('Nom')
            ->type('text');
        CRUD::column('slug')
            ->label('Slug')
            ->type('text')
            ->hint('Généré automatiquement à partir du nom');
        // Add a custom column for the formatted image name
        CRUD::column('description')
            ->label('Description')
            ->type('text');
        CRUD::addColumn([
            'name' => 'main_image_name',
            'label' => 'Image mignature',
            'type' => 'model_function',
            'function_name' => 'getMainImageName',
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
        CRUD::setFromDb(); // set fields from db columns.

        // Add slug field that auto-generates from name
        CRUD::field('slug')
            ->type('text')
            ->hint('Généré automatiquement à partir du nom');

        // Add main image selection
        CRUD::addField([
            'label' => 'Image mignature',
            'name' => 'main_image',
            'type' => 'upload',
            'upload' => true,
            'disk' => 'public',
            'prefix' => 'uploads/dog-races/',
            'default' => '', 
            'hint' => 'Sélectionnez une image, laisser vide donne une image par defaut.',
        ]);

        // Add JavaScript to auto-generate slug
        CRUD::addField([
            'name' => 'slug_generator',
            'type' => 'custom_html',
            'value' => '
            <script>
            document.addEventListener("DOMContentLoaded", function() {
                const nameField = document.querySelector(\'input[name="name"]\');
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
                
                if (nameField && slugField) {
                    nameField.addEventListener("input", function() {
                        const slug = createSlug(this.value);
                        slugField.value = slug;
                    });
                }
            });
            </script>'
        ]);
    }

    /**
     * Store multiple images in Pictures table
     */
    protected function store()
    {
        $this->crud->hasAccessOrFail('create');
        
        // Handle file uploads before creating the item
        $uploadedFiles = $this->handleFileUploads();
        
        $request = $this->crud->validateRequest();
        $to_create = $this->crud->getStrippedSaveRequest($request);
        // Ensure there is no duplicate name
        if (
            $this->crud->model::where('name', $to_create['name'])->exists() ||
            $this->crud->model::where('slug', $to_create['slug'])->exists()
        ) {
            FacadesAlert::error('Une race avec ce nom ou ce slug existe déjà.')->flash();
            return redirect()->back()->withInput();
        }

        $item = $this->crud->create($to_create);
        $this->data['entry'] = $this->crud->entry = $item;

        // Create picture records with uploaded filenames
        $this->createPictureRecords($item, $uploadedFiles);

        FacadesAlert::success(trans('backpack::crud.insert_success'))->flash();
        $this->crud->setSaveAction();
        return $this->crud->performSaveAction($item->getKey());
    }

    /**
     * Update thumbnail images in Pictures table
     */
    protected function update()
    {
        $this->crud->hasAccessOrFail('update');
        
        // Handle file uploads before updating the item
        $uploadedFiles = $this->handleFileUploads();
        
        $request = $this->crud->validateRequest();

        $item = $this->crud->update(
            $request->get($this->crud->model->getKeyName()),
            $this->crud->getStrippedSaveRequest($request)
        );
        
        $this->data['entry'] = $this->crud->entry = $item;

        // Update picture records
        $this->updatePictureRecords($item, $uploadedFiles);

        FacadesAlert::success(trans('backpack::crud.update_success'))->flash();
        $this->crud->setSaveAction();
        return $this->crud->performSaveAction($item->getKey());
    }

    /**
     * Handle file uploads manually
     */
    private function handleFileUploads()
    {
        $uploadedFiles = [
            'main_image' => null,
            'images' => []
        ];

        // Handle main image upload
        if (request()->hasFile('main_image')) {
            $file = request()->file('main_image');
            $filename = uniqid() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            
            // Ensure directory exists
            Storage::disk('public')->makeDirectory('uploads/dog-races');
            
            $path = $file->storeAs('uploads/dog-races', $filename, 'public');
            
            if ($path) {
                $uploadedFiles['main_image'] = [
                    'filename' => $file->getClientOriginalName(),
                    'path' => 'uploads/dog-races/' . $filename,
                    'original_name' => $file->getClientOriginalName()
                ];
            }
        }

        return $uploadedFiles;
    }

    /**
     * Create picture records for new items
     */
    private function createPictureRecords($item, $uploadedFiles)
    {
        // Create main image record
        if ($uploadedFiles['main_image']) {
            // Format the filename for alt text (remove extension and replace spaces/hyphens with underscores)
            $formattedName = str_replace([' ', '-'], '_', pathinfo($uploadedFiles['main_image']['original_name'], PATHINFO_FILENAME));
            
            $item->pictures()->create([
                'path' => '/storage/' . $uploadedFiles['main_image']['path'],
                'alt_text' => $formattedName,
                'is_main' => 1,
            ]);
        }
    }

    /**
     * Update picture records for existing items
     */
    private function updatePictureRecords($item, $uploadedFiles)
    {
        // Update main image if new one uploaded
        if ($uploadedFiles['main_image']) {
            // Remove old main image
            $item->pictures()->where('is_main', 1)->delete();
            
            // Format the filename for alt text
            $formattedName = str_replace([' ', '-'], '_', pathinfo($uploadedFiles['main_image']['original_name'], PATHINFO_FILENAME));
            
            $item->pictures()->create([
                'path' => '/storage/' . $uploadedFiles['main_image']['path'],
                'alt_text' => $formattedName,
                'is_main' => 1,
            ]);
        }
    }

    protected function setupReorderOperation()
    {
        CRUD::set('reorder.label', 'name');
        CRUD::set('reorder.max_level', 1);
        CRUD::set('reorder.order_column', 'order'); 
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

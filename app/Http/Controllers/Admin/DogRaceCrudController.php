<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

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
        CRUD::setFromDb(); // set columns from db columns.

        /**
         * Columns can be defined using the fluent syntax:
         * - CRUD::column('price')->type('number');
         */
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
        ]);
    
        // Add multiple image upload field
        CRUD::addField([
            'label' => 'Images',
            'name' => 'images',
            'type' => 'upload_multiple',
            'upload' => true,
            'disk' => 'public', // The disk where you want to store the files
            'prefix' => 'uploads/dog-races/',
        ]);

        // Exclude upload fields from being saved to the main model
        CRUD::setOperationSetting('saveAllInputsExcept', ['images', 'main_image']);

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
        
        // Debug: Check if files are being received
        \Log::info('Files received:', [
            'main_image' => request()->hasFile('main_image'),
            'images' => request()->hasFile('images'),
            'all_files' => request()->allFiles()
        ]);
        
        // Handle file uploads before creating the item
        $uploadedFiles = $this->handleFileUploads();
        
        // Debug: Check what was uploaded
        \Log::info('Uploaded files:', $uploadedFiles);
        
        $request = $this->crud->validateRequest();
        $item = $this->crud->create($this->crud->getStrippedSaveRequest($request));
        $this->data['entry'] = $this->crud->entry = $item;

        // Create picture records with uploaded filenames
        $this->createPictureRecords($item, $uploadedFiles);

        \Alert::success(trans('backpack::crud.insert_success'))->flash();
        $this->crud->setSaveAction();
        return $this->crud->performSaveAction($item->getKey());
    }

    /**
     * Update multiple images in Pictures table
     */
    protected function update()
    {
        $this->crud->hasAccessOrFail('update');
        
        // Handle file uploads before updating the item
        $uploadedFiles = $this->handleFileUploads();
        
        $request = $this->crud->validateRequest();
        $itemId = $this->crud->update(
            $request->get($this->crud->model->getKeyName()),
            $this->crud->getStrippedSaveRequest($request)
        );
        
        // Create a new instance of the model and find the item
        $modelClass = $this->crud->model;
        $item = (new $modelClass)->find($itemId);
        $this->data['entry'] = $this->crud->entry = $item;

        // Update picture records
        $this->updatePictureRecords($item, $uploadedFiles);

        \Alert::success(trans('backpack::crud.update_success'))->flash();
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
            $filename = uniqid() . '_' . $file->getClientOriginalName();
            
            // Ensure directory exists
            \Storage::disk('public')->makeDirectory('uploads/dog-races');
            
            $path = $file->storeAs('uploads/dog-races', $filename, 'public');
            
            if ($path) {
                $uploadedFiles['main_image'] = [
                    'filename' => $filename,
                    'path' => 'uploads/dog-races/' . $filename,
                    'original_name' => $file->getClientOriginalName()
                ];
            }
        }

        // Handle multiple images upload
        if (request()->hasFile('images')) {
            foreach (request()->file('images') as $file) {
                $filename = uniqid() . '_' . $file->getClientOriginalName();
                
                // Ensure directory exists
                \Storage::disk('public')->makeDirectory('uploads/dog-races');
                
                $path = $file->storeAs('uploads/dog-races', $filename, 'public');
                
                if ($path) {
                    $uploadedFiles['images'][] = [
                        'filename' => $filename,
                        'path' => 'uploads/dog-races/' . $filename,
                        'original_name' => $file->getClientOriginalName()
                    ];
                }
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
                'imageable_id' => $item->id,
                'model_type' => get_class($item),
                'alt_text' => $formattedName,
                'is_main' => 1,
            ]);
        }

        // Create multiple image records
        foreach ($uploadedFiles['images'] as $index => $imageData) {
            // Format the filename for alt text (remove extension and replace spaces/hyphens with underscores)
            $formattedName = str_replace([' ', '-'], '_', pathinfo($imageData['original_name'], PATHINFO_FILENAME));
            
            $item->pictures()->create([
                'path' => '/storage/' . $imageData['path'],
                'imageable_id' => $item->id,
                'model_type' => get_class($item),
                'alt_text' => $formattedName,
                'is_main' => 0,
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
                'imageable_id' => $item->id,
                'model_type' => get_class($item),
                'alt_text' => $formattedName,
                'is_main' => 1,
            ]);
        }

        // Update multiple images if new ones uploaded
        if (!empty($uploadedFiles['images'])) {
            // Remove old images
            $item->pictures()->where('is_main', 0)->delete();
            
            foreach ($uploadedFiles['images'] as $index => $imageData) {
                // Format the filename for alt text
                $formattedName = str_replace([' ', '-'], '_', pathinfo($imageData['original_name'], PATHINFO_FILENAME));
                
                $item->pictures()->create([
                    'path' => '/storage/' . $imageData['path'],
                    'imageable_id' => $item->id,
                    'model_type' => get_class($item),
                    'alt_text' => $formattedName,
                    'is_main' => 0,
                ]);
            }
        }
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

<?php

namespace App\Http\Controllers\Admin;

use App\Faq;
use App\Http\Requests\FaqRequest as StoreRequest;
// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\FaqRequest as UpdateRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\ReorderOperation;
use Backpack\CRUD\CrudPanel;

/**
 * Class FaqCrudControllerCrudController.
 *
 * @property CrudPanel $crud
 */
class FaqCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ReorderOperation;

    // use ReorderOperation;

    /**
     * Reorder method for backpack 4.1.
     */
    // protected function setupReorderOperation()
    // {
    //     $this->crud->set('reorder.label', 'question');
    //     $this->crud->set('reorder.max_level', 1);
    // }

    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Faq');
        $this->crud->setRoute(config('backpack.base.route_prefix').'/faq');
        $this->crud->setEntityNameStrings('FAQ', 'FAQs');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->setFromDb();

        $this->crud->set('reorder.label', 'question'); // the attribute on the Model which will be shown on draggable elements
        $this->crud->set('reorder.max_level', 1);

        $this->crud->modifyField('screenshots', [
            'type' => 'upload_multiple',
            'upload' => true,
        ]);

        $this->crud->modifyField('answer', [
            'type' => 'ckeditor',
            'options' => [
                'removePlugins' => 'image,maximize,oembed',
            ],
        ]);

        $this->crud->removeColumn('screenshots');

        $this->crud->modifyColumn('question', [
            'type' => 'text',
            'limit' => 160,
        ]);

        $this->crud->modifyColumn('answer', [
            'visibleInTable' => false,
            'visibleInModal' => true,
            'priority' => 1,
        ]);
        $this->crud->addColumn([
            'name' => 'id',
            'label' => 'ID',
            'visibleInTable' => true,
            'priority' => 1,
        ])->makeFirstColumn();

        // add asterisk for fields that are required in FaqCrudControllerRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        $this->crud->orderBy('lft');
        $this->crud->orderBy('id');
    }

    public function showDetailsRow($id)
    {
        return Faq::findOrFail($id)->answer;
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(StoreRequest::class);
    }

    protected function setupUpdateOperation()
    {
        $this->crud->setValidation(UpdateRequest::class);
    }
}

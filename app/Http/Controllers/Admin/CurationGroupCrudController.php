<?php

namespace App\Http\Controllers\Admin;

use App\CurationActivity;
// VALIDATION: change the requests to match your own file names if you need form validation
use App\CurationGroup;
use App\Http\Requests\CurationGroupRequest as StoreRequest;
use App\Http\Requests\CurationGroupRequest as UpdateRequest;
use App\WorkingGroup;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\CrudPanel;

/**
 * Class CurationGroupCrudController.
 *
 * @property CrudPanel $crud
 */
class CurationGroupCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;

    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel(CurationGroup::class);
        $this->crud->setRoute(config('backpack.base.route_prefix').'/curation-group');
        $this->crud->setEntityNameStrings('curation group', 'curation groups');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

        // add asterisk for fields that are required in CurationGroupRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        $this->crud->modifyField('curation_activity_id', [
            'label' => 'Curation Activity',
            'type' => 'select',
            'entity' => 'curationActivity',
            'name' => 'curation_activity_id',
            'attribute' => 'name',
            'model' => CurationActivity::class,
            'options' => (function ($query) {
                $options = $query->get();
                $options->prepend(new CurationActivity(['name' => '-', 'id' => null]));

                return $options;
            }),
        ]);

        $this->crud->modifyField('working_group_id', [
            'label' => 'Working Group',
            'type' => 'select2',
            'name' => 'working_group_id',
            'entity' => 'workingGroup',
            'attribute' => 'name',
            'model' => WorkingGroup::class,
        ]);

        $this->crud->with('curationActivity');
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(StoreRequest::class);
    }

    protected function setupUpdateOperation()
    {
        $this->crud->setValidation(UpdateRequest::class);
    }

    protected function setupListOperation()
    {
        $this->crud->setColumnDetails('curation_activity_id', [
            'label' => 'Curation Activity', // Table column heading
            'type' => 'select',
            'name' => 'curation_activity_id', // the column that contains the ID of that connected entity;
            'entity' => 'curationActivity', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => CurationActivity::class, // foreign key model
        ]);
        $this->crud->setColumnDetails('working_group_id', [
            'label' => 'Working Group', // Table column heading
            'type' => 'select',
            'name' => 'working_group_id', // the column that contains the ID of that connected entity;
            'entity' => 'workingGroup', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => WorkingGroup::class, // foreign key model
        ]);
        $this->crud->addColumn([
            'label' => 'Accepting Volunters',
            'type' => 'boolean',
            'name' => 'accepting_volunteers',
        ]);
        $this->crud->addColumn([
            'label' => 'ID',
            'type' => 'integer',
            'name' => 'id',
        ])->makeFirstColumn();

        $this->crud->removeColumn(['url']);

        if (! $request->user()->can('create', CurationGroup::class)) {
            $this->crud->RemoveButton('create');
        }

        if (! $request->user()->can('update curation-groups')) {
            $this->crud->RemoveButtonFromStack('update', 'line');
        }

        if (! $request->user()->can('delete curation-groups')) {
            $this->crud->RemoveButtonFromStack('delete', 'line');
        }
    }
}

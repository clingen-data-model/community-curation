<?php

namespace App\Http\Controllers\Admin;

use App\CurationGroup;

// VALIDATION: change the requests to match your own file names if you need form validation
use Backpack\CRUD\CrudPanel;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use App\Http\Requests\CurationGroupRequest as StoreRequest;
use App\Http\Requests\CurationGroupRequest as UpdateRequest;
use App\WorkingGroup;
use App\CurationActivity;

/**
 * Class CurationGroupCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class CurationGroupCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel(CurationGroup::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/curation-group');
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

        $this->crud->addFields([
            [
                'label' => "Curation Activity",
                'type' => 'select2',
                'name' => 'curation_activity_id',
                'entity' => 'curationActivity',
                'attribute' => 'name',
                'model' => CurationActivity::class,
            ],
            [
                'label' => "Working Group",
                'type' => 'select2',
                'name' => 'working_group_id',
                'entity' => 'workingGroup',
                'attribute' => 'name',
                'model' => WorkingGroup::class,
            ],
            [
                'label' => 'Accepting Volunters',
                'type' => 'checkbox',
                'name' => 'accepting_volunteers'
            ]
        ], 'both');

        $this->crud->addColumns([
            [
                'label' => "Curation Activity", // Table column heading
                'type' => "select",
                'name' => 'curation_activity_id', // the column that contains the ID of that connected entity;
                'entity' => 'curationActivity', // the method that defines the relationship in your Model
                'attribute' => "name", // foreign key attribute that is shown to user
                'model' => CurationActivity::class, // foreign key model
            ],
            [
                'label' => "Working Group", // Table column heading
                'type' => "select",
                'name' => 'working_group_id', // the column that contains the ID of that connected entity;
                'entity' => 'workingGroup', // the method that defines the relationship in your Model
                'attribute' => "name", // foreign key attribute that is shown to user
                'model' => WorkingGroup::class, // foreign key model
            ],
            [
                'label' => 'Accepting Volunters',
                'type' => 'boolean',
                'name' => 'accepting_volunteers'

            ]
        ]);

        $this->crud->removeColumn(['url']);

        if (!\Auth::user()->can('create', CurationGroup::class)) {
            $this->crud->RemoveButton('create');
        }

        if (!\Auth::user()->can('update working-groups')) {
            $this->crud->RemoveButtonFromStack('update', 'line');
        }

        if (!\Auth::user()->can('delete working-groups')) {
            $this->crud->RemoveButtonFromStack('delete', 'line');
        }

        $this->crud->with('curationActivity');
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }
}

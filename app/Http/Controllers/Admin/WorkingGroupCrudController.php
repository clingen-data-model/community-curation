<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\WorkingGroupRequest as StoreRequest;
// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\WorkingGroupRequest as UpdateRequest;
use App\WorkingGroup;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\CrudPanel;

/**
 * Class WorkingGroupCrudController.
 *
 * @property CrudPanel $crud
 */
class WorkingGroupCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\WorkingGroup');
        $this->crud->setRoute(config('backpack.base.route_prefix').'/working-group');
        $this->crud->setEntityNameStrings('working group', 'working groups');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

        // add asterisk for fields that are required in WorkingGroupRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        if (!\Auth::user()->can('create', WorkingGroup::class)) {
            $this->crud->RemoveButton('create');
        }

        if (!\Auth::user()->can('update working-groups')) {
            $this->crud->RemoveButtonFromStack('update', 'line');
        }

        if (!\Auth::user()->can('delete working-groups')) {
            $this->crud->RemoveButtonFromStack('delete', 'line');
        }
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

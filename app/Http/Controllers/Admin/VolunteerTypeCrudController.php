<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\VolunteerTypeRequest as StoreRequest;
use App\Http\Requests\VolunteerTypeRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use App\VolunteerType;

/**
 * Class VolunteerTypeCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class VolunteerTypeCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel(VolunteerType::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/volunteer-type');
        $this->crud->setEntityNameStrings('volunteer type', 'volunteer types');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

        // add asterisk for fields that are required in VolunteerTypeRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        if (!\Auth::user()->can('create', VolunteerType::class)) {
            $this->crud->RemoveButton('create');
        }

        if (!\Auth::user()->can('update volunteer-types')) {
            $this->crud->RemoveButtonFromStack('update', 'line');
        }

        if (!\Auth::user()->can('delete volunteer-types')) {
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

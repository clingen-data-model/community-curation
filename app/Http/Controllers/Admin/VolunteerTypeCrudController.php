<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\VolunteerTypeRequest as StoreRequest;
use App\Http\Requests\VolunteerTypeRequest as UpdateRequest;
// VALIDATION: change the requests to match your own file names if you need form validation
use App\VolunteerType;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\CrudPanel;
use Illuminate\Http\Request;

/**
 * Class VolunteerTypeCrudController.
 *
 * @property CrudPanel $crud
 */
class VolunteerTypeCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;

    // use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;

    public function setup(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel(VolunteerType::class);
        $this->crud->setRoute(config('backpack.base.route_prefix').'/volunteer-type');
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

        if (! $request->user()->can('create', VolunteerType::class)) {
            $this->crud->RemoveButton('create');
        }

        if (! $request->user()->can('update volunteer-types')) {
            $this->crud->RemoveButtonFromStack('update', 'line');
        }

        if (! $request->user()->can('delete volunteer-types')) {
            $this->crud->RemoveButtonFromStack('delete', 'line');
        }
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

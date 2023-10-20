<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests\VolunteerStatusRequest as StoreRequest;
// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\VolunteerStatusRequest as UpdateRequest;
use App\Models\VolunteerStatus;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\CrudPanel;

/**
 * Class VolunteerStatusCrudController.
 *
 * @property CrudPanel $crud
 */
class VolunteerStatusCrudController extends CrudController
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
        $this->crud->setModel(\App\VolunteerStatus::class);
        $this->crud->setRoute(config('backpack.base.route_prefix').'/volunteer-status');
        $this->crud->setEntityNameStrings('volunteer status', 'volunteer statuses');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

        // add asterisk for fields that are required in VolunteerStatusRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        if (! Auth::user()->can('create', VolunteerStatus::class)) {
            $this->crud->RemoveButton('create');
        }

        if (! Auth::user()->can('update volunteer-statuses')) {
            $this->crud->RemoveButtonFromStack('update', 'line');
        }

        if (! Auth::user()->can('delete volunteer-statuses')) {
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

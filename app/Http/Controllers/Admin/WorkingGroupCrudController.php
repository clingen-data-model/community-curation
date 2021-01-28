<?php

namespace App\Http\Controllers\Admin;

use App\WorkingGroup;
// VALIDATION: change the requests to match your own file names if you need form validation
use Backpack\CRUD\CrudPanel;
use Prologue\Alerts\Facades\Alert;
use Illuminate\Support\Facades\Redirect;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use App\Http\Requests\WorkingGroupRequest as StoreRequest;
use App\Http\Requests\WorkingGroupRequest as UpdateRequest;

/**
 * Class WorkingGroupCrudController.
 *
 * @property CrudPanel $crud
 */
class WorkingGroupCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation { destroy as traitDestroy; }

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

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(StoreRequest::class);
    }

    protected function setupUpdateOperation()
    {
        $this->crud->setValidation(UpdateRequest::class);
    }

    public function destroy($id)
    {
        $wg = WorkingGroup::findOrFail($id);
        $curationGroupCount = $wg->curationGroups()->count();
        if ($curationGroupCount > 0) {
            $message = 'This working group has curation groups associated with it. You must delete those curation groups before you can delete the working group.';
            return response(['error' => $message], 422);
        }

        return $this->traitDestroy($id);


    }
}

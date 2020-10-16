<?php

namespace App\Http\Controllers\Admin;

use App\Goal;
// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\GoalRequest as StoreRequest;
use App\Http\Requests\GoalRequest as UpdateRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\CrudPanel;

/**
 * Class GoalCrudControllerCrudController.
 *
 * @property CrudPanel $crud
 */
class GoalCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;

    public function setup()
    {
        $this->crud->setModel(Goal::class);
        $this->crud->setRoute(config('backpack.base.route_prefix').'/goal');
        $this->crud->setEntityNameStrings('goal', 'goals');
        $this->crud->setFromDb();

        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->crud->removeButton('delete');
        $this->crud
            ->orderBy('active', 'desc')
            ->orderBy('name');
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

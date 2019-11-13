<?php

namespace App\Http\Controllers\Admin;

use App\Goal;

// VALIDATION: change the requests to match your own file names if you need form validation
use Backpack\CRUD\CrudPanel;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use App\Http\Requests\GoalRequest as StoreRequest;
use App\Http\Requests\GoalRequest as UpdateRequest;

/**
 * Class GoalCrudControllerCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class GoalCrudController extends CrudController
{
    public function setup()
    {
        $this->crud->setModel(Goal::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/goal');
        $this->crud->setEntityNameStrings('goal', 'goals');
        $this->crud->setFromDb();

        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->crud->removeButton('delete');
        $this->crud
            ->orderBy('active', 'desc')
            ->orderBy('name');
    }

    public function store(StoreRequest $request)
    {
        $redirect_location = parent::storeCrud($request);
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        $redirect_location = parent::updateCrud($request);
        return $redirect_location;
    }
}

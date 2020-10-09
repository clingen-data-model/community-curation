<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\InterestRequest as StoreRequest;
// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\InterestRequest as UpdateRequest;
use App\Interest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\CrudPanel;

/**
 * Class InterestCrudController.
 *
 * @property CrudPanel $crud
 */
class InterestCrudController extends CrudController
{
    public function setup()
    {
        $this->crud->setModel(Interest::class);
        $this->crud->setRoute(config('backpack.base.route_prefix').'/interest');
        $this->crud->setEntityNameStrings('interest', 'interests');

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

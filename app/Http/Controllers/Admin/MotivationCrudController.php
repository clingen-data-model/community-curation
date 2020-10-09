<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\MotivationRequest as StoreRequest;
// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\MotivationRequest as UpdateRequest;
use App\Motivation;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\CrudPanel;

/**
 * Class MotivationCrudController.
 *
 * @property CrudPanel $crud
 */
class MotivationCrudController extends CrudController
{
    public function setup()
    {
        $this->crud->setModel(Motivation::class);
        $this->crud->setRoute(config('backpack.base.route_prefix').'/motivation');
        $this->crud->setEntityNameStrings('motivation', 'motivations');

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

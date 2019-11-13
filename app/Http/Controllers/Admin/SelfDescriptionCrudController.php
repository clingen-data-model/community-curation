<?php

namespace App\Http\Controllers\Admin;

use App\SelfDescription;

// VALIDATION: change the requests to match your own file names if you need form validation
use Backpack\CRUD\CrudPanel;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use App\Http\Requests\SelfDescriptionRequest as StoreRequest;
use App\Http\Requests\SelfDescriptionRequest as UpdateRequest;

/**
 * Class SelfDescriptionCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class SelfDescriptionCrudController extends CrudController
{
    public function setup()
    {
        $this->crud->setModel(SelfDescription::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/self-description');
        $this->crud->setEntityNameStrings('self-description', 'self-descriptions');

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

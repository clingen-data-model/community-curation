<?php

namespace App\Http\Controllers\Admin;

use App\Gene;

// VALIDATION: change the requests to match your own file names if you need form validation
use Backpack\CRUD\CrudPanel;
use App\Http\Requests\GeneRequest as StoreRequest;
use App\Http\Requests\GeneRequest as UpdateRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;

/**
 * Class GeneCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class GeneCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel(Gene::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/gene');
        $this->crud->setEntityNameStrings('gene', 'genes');
        $this->crud
            ->orderBy('symbol', 'asc');

        $this->configureFields();
        $this->configureColumns();
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

    private function configureColumns()
    {
        $this->crud->removeColumn('protocol_path');
    }
    

    private function configureFields()
    {
        $this->crud->setFromDb();
        $this->crud->addField([
            'name' => 'protocol_path',
            'type' => 'upload',
            'upload' => true,
            'disk' => 'public'
        ]);
        $this->crud->removeField('protocol_filename');

        // add asterisk for fields that are required in GeneRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }
}
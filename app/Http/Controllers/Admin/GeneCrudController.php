<?php

namespace App\Http\Controllers\Admin;

use App\Gene;
// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\GeneRequest as StoreRequest;
use App\Http\Requests\GeneRequest as UpdateRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\CrudPanel;

/**
 * Class GeneCrudController.
 *
 * @property CrudPanel $crud
 */
class GeneCrudController extends CrudController
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
        $this->crud->setModel(Gene::class);
        $this->crud->setRoute(config('backpack.base.route_prefix').'/gene');
        $this->crud->setEntityNameStrings('gene', 'genes');
        $this->crud
            ->orderBy('symbol', 'asc');

        $this->configureFields();
        $this->configureColumns();
    }

    private function configureColumns()
    {
        $this->crud->removeColumns(['protocol_path', 'hypothesis_group_url']);
        $this->crud->modifyColumn('hgnc_id', ['label' => 'HGNC ID']);
    }

    private function configureFields()
    {
        $this->crud->setFromDb();
        $this->crud->modifyField('symbol', ['label' => 'Genes']);
        $this->crud->removeField('hgnc_id');
        $this->crud->modifyField('hypothesis_group_url', ['type' => 'url']);
        $this->crud->modifyField('protocol_path', [
            'label' => 'Protocol File',
            'type' => 'upload',
            'upload' => true,
            'disk' => 'public',
        ]);
        $this->crud->removeField('protocol_filename');

        // add asterisk for fields that are required in GeneRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
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

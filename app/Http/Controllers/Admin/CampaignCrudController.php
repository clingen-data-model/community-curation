<?php

namespace App\Http\Controllers\Admin;

use App\Campaign;
use App\Http\Requests\CampaignRequest as StoreRequest;
use App\Http\Requests\CampaignRequest as UpdateRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\CrudPanel;

/**
 * Class CampaignCrudController.
 *
 * @property CrudPanel $crud
 */
class CampaignCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;

    public function setup()
    {
        $this->crud->setModel(Campaign::class);
        $this->crud->setRoute(config('backpack.base.route_prefix').'/campaign');
        $this->crud->setEntityNameStrings('campaign', 'campaigns');

        $this->crud->setFromDb();

        $this->crud->addField([
            'name' => 'starts_at',
            'type' => 'date_picker',
            'format' => 'YYYY-MM-DD',
        ]);
        $this->crud->addField([
            'name' => 'ends_at',
            'type' => 'date_picker',
            'format' => 'YYYY-MM-DD',
        ]);

        $this->crud->addColumns([
            [
                'name' => 'starts_at',
                'type' => 'date',
                'format' => 'YYYY-MM-DD',
            ],
            [
                'name' => 'ends_at',
                'type' => 'date',
                'format' => 'YYYY-MM-DD',
            ],
        ]);

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

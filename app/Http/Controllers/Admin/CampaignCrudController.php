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

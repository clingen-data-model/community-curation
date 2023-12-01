<?php

namespace App\Http\Controllers\Admin;

use App\Campaign;
use App\Http\Requests\CampaignRequest as StoreRequest;
use App\Http\Requests\CampaignRequest as UpdateRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\ReorderOperation;
use Backpack\CRUD\CrudPanel;
use Illuminate\Support\Facades\DB;

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
    use ReorderOperation;

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
                'name' => 'display_order',
                'type' => 'integer',
            ],
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

        $this->crud->removeField('display_order', 'created_at', 'updated_at');

        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        $this->crud->removeButton('delete');
        $this->crud
            ->orderBy('display_order')
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

    protected function setupReorderOperation()
    {
        $this->crud->set('reorder.label', 'name');
        $this->crud->set('reorder.max_level', 1);
    }

    public function saveReorder()
    {
        $this->crud->hasAccessOrFail('reorder');

        $allEntries = \Request::input('tree');

        DB::beginTransaction();
        foreach ($allEntries as $idx => $entry) {
            dump($entry['item_id']);
            if (is_null($entry['item_id'])) {
                continue;
            }
            Campaign::find($entry['item_id'])
                ->update([
                    'display_order' => $idx,
                ]);
        }
        DB::commit();

        return 'success for '.count($allEntries).' items';
    }
}

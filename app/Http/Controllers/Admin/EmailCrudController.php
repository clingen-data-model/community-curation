<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
// VALIDATION: change the requests to match your own file names if you need form validation
use Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
use Backpack\CRUD\CrudPanel;
use App\DbMailLog\DbMailLogProvider;

/**
 * Class EmailCrudController.
 *
 * @property CrudPanel $crud
 */
class EmailCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use ShowOperation {
        show as traitShow;
    }

    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel(DbMailLogProvider::getEmailLogEntryClass());
        $this->crud->setRoute(config('backpack.base.route_prefix').'/email');
        $this->crud->setEntityNameStrings('email', 'email');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        $this->crud->allowAccess('show');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('delete');
        $this->crud->denyAccess('create');
        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

        $this->crud->removeColumns(['cc', 'bcc', 'reply_to', 'sender']);
        $this->crud->setColumnsDetails(['to'], ['type' => 'json_email']);
        $this->crud->setColumnsDetails(['from'], ['type' => 'json_email']);
        $this->crud->addColumn(['type' => 'datetime', 'name' => 'created_at', 'label' => 'Sent'])->makeFirstColumn();

        $this->crud->orderBy('created_at', 'DESC');
    }

    public function show($id)
    {
        $content = $this->traitShow($id);
        foreach ($content->entry->getAttributes() as $key => $value) {
            if (is_null($content->entry->{$key})) {
                $this->crud->removeColumn($key);
            }
        }

        return $content;
    }
}

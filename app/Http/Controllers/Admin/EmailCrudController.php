<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
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
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/email');
        $this->crud->setEntityNameStrings('email', 'emails');

        $this->crud->allowAccess('show');
        $this->crud->denyAccess(['update', 'delete', 'create']);

        // Define columns manually so we can control searchability
        $this->crud->addColumn([
            'name'  => 'created_at',
            'type'  => 'datetime',
            'label' => 'Sent',
        ])->makeFirstColumn();

        $this->crud->addColumn([
            'name'  => 'subject',
            'type'  => 'text',
            'label' => 'Subject',
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhere('subject', 'like', "%{$searchTerm}%");
            },
        ]);

        $this->crud->addColumn([
            'name'  => 'from',
            'type'  => 'json_email',
            'label' => 'From',
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereRaw('LOWER(`from`) LIKE LOWER(?)', ["%{$searchTerm}%"]);
            },
        ]);

        $this->crud->addColumn([
            'name'  => 'to',
            'type'  => 'json_email',
            'label' => 'To',
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereRaw('LOWER(`to`) LIKE LOWER(?)', ["%{$searchTerm}%"]);
            },
        ]);

        // Body: hidden from table, but searchable
        $this->crud->addColumn([
            'name'              => 'body',
            'type'              => 'textarea',
            'label'             => 'Body',
            'visibleInTable'    => false,
            'visibleInModal'    => false,
            'visibleInExport'   => false,
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereRaw('LOWER(`body`) LIKE LOWER(?)', ["%{$searchTerm}%"]);
            },
        ]);

        $this->crud->orderBy('created_at', 'DESC');
    }

    public function show($id)
    {
        $content = $this->traitShow($id);
        foreach ($content->entry->getAttributes() as $key => $value) {
            if (is_null($value)) {
                $this->crud->removeColumn($key);
            }
        }

        return $content;
    }
}

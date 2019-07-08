<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\CrudPanel;

// VALIDATION: change the requests to match your own file names if you need form validation
use Spatie\Permission\Models\Role;
use App\Http\Requests\UserRequest as StoreRequest;
use App\Http\Requests\UserRequest as UpdateRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Spatie\Permission\Models\Permission;

/**
 * Class UserCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class UserCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\User');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/user');
        $this->crud->setEntityNameStrings('user', 'users');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

        // add asterisk for fields that are required in UserRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        $this->crud->addFields([
            [
                'label' => "Roles",
                'type' => 'select2_multiple',
                'name' => 'roles',
                'entity' => 'roles',
                'attribute' => 'name',
                'model' => Role::class,
                'pivot' => true,
            ],
            [
                'label' => "Additonal Permissions",
                'type' => 'select2_multiple',
                'name' => 'permissions',
                'entity' => 'Permissions',
                'attribute' => 'name',
                'model' => Permission::class,
                'pivot' => true,
            ],
        ], 'both');

        if (!\Auth::user()->can('create users')) {
            $this->crud->RemoveButton('create');
        }

        if (!\Auth::user()->can('update users')) {
            $this->crud->RemoveButtonFromStack('update', 'line');
        }

        if (!\Auth::user()->can('delete users')) {
            $this->crud->RemoveButtonFromStack('delete', 'line');
        }

        if (\Auth::user()->canImpersonate()) {
            $this->crud->addButtonFromView('line', 'impersonate-users', 'impersonate_user', 'end'); // add a button; possible types are: view, model_functiona
        }
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

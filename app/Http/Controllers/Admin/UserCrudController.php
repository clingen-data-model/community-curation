<?php

namespace App\Http\Controllers\Admin;

use App\Country;
// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\UserRequest as StoreRequest;
use App\Http\Requests\UserRequest as UpdateRequest;
use App\Surveys\SurveyOptions;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\CrudPanel;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * Class UserCrudController.
 *
 * @property CrudPanel $crud
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
        $this->crud->setRoute(config('backpack.base.route_prefix').'/user');
        $this->crud->setEntityNameStrings('user', 'users');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addClause('whereDoesntHave', 'roles', function ($query) {
            $query->where('name', 'volunteer');
        });

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

        // add asterisk for fields that are required in UserRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        $this->crud->removeFields([
            'password',
            'volunteer_type_id',
            'volunteer_status_id',
            'address',
            'last_logged_in_at',
            'last_logged_out_at',
            'orcid_id',
            'hypothesis_id',
            '',
        ]);

        $this->crud->addFields([
            [
                'label' => 'Roles',
                'type' => 'select2_multiple',
                'name' => 'roles',
                'entity' => 'roles',
                'attribute' => 'name',
                'model' => Role::class,
                'pivot' => true,
            ],
            [
                'label' => 'Additonal Permissions',
                'type' => 'select2_multiple',
                'name' => 'permissions',
                'entity' => 'Permissions',
                'attribute' => 'name',
                'model' => Permission::class,
                'pivot' => true,
            ],
        ], 'both');
        $this->crud->modifyField('country_id', ['type' => 'select2', 'name' => 'country_id', 'entity' => 'country', 'attribute' => 'name', 'model' => Country::class]);

        $this->crud->modifyField(
            'timezone',
            [
                'type' => 'select2_from_array',
                'name' => 'timezone',
                'label' => 'Timezone (Select city closest to you)',
                'options' => collect((new SurveyOptions())->timezones())->pluck('name', 'id')->toArray(),
            ]
        );

        $this->crud->removeColumns([
            'volunteer_type_id',
            'volunteer_status_id',
            'street1', 'street2',
            'city',
            'zip',
            'country_id',
            'state',
            'last_logged_in_at',
            'last_logged_out_at',
            'institution',
            'hypothesis_id',
            'orcid_id',
        ]);

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

<?php

namespace App\Http\Controllers\Admin;

use App\Country;
use App\Http\Requests\VolunteerAdminRequest as StoreRequest;
use App\Http\Requests\VolunteerAdminRequest as UpdateRequest;
use App\Surveys\SurveyOptions;
use App\User;
use App\VolunteerStatus;
use App\VolunteerType;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\CrudPanel;
use Illuminate\Http\Request;

/**
 * Class VolunteerCrudController.
 *
 * @property CrudPanel $crud
 */
class VolunteerCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation { store as traitStore; }
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;

    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel(User::class);
        $this->crud->setRoute(config('backpack.base.route_prefix').'/volunteer');
        $this->crud->setEntityNameStrings('volunteer', 'volunteers');

        $this->crud->allowAccess(['list', 'create', 'delete', 'show']);
        $this->crud->denyAccess(['update']);

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addClause('whereHas', 'roles', function ($query) {
            $query->where('name', 'volunteer');
        });

        // TODO: setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

        // add asterisk for fields that are required in VolunteerRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        $this->crud->modifyField('volunteer_type_id', [
            'label' => 'Volunteer Type',
            'type' => 'select2',
            'name' => 'volunteer_type_id',
            'entity' => 'volunteerType',
            'model' => VolunteerType::class,
            'attribute' => 'name',
        ]);

        $this->crud->modifyField('volunteer_status_id', [
            'label' => 'Volunteer Status',
            'type' => 'select2',
            'name' => 'volunteer_status_id',
            'entity' => 'volunteerStatus',
            'model' => VolunteerStatus::class,
            'attribute' => 'name',
        ]);

        $this->crud->modifyField(
            'country_id',
            [
                'type' => 'select2',
                'name' => 'country_id',
                'entity' => 'country',
                'attribute' => 'name',
                'model' => Country::class,
            ]
        );

        $this->crud->modifyField(
            'timezone',
            [
                'type' => 'select2_from_array',
                'name' => 'timezone',
                'label' => 'Timezone (Select city closest to you)',
                'options' => collect((new SurveyOptions())->timezones())->pluck('name', 'id')->toArray(),
            ],
        );

        $this->crud->modifyField(
            'already_member_cgs',
            [
                'name' => 'already_member_cgs',
                'label' => 'Groups before C3',
                'type' => 'filterable_multicheck',
            ],
        );

        $this->crud->removeFields(['password', 'last_logged_in_at', 'last_logged_out_at']);

        $this->crud->with('roles');
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(StoreRequest::class);
    }

    protected function setupUpdateOperation()
    {
        $this->crud->setValidation(UpdateRequest::class);
    }

    public function setupListOperation(Request $request)
    {
        if (! $request->user()->can('create users')) {
            $this->crud->RemoveButton('create');
        }

        // remove update button so we don't have to support member eps in multiple places.
        // if (!$request->user()->can('update users')) {
        //     $this->crud->RemoveButtonFromStack('update', 'line');
        // }
        $this->crud->removeButton('update');

        if (! $request->user()->can('delete users')) {
            $this->crud->RemoveButtonFromStack('delete', 'line');
        }

        if ($request->user()->canImpersonate()) {
            $this->crud->addButtonFromView('line', 'impersonate-users', 'impersonate_user', 'end'); // add a button; possible types are: view, model_functiona
        }

        $this->crud->addButtonFromView('line', 'view_volunteer', 'view_volunteer', 'beginning');

        $this->crud->removeColumns([
            'password',
            'last_logged_in_at',
            'last_logged_out_at',
            'orcid_id',
            'hypothesis_id',
            'zip',
            'country_id',
            'timezone',
            'street1',
            'street2',
            'city',
            'state',
            'zip',
            'institution',
            'volunteer_status_id',
            'volunteer_type_id',
        ]);

        $this->crud->addColumn([
            'label' => 'ID',
            'type' => 'integer',
            'name' => 'id',
        ])
            ->makeFirstColumn();

        $this->crud->addColumns([
            [
                'label' => 'Type',
                'type' => 'relationship',
                'name' => 'volunteerType',
            ],
            [
                'label' => 'Status',
                'type' => 'relationship',
                'name' => 'volunteerStatus',
            ],
        ]);
    }
}

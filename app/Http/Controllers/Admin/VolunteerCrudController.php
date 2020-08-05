<?php

namespace App\Http\Controllers\Admin;

use App\User;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Country;
use App\VolunteerType;
use App\VolunteerStatus;
use Backpack\CRUD\CrudPanel;
use App\Surveys\SurveyOptions;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use App\Http\Requests\VolunteerAdminRequest as StoreRequest;
use App\Http\Requests\VolunteerAdminRequest as UpdateRequest;

/**
 * Class VolunteerCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class VolunteerCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel(User::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/volunteer');
        $this->crud->setEntityNameStrings('volunteer', 'volunteers');

        $this->crud->allowAccess(['list', 'create', 'update', 'delete', 'show']);

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

        $this->crud->addButtonFromView('line', 'view_volunteer', 'view_volunteer', 'beginning');

        $this->crud->addFields([
            [
                'label' => 'Volunteer Type',
                'type' => 'select2',
                'name' => 'volunteer_type_id',
                'entity' => 'volunteerType',
                'model' => VolunteerType::class,
                'attribute' => 'name'
            ],
            [
                'label' => 'Volunteer Status',
                'type' => 'select2',
                'name' => 'volunteer_status_id',
                'entity' => 'volunteerStatus',
                'model' => VolunteerStatus::class,
                'attribute' => 'name'
            ],
        ]);

        $this->crud->modifyField(
            'country_id',
            [
                'type' => 'select2',
                'name'=>'country_id',
                'entity'=>'country',
                'attribute'=>'name',
                'model' => Country::class
            ]
        );

        $this->crud->modifyField(
            'timezone',
            [
                'type' => 'select2_from_array',
                'name'=>'timezone',
                'label' => 'Timezone (Select city closest to you)',
                'options' => collect((new SurveyOptions())->timezones())->pluck('name', 'id')->toArray()
            ]
        );


        $this->crud->removeFields(['password', 'last_logged_in_at', 'last_logged_out_at',]);

        $this->crud->removeColumns(['password', 'last_logged_in_at', 'last_logged_out_at', 'orcid_id', 'hypothesis_id', 'zip', 'country_id', 'timezone', 'street1', 'street2', 'city', 'state', 'zip', 'institution']);
        $this->crud->addColumns([
            [
                'label' => 'Type',
                'type' => 'select',
                'name' => 'volunteer_type_id',
                'entity' => 'volunteerType',
                'model' => VolunteerType::class,
                'attribute' => 'name'
            ],
            [
                'label' => 'Status',
                'type' => 'select',
                'name' => 'volunteer_status_id',
                'entity' => 'volunteerStatus',
                'model' => VolunteerStatus::class,
                'attribute' => 'name'
            ],
        ]);

        $this->crud->with('roles');
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        $this->crud->entry->assignRole('volunteer');
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

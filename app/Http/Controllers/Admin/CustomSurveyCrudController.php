<?php

namespace App\Http\Controllers\Admin;

use App\CurationGroup;
use App\Http\Requests\CustomSurveyRequest;
use App\VolunteerType;
use Attribute;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class CustomSurveyCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CustomSurveyCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\CustomSurvey::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/custom-survey');
        CRUD::setEntityNameStrings('custom survey', 'custom surveys');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        // CRUD::setFromDb(); // columns

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */

        $this->crud->addColumn([
            'type' => 'select',
            'label' => 'Curation Group',
            'name' => 'curation_group_id',
            'model' => CurationGroup::class,
            'attribute' => 'name',
            'entity' => 'curationGroup'
         ]);
        $this->crud->addColumn([
            'type' => 'select',
            'label' => 'Volunteer Type',
            'name' => 'volunteer_type_id',
            'model' => VolunteerType::class,
            'attribute' => 'name',
            'entity' => 'volunteerType'
         ]);
        $this->crud->addColumn([
            'name' => 'survey_url',
            'label' => 'URL',
            'type' => 'text',
            'wrapper' => [
                'href' => function ($crud, $col, $entry, $related_key) {
                    return $entry->survey_url;
                }
            ]
        ]);
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(CustomSurveyRequest::class);

        CRUD::setFromDb(); // fields

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number']));
         */
        $this->crud->modifyField('curation_group_id', [
            'type' => 'select2',
            'label' => 'Curation Group',
            'name' => 'curation_group_id',
            'model' => CurationGroup::class,
            'attribute' => 'name',
        ]);
        $this->crud->modifyField('volunteer_type_id', [
            'type' => 'select2',
            'name' => 'volunteer_type_id',
            'model' => VolunteerType::class,
            'attribute' => 'name',
            'lable' => 'Volunteer Type'
        ]);
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    protected function setupShowOperation()
    {
        $this->crud->set('show.setFromDb', false);

        $this->crud->addColumn([
            'name' => 'group_name',
            'label' => 'Curation group',
            'type' => 'text'
        ]);
        $this->crud->addColumn([
            'name' => 'volunteer_type_name',
            'label' => 'Volunteer Type',
            'type' => 'text'
        ]);
        $this->crud->addColumn([
            'name' => 'survey_url',
            'label' => 'URL',
            'type' => 'link'
        ]);
    }
}

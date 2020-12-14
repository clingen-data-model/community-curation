<?php

return [
    'surveysPath' => base_path('resources/surveys'),
    'rulesPath' => app_path('Surveys'),
    'rulesNamespace' => 'App\\Surveys\\',
    'routeGroup' => ['middleware' => ['web']],
    'cacheDocuments' => (env('APP_DEBUG')) ? false : true,
    'chromeTemplate' => 'layouts.app',
    'editAfterFinalized' => false,
    'customTemplatePath' => base_path('resources/views'),
    'refusedLabel' => 'Refused',
    'refusedValue' => -77,
    'autosave' => [
        'enabled' => false,
        'frequency' => 10000, // time in miliseconds
        'notify' => true,
        'notify_time' => 2500,
    ],
    'default_templates' => [
        'page' => 'containers.page.page',
        'date' => 'questions.date.date',
        'duration' => 'questions.number.duration',
        'multiple_choice' => [
            'single' => 'questions.multiple_choice.radio_group_vertical',
            'multi' => 'questions.multiple_choice.checkbox_group',
        ],
        'number' => 'questions.number.number',
        'numeric_scale' => 'questions.number.numeric_scale',
        'question' => 'questions.text.default_text',
        'time' => 'questions.time.time',
    ],
    'validation_messages' => [
        'curation_activity_2.different' => 'You have already selected this curation activity',
        'curation_activity_3.different' => 'You have already selected this curation activity',
        'volunteer_type.required' => 'Please indicate the level at which you\'d like to volunteer',
        // 'required' => 'required*'
        // 'my_field.validation_rule' => 'My custom mssage',
    ],
    'datasource_cachelife' => env('SURVEY_DATASOURCR_CACHE_LIFE', 1200),
    'bindings' => [
        'models' => [
            'Survey' => App\Survey::class,
            'Response' => App\SurveyResponse::class,
        ],
    ],
];

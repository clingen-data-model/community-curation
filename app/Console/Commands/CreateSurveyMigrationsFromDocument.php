<?php

namespace App\Console\Commands;

use Sirs\Surveys\Console\CreateSurveyMigrationsFromDocument as BaseCommand;

class CreateSurveyMigrationsFromDocument extends BaseCommand
{
    public function getDefaultText()
    {
        // The pinned SIRS template uses Doctrine integration removed in Laravel 11.
        return str_replace(
            "Schema::getConnection()->getDoctrineSchemaManager()->listTableIndexes('DummyTable')",
            "array_column(Schema::getIndexes('DummyTable'), null, 'name')",
            parent::getDefaultText()
        );
    }
}

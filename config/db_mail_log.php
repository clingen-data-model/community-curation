<?php

use App\EmailLogEntry;

return [
    /**
     * If enabled will log mail to the database
     */
    'enabled' => env('DB_MAIL_LOG_ENABLED', true),

    /**
     * How long to keep logged email around in days
     */
    'delete_records_older_than_days' => 365,

    /**
     * The model used for an email log entry
     * Should implement \Berglab\DbMailLog\Models\Email
     * Should extend \Illuminate\Database\Eloquent\Model
     */
    'email_model' => EmailLogEntry::class,

    /**
     * Name of the table emails are stored in; Used by email model
     */
    'table_name' => 'mail_log',

    /**
     * Database connection to use;
     */
    'database_connection' => env('DB_MAIL_LOG_CONNECTON', null),
];

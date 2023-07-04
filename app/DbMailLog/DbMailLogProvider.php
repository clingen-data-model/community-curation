<?php

namespace App\DbMailLog;

use App\DbMailLog\Contracts\Email as EmailContract;
use App\DbMailLog\Listeners\StoreMailInDatabase;
use App\DbMailLog\Models\Email;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Mail\Events\SentMessage;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
//forcd git

class DbMailLogProvider extends ServiceProvider
{
    public function boot()
    {
        if (! class_exists('CreateEmailLogTable')) {
            $timestamp = date('Y_m_d_His', time());

            $this->publishes([
                __DIR__.'/../migrations/create_email_log_table.php' => database_path("/migrations/{$timestamp}_create_email_log_table.php"),
            ], 'migrations');
        }

        $this->registerMailLogging();
    }

    public function register()
    {
    }

    public static function getEmailLogEntryClass()
    {
        $model = config('db_mail_log.email_model') ?? Email::class;

        if (! is_a($model, EmailContract::class, true) || ! is_a($model, Model::class, true)) {
            throw new Exception('Invalid Email Log Entry class.  It must implement Yugen\\DbMailLog\\Contracts\\Email and exted Illuminate\Database\Eloquent\Model');
        }

        return $model;
    }

    public static function getEmailInstance($attributes = [])
    {
        $class = self::getEmailLogEntryClass();

        return new $class($attributes);
    }

    private function registerMailLogging()
    {
        Event::listen(SentMessage::class, StoreMailInDatabase::class);
    }
}

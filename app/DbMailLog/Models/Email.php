<?php

namespace App\DbMailLog\Models;

use App\DbMailLog\Contracts\Email as EmailContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Email extends Model implements EmailContract
{
    protected $guarded = [];

    protected $casts = [
        'from' => 'array',
        'sender' => 'array',
        'to' => 'array',
        'cc' => 'array',
        'bcc' => 'array',
        'reply_to' => 'array',
    ];

    public function __construct(array $attributes = [])
    {
        if (! isset($this->connection)) {
            $connection = config('db_mail_log.database_connection') ?? config('database.default');
            $this->setConnection($connection);
        }

        if (! isset($this->table)) {
            $this->setTable(config('db_mail_log.table_name'));
        }

        parent::__construct($attributes);
    }

    public function scopeFrom($query, $from): Builder
    {
        return $query->where('from_address', $from);
    }

    public function scopeLikeFrom($query, $from): Builder
    {
        return $query->where('from_address', 'LIKE', '%'.$from.'%');
    }

    public function scopeTo($query, $to): Builder
    {
        return $query->where('to', $to);
    }

    public function scopeLikeTo($query, $to): Builder
    {
        return $query->where('to', 'LIKE', '%'.$to.'%');
    }
}

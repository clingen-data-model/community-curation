<?php

namespace App;

use App\Traits\TranscodesHtmlToMarkdown;
use Carbon\Carbon;
use Spatie\CalendarLinks\Link;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrainingSession extends Model
{
    use SoftDeletes;
    use TranscodesHtmlToMarkdown;

    public $fillable = [
        'topic_type',
        'topic_id',
        'starts_at',
        'ends_at',
        'url',
        'invite_message',
        'notes'
    ];

    public $dates = [
        'starts_at',
        'ends_at'
    ];

    public function topic()
    {
        return $this->morphTo();
    }

    public function attendees()
    {
        return $this->belongsToMany(User::class, 'training_session_user', 'training_session_id', 'user_id');
    }

    public function scopeFuture($query)
    {
        return $query->where('starts_at', '>=', Carbon::now());
    }

    public function scopePast($query)
    {
        return $query->where('starts_at', '<=', Carbon::now());
    }

    public function getCalendarLinksAttribute()
    {
        $link = $this->getCalendarLink();
        
        return [
            'Apple & Outlook' => $link->ics(),
            'Google' => $link->google(),
            'Web-based Outlook' => $link->webOutlook(),
            'Yahoo' => $link->yahoo(),
        ];
    }

    public function getTitleAttribute()
    {
        return 'ClinGen ' . $this->topic->name . ' Training';
    }

    public function getDescriptionAttribute()
    {
        return 'Training for ClinGen ' . $this->topic->name;
    }
    
    public function getIcsFilePath()
    {
        if (!file_exists(storage_path('/app/calendar_ics'))) {
            mkdir(storage_path('/app/calendar_ics'));
        }
        $filepath = storage_path('app/calendar_ics/'.$this->generateEventUid().'.ics');
        if (!file_exists($filepath) || filectime($filepath) < $this->updated_at->timestamp) {
            $fh = fopen($filepath, 'w');
            fwrite($fh, $this->getIcsString());
            fclose($fh);
        }
        return $filepath;
    }

    private function getCalendarLink()
    {
        return Link::create(
            $this->title,
            $this->starts_at->toDateTime(),
            $this->ends_at->toDateTime()
        )
        ->description($this->description)
        ->address($this->url);
    }

    public function getIcsString()
    {
        $dateTimeFormat = 'e:Ymd\THis';
        $UID = $this->generateEventUid();

        $url = [
            'BEGIN:VCALENDAR',
            'VERSION:2.0',
            'BEGIN:VEVENT',
            'UID:'.$UID,
            'SUMMARY:'.$this->escapeString($this->title),
            'DTSTART;TZID='.$this->starts_at->format($dateTimeFormat),
            'DTEND;TZID='.$this->ends_at->format($dateTimeFormat),
            'DESCRIPTION:'.$this->escapeString($this->description),
            'LOCATION:'.$this->escapeString($this->url),
            'END:VEVENT',
            'END:VCALENDAR',
        ];

        $string = implode("\r\n", $url);

        return $string;
    }

    public function setInviteMessageAttribute($value)
    {
        $this->attributes['invite_message'] = $this->htmlToMarkdown($value);
    }
    
    public function getInviteMessageAttribute()
    {
        return $this->markdownToHtml($this->attributes['invite_message']);
    }

    /** @see https://tools.ietf.org/html/rfc5545.html#section-3.3.11 */
    protected function escapeString(string $field): string
    {
        return addcslashes($field, "\r\n,;");
    }

    /** @see https://tools.ietf.org/html/rfc5545#section-3.8.4.7 */
    protected function generateEventUid(): string
    {
        return md5($this->starts_at->format("Y-m-d\TH:i:sP").$this->ends_at->format("Y-m-d\TH:i:sP").$this->title.$this->url);
    }
}

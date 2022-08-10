<p>Hi {{$volunteer->first_name}},</p>

<p>
    This email is to inform you that a live training session for {{$trainingSession->topic->name}} will be held on 
    {{$trainingSession->starts_at->addSeconds($timezone->getOffset($trainingSession->starts_at))->format('l, F j, Y \a\t g:i a')}} {{strtoupper($timezone->getAbbr())}}.
</p>
 
<p>Attendance for this live training session is required for volunteer training completion and curation assignment.</p>
 
<blockquote>
    {!! $trainingSession->invite_message !!}
</blockquote>

<p>If you have any questions or concerns, please contact us at <a href="mailto:volunteer@clinicalgenome.org">volunteer@clinicalgenome.org</a>.</p>
 
<p>Thank you for your participation,</p>
 
<p>The ClinGen Community Curation (C3) Working Group.</p>

<br>
Add the training session to your calendar:
<ul>
    @foreach ($trainingSession->calendarLinks as $label => $url)
        @if ($label != 'Apple & Outlook')
            <li><a href="{{$url}}">{{$label}}</a></li>
        @endif
    @endforeach
</ul>
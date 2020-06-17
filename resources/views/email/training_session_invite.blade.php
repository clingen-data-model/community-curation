<p>Hi {{$volunteer->first_name}},</p>

<p>
    You are invited to join our {{$trainingSession->topic->name}} training 
    on {{$trainingSession->starts_at->format('l, F j, Y \a\t j:i a e')}}.  
</p>

<p>
    Training will take place via an online at 
    <a href="{{$trainingSession->url}}">{{$trainingSession->url}}</a>.
</p>

<p>
    You need to complete this training to start volunteering with the Clinical Genome's community curation effort.
</p>

<p>
    If you have any questions please reply to this email and we'll get your sorted out.
</p>

{!! $trainingSession->invite_message !!}

<p>
    We look forward to seeing you then!
</p>

<p>
Cheers!
</p>

<br>
<div>
    Add the training session to your calendar:
    <ul>
        @foreach ($trainingSession->calendarLinks as $label => $url)
            @if ($label != 'Apple & Outlook')
                <li><a href="{{$url}}">{{$label}}</a></li>
            @endif
        @endforeach
    </ul>
</div>
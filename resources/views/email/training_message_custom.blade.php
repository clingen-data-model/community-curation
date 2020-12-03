{!! $body !!}

<hr>
You are receiving this email b/c you have been invited to attend a ClinGen training session for 
{{$trainingSession->topic->name}} 
on 
{{$trainingSession->starts_at->addSeconds($timezone->getOffset($trainingSession->starts_at)) ->format('l, F j, Y \a\t g:i a')}}
<p>Hi {{$recipient->name}},</p>

<p>
    You have a new attestation for {{ $attestation->aptitude->name }} ready to be signed.  
</p>

<p>
    <a href="{{url('/attestations/'.$attestation->id.'/edit')}}">Complete your attestation</a>
</p>
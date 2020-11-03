<p>
    Hi {{$recipient->name}},
</p>

<p>
    Thank you for participating in ClinGen Community Curation. 
    Based on either your preferred activity or group availability you have been assigned to {{$userAptitude->aptitude->name}}.
    Prior to being assigned to a specific group or curation group, 
    please visit <a href="{{$userAptitude->aptitude->training_materials_url}}">{{$userAptitude->aptitude->training_materials_url}}</a> 
    to view details for the first part of your training.
</p> 

<p>
    Thank you,
    <br>
    The ClinGen Community Curation Team
</p>   

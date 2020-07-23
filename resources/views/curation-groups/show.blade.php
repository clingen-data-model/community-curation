@extends('layouts.app')

@section('content')
    <curation-group-detail :initial-group="{{$curationGroup}}">
        <template v-slot:default="{group}">
            <section class="lead">
                <dl class="row mb-0">
                    <dt class="col-md-4">Curation activity</dt>
                    <dd class="col-md-8">@{{group.curation_activity.name}}</dd>
                    
                    <dt class="col-md-4">Working group</dt>
                    <dd class="col-md-8">@{{group.working_group ? group.working_group.name : '--'}}</dd>
                    
                    <dt class="col-md-4">Accepting Vollunteers</dt>
                    <dd class="col-md-8">@{{group.accepting_volunteers ? 'Yes' : 'No'}}</dd>

                    <dt class="col-md-4">Url</dt>
                    <dd class="col-md-8">@{{group.url ? group.url : '--' }}</dd>
                </dl>
            </section>
        </template>
    </curation-group-detail>
@endsection
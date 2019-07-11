@extends('layouts.survey')

@section('content')
<br>
<form class="sirs-survey" method="POST" name="{{$context['survey']['name']}}-{{$renderable->name}}" novalidate>
  <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
  <div class="panel panel-default card">
    <div class="panel-heading card-header">
      @if (!\Auth::guest() && \Auth::user()->can('view survey data'))
        <div class="pull-right float-right" style="margin-top: 4px;">
          
          @if($context['response']->finalized_at)
            <small class="finalized-response-warning">
              <strong>This response was finalized on {{$context['response']->finalized_at->format('m/d/Y')}}</strong>
            </small>
          @endif        

          <a href="{{route('surveys.responses.show', [$context['survey']['object']->slug, $context['response']->id])}}" class="btn btn-sm btn-default">View Data</a>
        </div>
      @endif
      <h4>
        @if ($context['response']->respondent)
          {{$context['response']->respondent->full_name ?? 'Respondent:'.$context['response']->respondent->id}} 
          - 
        @endif
        @if ($renderable->title)
          {{$renderable->title}}
        @endif
      </h4>
    </div>

    <div class="panel-body card-body">
      @if($renderable->contents)
        @foreach($renderable->contents as $content)
          {!! $content->render($context) !!}
        @endforeach
      @else
        <div class="alert-danger">
          No contents found for this page!!
        </div>
      @endif
    </div>
    
    <div class="panel-footer card-footer">
      @if($context['survey']['currentPageIdx'] > 0)
        <button id="nav-prev" type="submit" name="nav" value="prev" class="btn btn-default">Back</button>
      @endif
      @if($context['survey']['currentPageIdx'] < ($context['survey']['totalPages']-1))
      <button id="nav-next" type="submit" name="nav" value="next" class="btn btn-primary">Next</button>
      @endif
      @if($context['survey']['currentPageIdx'] == ($context['survey']['totalPages']-1))
        <button id="nav-finalize" type="submit" name="nav" value="finalize" class="btn btn-primary">Finish &amp; Finalize</button>
      @endif
      <div id="save-buttons" class="pull-right float-right">
        @if(!isset($context['hideSave']) || !$context['hideSave'])
        <button id="nav-save" type="submit" name="nav" value="save" class="btn btn-default">Save</button>
        @endif
        @if(!isset($context['hideSaveExit']) || !$context['hideSaveExit'])
        <button id="nav-save-exit" type="submit" name="nav" value="save_exit" class="btn btn-default">Save &amp; exit</button>
        @endif
      </div>
    </div>
  </div>
</form>

<div class="alert alert-info notification d-none" id="flast-notification">Auto-saved at <span id="notification-time"></span>.</div>

<div style="display: none;">response_id: {{$context['response']->id}}</div>

<div class="text-muted">
  <small>
    {{ $context['survey']['title'] ?? ucwords($context['survey']['name'])}}        
    - {{$renderable->title}}
    version {{$context['survey']['version']}}
  </small>
</div>
@endsection

@push('scripts')
  @include('surveys::js.plugins')
  @include('surveys::js.autosave')
@endpush
@if ($crud->hasAccess('revisions') && count($entry->revisionHistory))
    <a href="{{ url($crud->route.'/'.$entry->getKey().'/revisions') }}" class="bp-crud btn btn-xs btn-default"><i class="fa fa-history"></i> {{ trans('backpack::crud.revisions') }}</a>
@endif

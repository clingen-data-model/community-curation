<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="nav-icon la la-dashboard"></i> <span>{{ trans('backpack::base.dashboard') }}</span></a></li>

@can('list users')
    <li class="nav-item"><a class="nav-link" href="{{ backpack_url('user') }}"><i class="nav-icon la la-user-plus"></i> <span>Users</span></a></li>
@endcan

@can('list users')
    <li class="nav-item"><a class="nav-link" href="{{ backpack_url('volunteer') }}"><i class="nav-icon la la-user"></i> <span>Volunteers</span></a></li>
@endcan

@can('list working-groups')
    <li class="nav-item"><a class="nav-link" href="{{ backpack_url('working-group') }}"><i class="nav-icon la la-group"></i> <span>Working groups</span></a></li>
@endcan

@can('list curation-groups')
    <li class="nav-item"><a class="nav-link" href="{{ backpack_url('curation-group') }}"><i class="nav-icon la la-group"></i> <span>Curation groups</span></a></li>
@endcan

@can('list volunteer-types')
    <li class="nav-item"><a class="nav-link" href="{{ backpack_url('volunteer-type') }}"><i class="nav-icon la la-list"></i> <span>Volunteer types</span></a></li>
@endcan

@can('list genes')
    <li class="nav-item"><a class="nav-link" href="{{ backpack_url('gene') }}"><i class="nav-icon la la-list"></i> <span>Genes</span></a></li>
@endcan

@can('list lookups')
    <hr style="margin-bottom: 0">
    <li class="nav-item" class class="nav-link"="header">Application Survey Options</li>
    <li class="nav-item"><a class="nav-link" href="{{ backpack_url('campaign') }}"><i class="nav-icon la la-list"></i> <span>Campaigns</span></a></li>
    <li class="nav-item"><a class="nav-link" href="{{ backpack_url('goal') }}"><i class="nav-icon la la-list"></i> <span>Goals</span></a></li>
    <li class="nav-item"><a class="nav-link" href="{{ backpack_url('interest') }}"><i class="nav-icon la la-list"></i> <span>Interests</span></a></li>
    <li class="nav-item"><a class="nav-link" href="{{ backpack_url('motivation') }}"><i class="nav-icon la la-list"></i> <span>Motivations</span></a></li>
    <li class="nav-item"><a class="nav-link" href="{{ backpack_url('self-description') }}"><i class="nav-icon la la-list"></i> <span>Self-descriptions</span></a></li>
@endcan

<hr>

<li class="nav-item"><a class="nav-link" href="{{ backpack_url('upload-category') }}"><i class="nav-icon la la-list"></i> <span>Upload Categories</span></a></li>

@if(\Auth::user()->can('create faq'))
<li class="nav-item">
    <a class="nav-link" href="{{backpack_url('faq')}}"><i class="nav-icon la la-question"></i><span>FAQ</span></a>
</li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('elfinder') }}"><i class="nav-icon la la-files-o"></i> <span>{{ trans('backpack::crud.file_manager') }}</span></a></li>
@endif

@if (\Auth::user()->can('view logs'))
    <li class="nav-item"><a class="nav-link" href="/admin/logs" target="logs"><i class="nav-icon la la-list"></i> <span>Logs</span></a></li>
@endif
{{-- @if(Auth::user()->hasPermissionTo('view email')) --}}
    <li class="nav-item"><a class="nav-link" href="{{ url(config('backpack.base.route_prefix').'/email') }}"><i class="nav-icon la la-file-o"></i> <span>Emails</span></a></li>
{{-- @endif --}}
{{-- @if(Auth::user()->hasPermissionTo('view notification')) --}}
    {{-- <li class="nav-item"><a class="nav-link" href="{{ url(config('backpack.base.route_prefix').'/notification') }}"><i class="nav-icon la la-file-o"></i> <span>Notifications</span></a></li> --}}
{{-- @endif --}}

<hr style="margin-bottom: 0">
<li class="nav-item">
    <a class="nav-link" href="{{ route('logout') }}"
        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
    >
        <i class="nav-icon la la-sign-out"></i><span>{{ __('Logout') }}</span>
    </a>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</li>
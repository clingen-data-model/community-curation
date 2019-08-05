<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<li><a href="{{ backpack_url('dashboard') }}"><i class="fa fa-dashboard"></i> <span>{{ trans('backpack::base.dashboard') }}</span></a></li>

{{-- @php dump(\Auth::user()->permissions); @endphp
@php dump(\Auth::user()->getPermissionsViaRoles()->pluck('name')); @endphp --}}

@can('list users')
    <li><a href="{{ backpack_url('user') }}"><i class="fa fa-user"></i> <span>Users</span></a></li>
@endcan

@can('list working-groups')
    <li><a href="{{ backpack_url('working-group') }}"><i class="fa fa-group"></i> <span>working-groups</span></a></li>
@endcan

@can('list expert-panels')
    <li><a href="{{ backpack_url('expert-panel') }}"><i class="fa fa-group"></i> <span>expert-panels</span></a></li>
@endcan

@if (\Auth::user()->hasRole('programmer'))
    <li><a href="/admin/logs"><i class="fa fa-list"></i> <span>Logs</span></a></li>
@endif

<li><a href="{{ backpack_url('elfinder') }}"><i class="fa fa-files-o"></i> <span>{{ trans('backpack::crud.file_manager') }}</span></a></li>
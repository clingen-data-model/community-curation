@canImpersonate
    @canBeImpersonated($entry)
        <a href="{{ route('impersonate', $entry->id) }}" class="btn btn-xs btn-default">
            <span class="fa fa-user-secret"></span>
            Impersonate
        </a>
    @endCanBeImpersonated
@endCanImpersonate
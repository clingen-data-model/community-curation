@canImpersonate
    @canBeImpersonated($entry)
    <a href="{{ route('impersonate', $entry->id) }}" class="btn btn-sm btn-link">
            <span class="fa fa-user-secret"></span>
            Impersonate
        </a>
    @endCanBeImpersonated
@endCanImpersonate
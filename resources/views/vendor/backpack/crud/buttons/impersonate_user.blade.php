@canImpersonate
    @canBeImpersonated($entry)
    <a href="{{ route('impersonate', $entry->id) }}" class="btn btn-sm btn-link">
            <span class="la la-user-secret"></span>
            Impersonate
        </a>
    @endCanBeImpersonated
@endCanImpersonate
@canImpersonate
    @canBeImpersonated($entry)
        <a href="{{ route('impersonate', $entry->id) }}" class="btn btn-xs btn-default">Impersonate</a>
    @endCanBeImpersonated
@endCanImpersonate
@if(!\Auth::guest() && \Auth::user()->canImpersonate() && !\Auth::user()->isImpersonated())
    <div class="clearfix text-right">
        <div class="container mt-1">
            <impersonate-control></impersonate-control>
        </div>
    </div>
@endif
@impersonating
    <div class="alert alert-warning clearfix text-right">
        <div class="container">
            You are impersonating {{ \Auth::user()->name }}
            &nbsp;
            <a href="/impersonate/leave" class="btn btn-secondary btn-sm" @click="clearSessionStorage">Stop impersonating</a>
        </div>
    </div>
@endImpersonating

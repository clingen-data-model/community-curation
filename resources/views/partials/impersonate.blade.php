@if(\Auth::user()->canImpersonate() && !\Auth::user()->isImpersonated())
    <div class="alert alert-warning clearfix text-right">
        <div class="container">
            <div class="form-inline float-right">
                Impersonate a user:
                &nbsp;
                <select name="impersonate_id" class="form-control" onchange="location.href = '/impersonate/take/'+this.value">
                    <option value="">Select user...</option>
                    @foreach($impersonatable as $u)
                        <option value="{{$u->id}}">{{$u->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
@endcan
@impersonating
    <div class="alert alert-warning clearfix text-right">
        <div class="container">
            You are impersonating {{ \Auth::user()->name }}
            &nbsp;
            <a href="/impersonate/leave" class="btn btn-secondary btn-sm">Stop impersonating</a>
        </div>
    </div>
@endImpersonating

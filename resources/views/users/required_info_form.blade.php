@extends('layouts.app');

@section('content')
    <div class="col-lg-8 offset-md-2">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Before you continue&hellip;</h4>
            </div>
            <div class="card-body">
                <h5>Thanks for signing in!</h5>
                <p>
                    There are a couple pieces of information we need to make sure your experience is properly customized and we can adequately report to our funding agencies.  
                    Please fill out the form below to continue.
                </p>
                <hr>
                <form action="/required-info" name="required-info-form" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="user_id" value="{{$user->id}}">
                    @if ($user->country_id == null) 
                        <div class="form-group row">
                            <label for="country-id-field" class="col-lg-2">Country:</label>
                            <div class="col-lg-10">
                                <select name="country_id" id="country-id-field" class="form-control form-control-sm">
                                    <option value="">Select...</option>
                                    @foreach ($countries as $country)
                                        <option value="{{$country->id}}">{{$country->name}}</option>
                                    @endforeach
                                </select>
                                @error('country_id')
                                    <div class="alert alert-danger mt-1">
                                        {{$message}}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    @else
                        <input type="hidden" name="country_id" value="{{$user->country_id}}">
                    @endif

                    @if (is_null($user->timezone) || $user->timezone == 'UTC')
                        <div class="form-group row">
                            <label for="timezone-field" class="col-lg-2">Nearest city:</label>
                            <div class="col-lg-10">
                                <select name="timezone" id="timezone-field" class="form-control form-control-sm">
                                    <option value="">Select...</option>
                                    @foreach ($timezones as $timezone)
                                        <?php if($timezone->name == 'UTC') { continue; } ?>
                                        <option value="{{$timezone->id}}" :selected="'{{$timezone->name}}' == timezone">{{$timezone->name}}</option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Used to determine your timezone</small>
                                @error('timezone')
                                    <div class="alert alert-danger mt-1">
                                        {{$message}}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    @else
                        <input type="hidden" name="timezone" value="{{$user->timezone}}">
                    @endif
                    <div class="form-group row">
                        <div class="col-lg-10 offset-lg-2">
                            <button class="btn btn-primary btn-sm" type="submit">Submit</button>
                        </div>
                    </div>
            </form>
            @if($user->isImpersonated())
                <form action="/required-info" name="bypass-required-info" method="POST">
                    @csrf
                    <input class="btn btn-danger btn-sm" type="submit" name="bypass" value="Bypass while impersonating">
                </form>
            @endif

            </div>
        </div>
    </div>
@endsection
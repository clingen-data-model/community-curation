@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <survey-form
                survey-slug="{{ $slug }}"
                redirect-url="{{ $redirectUrl ?? '/' }}"
                @if(isset($prefillData))
                :prefill-data='@json($prefillData)'
                @endif
            ></survey-form>
        </div>
    </div>
</div>
@endsection

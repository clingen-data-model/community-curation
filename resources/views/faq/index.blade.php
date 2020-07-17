@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <div class="card">
                <div class="card-header">
                    @if (\Auth::user() && \Auth::user()->hasAnyPermission('create faq', 'update faq'))
                        <div class="float-right"><a href="admin/faq" class="btn btn-primary border btn-sm">Manage FAQs</a></div>
                    @endif
                    <h3>Frequently Asked Questions</h3>
                </div>
                <div class="card-body">
                    <dl>
                        @foreach ($faqs as $faq)            
                        <dt>
                            @if (\Auth::user() && \Auth::user()->hasAnyPermission('create faq', 'update faq'))
                                <div class="float-right">
                                    <a href="admin/faq/{{$faq->id}}/edit" class="btn btn-default border btn-xs">
                                        <b-icon-pencil></b-icon-pencil>
                                    </a>
                                </div>
                            @endif
                            <h5  v-b-toggle.faq-collapse-{{$faq->id}}>{{$faq->question}}</h5>
                        </dt>
                        <dd class="mb-4 pb-3 pl-3 border-bottom" style="font-size: 1rem;">
                            <b-collapse id="faq-collapse-{{$faq->id}}" class="clearfix">
                                {!!$faq->answer!!}
                            </b-collapse>
                        </dd>
                        @endforeach
                    </dl>
                
                    <p style="font-size: 1.25rem">
                        If you have any questions not addressed on this FAQ, please email <a href="mailto:volunteer@clinicalgenome.org">volunteer@clinicalgenome.org</a>.
                    </p>
                </div>
            </div>
        </div>
    </div>

@endsection
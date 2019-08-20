<div class="address-container {{ $renderable->class }}">
    <div><strong>Address</strong></div>

    <div class="indent">
        {!! $renderable->contents['street1']->render($context) !!}
        {!! $renderable->contents['street2']->render($context) !!}
        <div class="form-row mt-0">
            <div class="col-sm-9">
                <div class="form-row">
                    <div class="col-sm-3">
                    @component('surveys::questions.question_no_layout', ['renderable' => $renderable->contents['city'], 'context' => $context])
                        @slot('answers')
                            @include('surveys::questions.input', ['type'=>'text', 'question'=>$renderable->contents['city'], 'context'=>$context])
                        @endslot
                    @endcomponent
                    </div>
                    <div class="col-sm-3">
                    @component('surveys::questions.question_no_layout', ['renderable' => $renderable->contents['state'], 'context' => $context])
                        @slot('answers')
                            @include('surveys::questions.input', ['type'=>'text', 'question'=>$renderable->contents['state'], 'context'=>$context])
                        @endslot
                    @endcomponent
                    </div>
                    <div class="col-sm-3">
                    @component('surveys::questions.question_no_layout', ['renderable' => $renderable->contents['zip'], 'context' => $context])
                        @slot('answers')
                            @include('surveys::questions.input', ['type'=>'text', 'question'=>$renderable->contents['zip'], 'context'=>$context])
                        @endslot
                    @endcomponent
                    </div>
                    <div class="col-sm-3">
                    @component('surveys::questions.question_no_layout', ['renderable' => $renderable->contents['country_id'], 'context' => $context])
                        @slot('answers')
                            @include('surveys::questions.multiple_choice.select_input', ['question'=>$renderable->contents['country_id'], 'context'=>$context])
                        @endslot
                    @endcomponent
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
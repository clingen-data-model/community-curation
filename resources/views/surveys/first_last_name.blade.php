<div 
  class="
    form-group question-block 
    {{($renderable->class) ? $renderable->class : ''}} 
    
    @if(isset($context['errors']) && $context['errors']->has($renderable->name)) 
      has-errors 
    @endif
  "
  @if($renderable->id)
    id="{{$renderable->id}}"
  @endif
>
    <div class="question-text">
        Name*
    </div>
    <div class="question-answers d-flex flex-row justify-content-start">
        <div>
            @include('surveys::questions.input', ['question' => $renderable->contents['first_name']])
            @include('surveys::error', ['question'=>$renderable->contents['first_name']])
        </div>
        
        <div>
            @include('surveys::questions.input', ['question' => $renderable->contents['last_name']])
            @include('surveys::error', ['question'=>$renderable->contents['last_name']])
        </div>
    </div>
</div>
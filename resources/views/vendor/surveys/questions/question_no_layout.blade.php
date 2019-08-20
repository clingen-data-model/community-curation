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
    @if($renderable->questionText)
      @include('surveys::questions.question_text', ['question'=>$renderable])
    @endif
    
    {{$answers}}

    <div class="mt-1">@include('surveys::error', ['question'=>$renderable])</div>
</div>  
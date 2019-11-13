@component('surveys::questions.question', compact('renderable', 'context'))

  @slot('answers')
    <div class="btn-group-vertical" role="group">
      @foreach($renderable->options as $idx => $option)
        <label class="checkbox {{$option->class}}">
        <input type="checkbox" 
          name="{{ $renderable->name }}[]"
          id="{{ $renderable->name }}-{{$idx}}_checkbox" 
          class="{{ $option->class}}"
          autocomplete="off"
          value="{{$option->value}}"
          @if( isset($context['response']->{$renderable->name}) 
              && in_array($option->name, $context['response']->{$renderable->name}) 
          )
            checked="checked"
          @endif
          @if($option->show)
            data-skipTarget="{{$option->show}}"
          @endif
          @if($option->hide)
            data-hide="{{$option->hide}}"
          @endif
          @if($option->class)
            class="{{$option->class}}"
          @endif
          />
          {!! $option->getCompiledLabel($context) !!}
      </label>
      @endforeach
    </div>
  @endslot

  @slot('errors-block')
    @if (isset($context['errors']) 
          && count(array_intersect( array_keys($context['errors']->getMessages()), $renderable->getOptionNames() )) > 0 )
      <div class="error-block">
        <ul class="error-list list-unstyled">
          <li>A response is required for this question</li>
        </ul>
      </div>
    @endif
  @endslot
@endcomponent
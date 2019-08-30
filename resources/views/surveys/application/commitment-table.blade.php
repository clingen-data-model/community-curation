<p>{!! $renderable->getCompiledQuestionText($context) !!}</p>
<table class="table table-striped text-center">
    <thead>
        <tr>
            <th style="width: 40%">&nbsp;</th>
            <th class="text-center" style="width: 30%">Comprehensive</th>
            <th class="text-center" style="width: 30%">Baseline</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="text-left">Read scientific articles</td>
            <td style="font-size: 1.5rem;">✅</td>
            <td style="font-size: 1.5rem;">✅</td>
        </tr>
        <tr>
            <td class="text-left">Identify and curate evidence</td>
            <td style="font-size: 1.5rem;">✅</td>
            <td style="font-size: 1.5rem;">✅</td>
        </tr>
        <tr>
            <td class="text-left">Participate on conference calls with experts</td>
            <td style="font-size: 1.5rem;">✅</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td class="text-left">
                Use <a href="https://hypothes.is" target="hypothes.is">Hypothes.is</a>
                <div><small>*a web-based annotation tool</small></div>
            </td>
            <td>Optional</td>
            <td>Required</td>
        </tr>
        <tr>
            <td class="text-left">Time Commitment</td>
            <td><small>Approximately 8-10 hours/month</small></td>
            <td>
                <small>
                    No commitment
                    <div><small>*curate at your leisure and interest level</small></div>
                </small>
            </td>
        </tr>
        <tr>
            <td class="text-left">Skill or education requirement</td>
            <td><small>Advanced degree or strong genetics background preferred</small></td>
            <td><small>No requirement</small></td>
        </tr>
        <tr data-toggle="buttons">
            <td>&nbsp;</td>
            <td class="btn-group-toggle">
                <label class="btn btn-lg btn-secondary @if ($context['response']->{$renderable->name} == 2) active @endif">
                    <input 
                        type="radio" 
                        name="{{$renderable->name}}" 
                        id="{{$renderable->name}}-comprehensive" 
                        value="2"
                        autocomplete="off"
                        @if ($context['response']->{$renderable->name} == 2) 
                            checked
                        @endif
                    >
                    I prefer Comprehensive
                </label>
            </td>
            <td class="btn-group-toggle">
                    <label class="btn btn-lg btn-secondary @if ($context['response']->{$renderable->name} == 1) active @endif">
                        <input 
                            type="radio" 
                            name="{{$renderable->name}}" 
                            id="{{$renderable->name}}-baselin" 
                            value="1"
                            autocomplete="off"
                            @if ($context['response']->{$renderable->name} == 1) 
                                checked
                            @endif
                        >
                        I prefer Baseline
                    </label>
                </div>
            </td>
            <td class="btn-group-toggle">
            </td>
        </tr>
    </tbody>
</table>
@include('surveys::error', ['question'=>$renderable])

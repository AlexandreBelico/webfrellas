@extends('layouts.app')
@section('content') 
<!-- Header start --> 
@include('includes.header') 
<!-- Header end --> 
<!-- Inner Page Title start --> 
@include('includes.inner_page_title', ['page_title'=>__('Apply on Job')]) 
<!-- Inner Page Title end -->
<div class="listpgWraper">
    <div class="container"> @include('flash::message')
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="userccount">
                    <div class="formpanel"> {!! Form::open(array('method' => 'post', 'route' => ['post.apply.job', $job_slug])) !!} 
                        <!-- Job Information -->
                        <h5>{{$job->title}}</h5>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="formrow{{ $errors->has('cover_letter') ? ' has-error' : '' }}">
                                {!! Form::textarea('cover_letter', null, ['id' => 'cover_letter', 'rows' => 4, 'cols' => 54, 'style' => 'resize:none;margin: 0px; width: 687px; height: 430px;', 'placeholder'=>__('Cover Letter')]) !!}
                                    
                                    @if ($errors->has('cover_letter')) <span class="help-block"> <strong>{{ $errors->first('cover_letter') }}</strong> </span> @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="formrow{{ $errors->has('expected_salary') ? ' has-error' : '' }}"> {!! Form::number('expected_salary', null, array('class'=>'form-control', 'id'=>'expected_salary', 'placeholder'=>__('Expected Cost').'')) !!}
                                    @if ($errors->has('expected_salary')) <span class="help-block"> <strong>{{ $errors->first('expected_salary') }}</strong> </span> @endif
                                </div>
                            </div>
                        
                            <div class="col-md-6">
                                <div class="formrow{{ $errors->has('salary_period_id') ? ' has-error' : '' }}" id="salary_period_id_div"> {!! Form::select('salary_period_id', ['' => __('Select Project length')]+$salaryPeriods, null, array('class'=>'form-control', 'id'=>'salary_period_id')) !!}
                                    {!! APFrmErrHelp::showErrors($errors, 'salary_period_id') !!} </div>
                            </div>
                            <div class="col-md-6">
                            	<span id="cost_show"></span>	
                            </div>
                        </div>
                        <br>
                        <input type="submit" class="btn" value="{{__('Apply on Job')}}">
                        {!! Form::close() !!} </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('includes.footer')
@endsection
@push('scripts') 
<script>
    $(document).ready(function () {
        var real_val = '@php echo $percentage->percent; @endphp';
        $("#expected_salary").keyup(function(){
            var value = $(this).val();
            if(value > 0){
                listPrice = parseFloat(value);
                discount  = parseFloat(real_val);
                var payFee = 6;
                var show_Cost = listPrice - ( listPrice * discount / 100 ).toFixed(2);
                var showCost = show_Cost - ( show_Cost * payFee / 100 );
                $("#cost_show").text("You will receive "+showCost.toFixed(2)+" USD");
            }else{
                $("#cost_show").text("You need to set some amount");
            }
        });
        $('#salary_currency').typeahead({
            source: function (query, process) {
                return $.get("{{ route('typeahead.currency_codes') }}", {query: query}, function (data) {
                    console.log(data);
                    data = $.parseJSON(data);
                    return process(data);
                });
            }
        });

    });
</script> 
@endpush
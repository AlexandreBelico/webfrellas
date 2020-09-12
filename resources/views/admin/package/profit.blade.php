@extends('admin.layouts.admin_layout')
@section('content')
<div class="page-content-wrapper"> 
    <!-- BEGIN CONTENT BODY -->
    <div class="page-content"> 
        <!-- BEGIN PAGE HEADER--> 
        <!-- BEGIN PAGE BAR -->
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li> <a href="{{ route('admin.home') }}">Home</a> <i class="fa fa-circle"></i> </li>
                <li> <span>Add Profit Percentage</span> </li>
            </ul>
        </div>
        <!-- END PAGE BAR -->        
        <!-- END PAGE HEADER-->
        <br />
        @include('flash::message')
        <div class="row">
            <div class="col-md-12">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption font-red-sunglo"> <i class="icon-settings font-red-sunglo"></i> <span class="caption-subject bold uppercase">Set Percentage</span> </div>
                    </div>
                    <div class="portlet-body form">          
                        <ul class="nav nav-tabs">              
                            <li class="active"> <a href="#Details" data-toggle="tab" aria-expanded="false"> Details </a> </li>
                        </ul>
                        {!! Form::open(array('method' => 'post', 'route' => 'store.profit', 'class' => 'form', 'files'=>true)) !!}
                        <div class="tab-content">              
                            {!! APFrmErrHelp::showOnlyErrorsNotice($errors) !!}
                            @include('flash::message')
                            <div class="form-body">
                                <div class="form-group {!! APFrmErrHelp::hasError($errors, 'profit') !!}"> {!! Form::label('profit', 'Profit Percentage', ['class' => 'bold']) !!}
                                    {!! Form::number('profit', $percentage->percent??"", array('class'=>'form-control', 'id'=>'profit', 'autocomplete'=>'off', 'placeholder'=>'%')) !!}
                                    @if(isset($percentage->id))
                                    {!! Form::hidden('pid', $percentage->id) !!}
                                    @endif
                                    {!! APFrmErrHelp::showErrors($errors, 'profit') !!} </div>
                                <div class="form-actions"> {!! Form::button('Update <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>', array('class'=>'btn btn-large btn-primary', 'type'=>'submit')) !!} </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END CONTENT BODY --> 
</div>
@endsection
@extends('layouts.app')
@section('content') 
<!-- Header start --> 
@include('includes.header') 
<!-- Header end --> 
<!-- Inner Page Title start --> 
@include('includes.inner_page_title', ['page_title'=>__('Milestones list')]) 
<!-- Inner Page Title end -->


<!-- Delete Milestone Confirmation Modal : START -->
<div class="modal fade" id="deleteMilestoneModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    {!! Form::open(array('method' => 'post', 'route' => ['post.deletemilestone'])) !!} 
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Delete Confirmation</h5>
      </div>
      <div class="modal-body">
            <h6>Are you sure want to delete milestone ?</h6>
            <input type="hidden" name="deleteMilestoneId" class="deleteMilestoneId">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <input type="submit" class="btn btn-danger" value="Delete">
      </div>
    </div>
    {!! Form::close() !!} 
  </div>
</div>
<!-- Delete Milestone Confirmation Modal : END -->
<!-- =========== Verify Work modal  ==========  -->
<div class="modal fade" id="verifyworkmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    {!! Form::open(array('method' => 'post', 'route' => ['post.completemilestone'])) !!} 
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalLabel">Mark as complete the milestone</h5>
      </div>
      <div class="modal-body">
        <div class="form-group">
            <b class="boldtitle">Details : </b>
            <p class="display_submit_message"></p>
            <input type="hidden" name="completemilestoneId" class="completemilestoneId">
        </div>
      </div>
      <div class="modal-footer">
        <input type="submit" class="btn btn-success" value="Approve">
      </div>
    </div>
    {!! Form::close() !!} 
  </div>
</div>
<!-- =========== Verify Work modal  ==========  -->

<div class="listpgWraper">
    <div class="container"> @include('flash::message')
        <div class="row">
             @include('includes.company_dashboard_menu')
            <div class="col-md-8">
                <div class="userccount">
                    <div class="col-md-12 text-right">
                        <a href="{{route('milestones.front.job', [$job->id])}}" class="btn btn-info btn-sm" title="Add Milestones">
                            <span class="fa fa-plus"></span> Add milestones
                        </a>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6"><h5>{{$job->title}}</h5></div>
                    </div>
                    <hr class="rowseparator">
                    @if(count($milestones)>0)
                    @php
                        $i = 1;
                    @endphp
                    @foreach($milestones as $milestone)
                    @php
                        $startdate = date("d M, Y", strtotime($milestone->start_date));
                        $enddate = date("d M, Y", strtotime($milestone->end_date));
                    @endphp
                    <div class="row">
                        <div class="col-md-9">
                            <h3><a class="milestonelisttitle" href="{{route('job.milestone.edit', [$milestone->id])}}">Milestone : {{ $milestone->milestone_title }}</a></h3>
                        </div>
                        <div class="col-md-3 text-right">
                            @if($milestone->status==0)
                                <span class="btn btn-info btn-sm">Open</span>
                            @endif
                            @if($milestone->status==1)
                                <span class="btn btn-warning btn-sm">In progress</span>
                            @endif
                            @if($milestone->status==2)
                                <span onclick="verifywork({{$milestone->id}})" class="btn btn-primary btn-sm">Submitted</span>
                            @endif
                             @if($milestone->status==3)
                                <span class="btn btn-success btn-sm">Completed</span>
                            @endif
                            @if($milestone->status==4)
                                <span class="btn btn-warning btn-sm">Paused</span>
                            @endif

                            <span class="btn btn-danger btn-sm" onclick="deleteMilestone({{$milestone->id}})">
                                <i class="fa fa-trash"></i>
                            </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <ul >
                                <li class="milestoneobjects">Start date : {{ $startdate }} </li>
                                <li class="milestoneobjects">End date : {{ $enddate }} </li>
                                <li class="milestoneobjects">Price : {{ $milestone->price }} </li>
                            </ul>
                        </div>

                        <div class="col-md-8 no-gutters">
                            <p class="milestonedescription"> {{ str_limit($milestone->description, 400, '') }} 
                                @if (strlen($milestone->description) > 400)
                                    <span id="dots_{{$milestone->id}}">...</span>
                                    <span id="moredescription_{{$milestone->id}}" class="moredescription">{{ substr($milestone->description, 400) }}</span>
                                    <button class="btn-link btn-anchor" href="javascript:void(0)" onclick="readmore({{$milestone->id}})" id="readmorebtn_{{$milestone->id}}">Read more</button>
                                @endif
                            </p>
                        </div>
                    </div>

                    <hr class="rowseparator">
                    @endforeach
                    @else 
                        <p>No milestones found!!!</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@include('includes.footer')
@endsection

 
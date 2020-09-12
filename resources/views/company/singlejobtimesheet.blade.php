@extends('layouts.app')
@section('content')
<!-- Header start -->
@include('includes.header')
<!-- Header end --> 
<!-- Inner Page Title start -->
@include('includes.inner_page_title', ['page_title'=>__('Timesheets')])
<!-- Inner Page Title end -->

<!-- Modal -->
<div class="modal fade" id="changetimesheetstatusmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    {!! Form::open(array('method' => 'post', 'route' => 'post.timeline.changestatus')) !!} 
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h5 class="modal-title" id="exampleModalLabel">Confirmation</h5>
      </div>
      <div class="modal-body">
         <h6>Are you sure want to change status ?</h6>
         <input type="hidden" name="timesheetid" class="timesheetid" id="timesheetid">
         <input type="hidden" name="changedstatusvalue" class="changedstatusvalue">
      </div>
      <div class="modal-footer">
        <input type="submit" class="btn btn-success" name="approve" value="Approve">
        <input type="submit" class="btn btn-danger" name="reject" value="Reject">
      </div>
    </div>
    {!! Form::close() !!} 
  </div>
</div>

<div class="listpgWraper">
    <div class="container">  @include('flash::message')
        <div class="row">
            @include('includes.company_dashboard_menu')

            <div class="col-md-9 col-sm-8"> 
                <div class="myads">
                    @if(isset($timesheetDetails) && count($timesheetDetails))
                        <h3>Timesheet Details of {{$timesheetDetails[0]->title}}</h3>
                    @else
                        <h3>No timesheet details found</h3>
                    @endif
                    <ul class="searchList">
                        <!-- job start --> 
                        @if(isset($timesheetDetails) && count($timesheetDetails))
                        @foreach($timesheetDetails as $timesheet)
                        @php
                            $whichdate = $timesheet->whichdate;
                            $whichdate = date_format(date_create($whichdate), 'd M, Y')
                        @endphp
                        <li>
                            <div class="row">
                                <div class="col-md-10 col-sm-10">
                                    <div class="jobinfo">
                                        <h3><a href="javascript:void(0)" title="Milestone number">Milestone : {{$timesheet->milestone_title}}</a></h3>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                @if($timesheet->status==0)
                                    <div class="col-md-2 col-sm-2 text-right" onclick="changeTimesheetStatus(1, {{$timesheet->id}})">
                                        <span class="btn btn-warning btn-sm">Pending</span>
                                        <div class="clearfix"></div>
                                    </div>
                                @elseif($timesheet->status==1)
                                    <div class="col-md-2 col-sm-2 text-right">
                                        <span class="btn btn-success btn-sm">Approved</span>
                                        <div class="clearfix"></div>
                                    </div>
                                @elseif($timesheet->status==2)
                                    <div class="col-md-2 col-sm-2 text-right">
                                        <span class="btn btn-danger btn-sm">Rejected</span>
                                        <div class="clearfix"></div>
                                    </div>  
                                @endif  
                                
                            </div>
                            <p>Date : {{$whichdate}}</p>
                            <p>Time spent : {{$timesheet->time_spent}}</p>
                            <p>{{$timesheet->description}}</p>
                        </li>

                        @endforeach
                        @endif
                    </ul>

                    {{ $timesheetDetails->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@include('includes.footer')
@endsection
@push('scripts')
<script type="text/javascript">
    function deleteJob(id) {
    var msg = 'Are you sure?';
    if (confirm(msg)) {
    $.post("{{ route('delete.front.job') }}", {id: id, _method: 'DELETE', _token: '{{ csrf_token() }}'})
            .done(function (response) {
            if (response == 'ok')
            {
            $('#job_li_' + id).remove();
            } else
            {
            alert('Request Failed!');
            }
            });
    }
    }
</script>
@endpush
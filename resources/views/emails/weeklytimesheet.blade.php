@extends('admin.layouts.email_template')
@section('content')
<style type="text/css">
  .timesheettable tr:nth-child(even){background-color: #f2f2f2;}
</style>
<table border="0" cellpadding="0" cellspacing="0" class="force-row" style="width: 100%;    border-bottom: solid 1px #ccc;">
    <tr>
        <td class="content-wrapper" style="padding-left:24px;padding-right:24px"><br>
            <div class="title" style="font-family: Helvetica, Arial, sans-serif; font-size: 18px;font-weight:600;color: #18a6fd;text-align: center;padding-top: 10px;padding-bottom: 10px;">
                Weekly timesheet details for {{$singleUserTimesheet[0]['title']}}
            </div>
            @if(isset($employer))
            <div class="title" style="font-family: Helvetica, Arial, sans-serif; font-size: 16px;font-weight:400;text-align: center;padding-top: 10px;padding-bottom: 10px;">
                Candidate : {{$singleUserTimesheet[0]['name']}}
            </div> 
            @endif
        </td>
    </tr>
    <tr>
        <td class="cols-wrapper" style="padding-left:12px;padding-right:12px">
            <table style="border-collapse: collapse; border-spacing: 0; width: 100%; border: 1px solid #ddd;" class="timesheettable">
            <tr>
              <th style="text-align: center; padding: 8px;">Milestone</th>
              <th style="text-align: center; padding: 8px;">Date</th>
              <th style="text-align: center; padding: 8px;">Time Spent</th>
              <th style="text-align: center; padding: 8px;">Status</th>
              <th style="text-align: center; padding: 8px;">Description</th>
            </tr>
            @foreach($singleUserTimesheet as $timesheet)
            @php
              $whichdate = $timesheet->whichdate;
              $whichdate = date_format(date_create($whichdate), 'd M, Y');
            @endphp  
              <tr>
                <td style="text-align: center; padding: 8px;">
                  Milestone : {{$timesheet->milestone_number}}
                </td>
                <td style="text-align: center; padding: 8px;">
                  {{$whichdate}}
                </td>
                <td style="text-align: center; padding: 8px;">
                  {{$timesheet->time_spent}}
                </td>
                <td style="text-align: center; padding: 8px;">
                  @if($timesheet->status==0)
                    <span class="btn btn-warning btn-sm">Pending</span>
                      @elseif($timesheet->status==1)
                    <span class="btn btn-success btn-sm">Approved</span>
                      @elseif($timesheet->status==2)
                    <span class="btn btn-danger btn-sm">Rejected</span>
                  @endif  
                </td>
                <td style="text-align: center; padding: 8px;">
                  {!! str_limit($timesheet->description, 80, '...') !!}
                </td>
              </tr>
            @endforeach
      </table>
  </td>
    </tr>
     <tr>
        <td class="content-wrapper" style="padding-left:24px;padding-right:24px"><br>
            <div class="title" style="font-family: Helvetica, Arial, sans-serif; font-size: 14px;font-weight:600;color: #18a6fd;text-align: center;
                 padding-top: 10px;padding-bottom: 10px;">
            @if(isset($employer))     
              <a href="{{route('posted.jobs')}}">View more details</a>
            @else
              <a href="{{route('job.timesheets')}}">View more details</a>
            @endif
            </div>
        </td>
    </tr>
</table>
@endsection
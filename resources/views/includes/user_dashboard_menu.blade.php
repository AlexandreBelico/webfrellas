<div class="col-md-3 col-sm-4">
    <div class="switchbox">
        <div class="txtlbl">{{__('Immediate Available')}} <i class="fa fa-info-circle" data-toggle="popover" data-trigger="hover" data-placement="top" data-content="{{__('Are you immediate available')}}?" data-original-title="{{__('Are you immediate available')}}?" title="{{__('Are you immediate available')}}?"></i>
        </div>
        <div class="pull-right">
            <label class="switch switch-green"> @php
                $checked = ((bool)Auth::user()->is_immediate_available)? 'checked="checked"':'';
                @endphp
                <input type="checkbox" name="is_immediate_available" id="is_immediate_available" class="switch-input" {{$checked}} onchange="changeImmediateAvailableStatus({{Auth::user()->id}}, {{Auth::user()->is_immediate_available}});">
                <span class="switch-label" data-on="On" data-off="Off"></span> <span class="switch-handle"></span> </label>
        </div>
        <div class="clearfix"></div>
    </div>
    <ul class="usernavdash">
        <li class="active"><a href="{{route('home')}}"><i class="fa fa-tachometer" aria-hidden="true"></i> {{__('Dashboard')}}</a>
        </li>
        <li><a href="{{ route('my.profile') }}"><i class="fa fa-user" aria-hidden="true"></i> {{__('My Profile')}}</a>
        </li>
        <li><a href="{{ route('view.public.profile', Auth::user()->id) }}"><i class="fa fa-eye" aria-hidden="true"></i> {{__('View Public Profile')}}</a>
        </li>
        <li><a href="{{ route('my.job.applications') }}"><i class="fa fa-desktop" aria-hidden="true"></i> {{__('My Job Applications')}}</a>
        </li>
        <li><a href="{{ route('my.favourite.jobs') }}"><i class="fa fa-heart" aria-hidden="true"></i> {{__('My Favourite Jobs')}}</a>
        </li>
        <!-- Below line is added by Hetal(Line number : 26) -->
        <li><a href="{{ route('jobs.developmentstatus') }}"><i class="fa fa-gears" aria-hidden="true"></i> {{__('Development Status')}}</a>
        </li>
         <li><a href="{{ route('my.payment_management') }}"><i class="fa fa-paypal" aria-hidden="true"></i> {{__('Payment Management')}}</a>
        </li>
        <!-- <li><a href="{{route('my.messages')}}"><i class="fa fa-envelope-o" aria-hidden="true"></i> {{__('My Messages')}}</a>
        </li> -->

        <li><a href="{{route('my.chats')}}"><i class="fa fa-comments" aria-hidden="true"></i> {{__('My Chats')}}(<span id="my_msg_no"></span>)</a>
       
        <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa fa-sign-out" aria-hidden="true"></i> {{__('Logout')}}</a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
        </li>
    </ul>
    <div class="row">
        <div class="col-md-12">{!! $siteSetting->dashboard_page_ad !!}</div>
    </div>
</div>

@push('scripts')
<script src="https://js.pusher.com/5.1/pusher.min.js"></script>
<script type="text/javascript">
      var receiver_id = '';
    var my_id = "{{ auth::id() }}";

    $(document).ready(function() {
        var temp = '{{ App\Helpers\DataArrayHelper::countmessageCandidates(Auth::user()->id) }}';
        $('#my_msg_no').html(temp);

        //alert(temp);
        var base_url = "{{ env('APP_URL') }}";
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });

        // Enable pusher logging - don't include this in production
        Pusher.logToConsole = true;

        var pusher = new Pusher('a28b149bfa9cd891c76e', {
          cluster: 'ap2',
          encrypted: false,
        });

        var channel = pusher.subscribe('my-channel');
        channel.bind('my-event', function(data) { 
          console.log(data)
          var t = parseInt(temp)+1;
          //alert(t);
          $('#my_msg_no').html(t);
          temp = t;
        });

       
    });
</script>
@endpush
<div class="header">
    <div class="container">
        <div class="row">
            <div class="col-md-2 col-sm-3 col-xs-12"><a href="{{url('/')}}" class="logo"><img
                            src="{{ asset('/public/') }}sitesetting_images/thumb/{{ $siteSetting->site_logo }}"
                            alt="{{ $siteSetting->site_name }}"/></a>
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span
                                class="icon-bar"></span> <span class="icon-bar"></span></button>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="col-md-10 col-sm-12 col-xs-12">

                <!-- Nav start -->
                <div class="navbar navbar-default" role="navigation">
                    <div class="navbar-collapse collapse" id="nav-main">
                        <ul class="nav navbar-nav">
                            <li class="{{ Request::url() == route('index') ? 'active' : '' }}"><a
                                        href="{{url('/')}}">{{__('Home')}}</a></li>
                            @if(!Auth::guard('company')->check())
                                <li class="{{ Request::url()}}"><a href="{{url('/jobs')}}">{{__('Jobs')}}</a></li>
                                <li class="{{ Request::url()}}"><a href="{{url('/companies')}}">{{__('Companies')}}</a>
                                </li>
                            @endif
                            @foreach($show_in_top_menu as $top_menu) @php $cmsContent = App\CmsContent::getContentBySlug($top_menu->page_slug); @endphp
                            <li class="{{ Request::url() == route('cms', $top_menu->page_slug) ? 'active' : '' }}"><a
                                        href="{{ route('cms', $top_menu->page_slug) }}">{{ $cmsContent->page_title  or ''}}</a>
                            </li>
                            @endforeach
                            <li class="{{ Request::url() == route('contact.us') ? 'active' : '' }}"><a
                                        href="{{ route('contact.us') }}">{{__('Contact us')}}</a></li>
                            @if(Auth::check())
                                <li class="{{ Request::url() == route('my.chats') ? 'active' : '' }}"><a
                                            href="{{ route('my.chats') }}">{{__('Messages')}}</a></li>

                            @endif
                            @if(Auth::check())
                                <li class="dropdown userbtn"><a href="">{{Auth::user()->printUserImage()}}</a>
                                    <ul class="dropdown-menu">
                                        <li><a href="{{route('home')}}"><i class="fa fa-tachometer"
                                                                           aria-hidden="true"></i> {{__('Dashboard')}}
                                            </a></li>
                                        <li><a href="{{ route('my.profile') }}"><i class="fa fa-user"
                                                                                   aria-hidden="true"></i> {{__('My Profile')}}
                                            </a></li>
                                        <li><a href="{{ route('view.public.profile', Auth::user()->id) }}"><i
                                                        class="fa fa-eye"
                                                        aria-hidden="true"></i> {{__('View Public Profile')}}</a></li>
                                        <li><a href="{{ route('my.job.applications') }}"><i class="fa fa-desktop"
                                                                                            aria-hidden="true"></i> {{__('My Job Applications')}}
                                            </a></li>
                                        <!-- Below line number 33 added by Hetal -->
                                        <li><a href="{{ route('job.timesheets') }}"><i class="glyphicon glyphicon-time"
                                                                                       aria-hidden="true"></i> {{__('My Timesheets')}}
                                            </a></li>
                                        <li><a href="{{ route('job.paymentdetails') }}"><i class="fa fa-cc-paypal"
                                                                                       aria-hidden="true"></i> {{__('My payments')}}
                                            </a></li>

                                        <li><a href="{{ route('logout') }}"
                                               onclick="event.preventDefault(); document.getElementById('logout-form-header').submit();"><i
                                                        class="fa fa-sign-out" aria-hidden="true"></i> {{__('Logout')}}
                                            </a></li>
                                        <form id="logout-form-header" action="{{ route('logout') }}" method="POST"
                                              style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </ul>
                                </li>
                            @endif
                            @if(Auth::guard('company')->check())
                                <li class="postjob"><a href="{{route('post.job')}}">{{__('Post a job')}}</a></li>
                                <li class=""><a href="{{route('companychats.messages')}}">{{__('Messages')}}</a></li>
                                <li class="dropdown userbtn"><a
                                            href="">{{Auth::guard('company')->user()->printCompanyImage()}}</a>
                                    <ul class="dropdown-menu">
                                        <li><a href="{{route('company.home')}}"><i class="fa fa-tachometer"
                                                                                   aria-hidden="true"></i> {{__('Dashboard')}}
                                            </a></li>
                                        <li><a href="{{ route('company.profile') }}"><i class="fa fa-user"
                                                                                        aria-hidden="true"></i> {{__('View Public Profile')}}
                                            </a></li>
                                        <li><a href="{{ route('post.job') }}"><i class="fa fa-desktop"
                                                                                 aria-hidden="true"></i> {{__('Post Job')}}
                                            </a></li>
                                        <!-- Below line number 48 added by Hetal -->
                                        <li><a href="{{ route('posted.jobs') }}"><i class="glyphicon glyphicon-time"
                                                                                    aria-hidden="true"></i> {{__('Timesheets')}}
                                            </a></li>
                                        <li><a href="{{route('company.messages')}}"><i class="fa fa-envelope-o"
                                                                                       aria-hidden="true"></i> {{__('Messages')}}
                                            </a></li>
                                        <li><a href="{{ route('company.logout') }}"
                                               onclick="event.preventDefault(); document.getElementById('logout-form-header1').submit();">{{__('Logout')}}</a>
                                        </li>
                                        <form id="logout-form-header1" action="{{ route('company.logout') }}"
                                              method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </ul>
                                </li>
                            @endif @if(!Auth::user() && !Auth::guard('company')->user())
                                <li class="postjob dropdown"><a href="{{route('login')}}">{{__('Sign in')}} <span
                                                class="caret"></span></a>

                                    <!-- dropdown start -->

                                    <ul class="dropdown-menu">
                                        <li><a href="{{route('login')}}">{{__('Sign in')}}</a></li>
                                        <li><a href="{{route('register')}}">{{__('Register')}}</a></li>
                                    </ul>

                                    <!-- dropdown end -->

                                </li>
                            @endif
                            @if(Auth::user() || Auth::guard('company')->user())
                                <li class="dropdown dropdown-notifications">
                                    <a href="#notifications-panel" class="dropdown-toggle" data-toggle="dropdown">
                                        <i data-count="@if(isset($notificationCount) && $notificationCount != 0) {{ $notificationCount }} @else 0 @endif" class="glyphicon glyphicon-bell notification-icon"></i>
                                    </a>
                                    <div class="dropdown-container" style="width: 275px;">
                                        <div class="dropdown-toolbar">
                                            {{--<div class="dropdown-toolbar-actions">
                                                <a href="#">Mark all as read</a>
                                            </div>--}}
                                            <h3 class="dropdown-toolbar-title">Notifications (<span class="notif-count">@if(isset($notificationCount) && $notificationCount != 0) {{ $notificationCount }} @else 0 @endif</span>)
                                            </h3>
                                        </div>
                                        <ul class="dropdown-menu" id="dropdown-menu">
                                            @if(isset($notification))
                                                @foreach($notification as $o)
                                                    <li class="notification">
                                                        <a href="{{ url('job').'/'.$o->getJobDetails->slug }}">{{ $o->content }}</a>
                                                    </li>
                                                @endforeach
                                            @endif
                                        </ul>
                                        <div class="dropdown-footer text-center">
                                            <a href="{{ route('notification.list') }}" class="btn btn-block">
                                                View All
                                            </a>
                                        </div>
                                    </div>
                                </li>
                            @endif
                            <li class="dropdown userbtn"><a href="{{url('/')}}"><img src="{{asset('/public/')}}images/lang.png"
                                                                                     alt="" class="userimg"/></a>
                                <ul class="dropdown-menu">
                                    @foreach($siteLanguages as $siteLang)
                                        <li><a href="javascript:;"
                                               onclick="event.preventDefault(); document.getElementById('locale-form-{{$siteLang->iso_code}}').submit();">{{$siteLang->native}}</a>
                                            <form id="locale-form-{{$siteLang->iso_code}}"
                                                  action="{{ route('set.locale') }}" method="POST"
                                                  style="display: none;">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="locale" value="{{$siteLang->iso_code}}"/>
                                                <input type="hidden" name="return_url" value="{{url()->full()}}"/>
                                                <input type="hidden" name="is_rtl" value="{{$siteLang->is_rtl}}"/>
                                            </form>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        </ul>
                        <!-- Nav collapes end -->
                    </div>
                    <div class="clearfix"></div>
                </div>
                <!-- Nav end -->
            </div>
        </div>
        <!-- row end -->
    </div>

    <!-- Header container end -->

</div>
@push('scripts')
    @if(Auth::check() && !Auth::guard('company')->check())
        <script>
            $(document).ready(function () {
                //Remember to replace key and cluster with your credentials.
                let pusher = new Pusher('a28b149bfa9cd891c76e', {
                    cluster: 'ap2',
                    encrypted: true
                });
                //Also remember to change channel and event name if your's are different.
                let channel = pusher.subscribe('notification');
                channel.bind('notification-event', function (sendNotification) {
                    let dropdown_menu = $('.job-display > #job-list');
                    dropdown_menu.append('<li class="notify newJob job-' + sendNotification.jobId + '" id="newJob"><button id="close-job-' + sendNotification.jobId + ' " class="btn-circle close-job" style="">x</button>' + sendNotification.content + '</li>');
                    setTimeout(function () {
                        $('.job-display > #job-list .job-' + sendNotification.jobId).remove();
                    }, 30000);
                    $("#close-job-" + sendNotification.jobId).click(function () {
                        $('.job-display > #job-list .job-' + sendNotification.jobId).remove();
                    });
                });

                //Also remember to change channel and event name if your's are different.
                let hire_channel = pusher.subscribe('hire-candidate');
                hire_channel.bind('hire-candidate-event', function (hiredCandidateNotification) {
                    let dropdown_menu = $('#dropdown-menu');
                    dropdown_menu.append('<li class="notification"><a href="{{ url('job') }}' + '/' + hiredCandidateNotification.jobSlug + '">' + hiredCandidateNotification.content + '</li>');
                });
            });
        </script>
    @else
        <script>
            let pusher = new Pusher('a28b149bfa9cd891c76e', {
                cluster: 'ap2',
                encrypted: true
            });
            //Also remember to change channel and event name if your's are different.
            let channel = pusher.subscribe('employer');
            channel.bind('employer-notification', function (employerNotification) {
                console.log(employerNotification.content);
                console.log(employerNotification.notificationId);
                let dropdown_menu = $('#dropdown-menu');
                dropdown_menu.append('<li class="notification"><a href="{{ url('job') }}' + '/' + employerNotification.jobSlug + '">' + employerNotification.content + '</li>');
            });
        </script>
    @endif
@endpush
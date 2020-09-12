@extends('layouts.app')
@section('content')
    <!-- Header start -->
    @include('includes.header')
    <!-- Header end -->
    <!-- Inner Page Title start -->
    @include('includes.inner_page_title', ['page_title'=>__($page_title)])
    <!-- Inner Page Title end -->
    <div class="listpgWraper">
        <div class="container">
        @include('flash::message')
        <!-- Job Header start -->
            <div class="job-header">
                <div class="jobinfo">
                    <div class="row">
                        <div class="col-md-8 col-sm-8">
                            <!-- Candidate Info -->
                            <div class="candidateinfo">
                                <div class="userPic">{{$user->printUserImage()}}</div>
                                <div class="title">
                                    {{$user->getName()}}
                                    @if((bool)$user->is_immediate_available)
                                        <sup style="font-size:12px; color:#090;">{{__('Immediate Available For Work')}}</sup>
                                    @endif
                                </div>
                                <div class="desi">{{$user->getLocation()}}</div>
                                <div class="loctext"><i class="fa fa-history"
                                                        aria-hidden="true"></i> {{__('Member Since')}}
                                    , {{$user->created_at->format('M d, Y')}}</div>
                                <div class="loctext"><i class="fa fa-map-marker"
                                                        aria-hidden="true"></i> {{$user->street_address}}</div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4">
                            <!-- Candidate Contact -->

                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="jobButtons">
                    @if(isset($job) && isset($company))
                        @if(Auth::guard('company')->check() && Auth::guard('company')->user()->isFavouriteApplicant($user->id, $job->id, $company->id))
                            <a href="{{route('remove.from.favourite.applicant', [$job_application->id, $user->id, $job->id, $company->id])}}"
                               class="btn"><i class="fa fa-floppy-o" aria-hidden="true"></i> {{__('Hired Applicant')}}
                            </a>
                        @else
                            <button onclick="hire_freelancer()" class="btn"><i class="fa fa-floppy-o"
                                                                               aria-hidden="true"></i> {{__('Hire This Applicant')}}
                            </button>
                        @endif
                    @endif
                    @if(Request::segment(3) > 0)
                        <a href="javascript:;" onclick="send_message()" class="btn"><i class="fa fa-envelope"
                                                                                       aria-hidden="true"></i> {{__('Send Message')}}
                        </a>
                    @endif
                </div>
            </div>

            <!-- Job Detail start -->
            <div class="row">
                <div class="col-md-8">
                    <!-- About Employee start -->
                    <div class="job-header">
                        <div class="contentbox">
                            <h3>{{__('About me')}}</h3>
                            <p>{{$user->getProfileSummary('summary')}}</p>
                        </div>
                    </div>

                    <!-- Education start -->
                    <div class="job-header">
                        <div class="contentbox">
                            <h3>{{__('Education')}}</h3>
                            <div class="" id="education_div"></div>
                        </div>
                    </div>

                    <!-- Experience start -->
                    <div class="job-header">
                        <div class="contentbox">
                            <h3>{{__('Experience')}}</h3>
                            <div class="" id="experience_div"></div>
                        </div>
                    </div>

                    <!-- Portfolio start -->
                    <div class="job-header">
                        <div class="contentbox">
                            <h3>{{__('Portfolio')}}</h3>
                            <div class="" id="projects_div"></div>
                        </div>
                    </div>

                    <!-- Portfolio start -->
                    <div class="job-header">
                        <div class="contentbox">
                            <h3 style="padding-bottom: 20px;">{{__('Work history and feedback')}}</h3>
                            <div class="" id="projects_div">
                                @foreach($projectFeedback as $o)
                                    @if($o->jobApply['isCandidateContractStatus'] == "close" && $o->jobApply['isEmployeerContractStatus'] == "close")
                                        <div class="project-review">
                                            <h4>{{ $o->jobDetails->title }}</h4>
                                            <div class="rating">
                                                <div class="row">
                                                    <div class="col-lg-1">
                                                        <div class="rateyo" data-rateyo-rating="{{ $o->rating }}"
                                                             data-rateyo-num-stars="5" data-rateyo-score="3">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3">
                                                    <span style="padding: 0;margin-top: -5px; margin-right: 3px; font-weight: bold;">
                                                        @if(strpos($o->rating, "."))
                                                            {{$o->rating}}0
                                                        @else
                                                            {{$o->rating}}.00
                                                        @endif
                                                    </span>
                                                        <span>{{\Carbon\Carbon::parse($o->created_at)->format('M Y')}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <p style="padding: 5px 0;">
                                                {{ $o->feedback }}
                                            </p>
                                        </div>
                                        <hr>
                                    @elseif($o->jobApply['isCandidateContractStatus'] == "close"
                                        && $o->jobApply['isEmployeerContractStatus'] == "open" ||
                                         $o->jobApply['isCandidateContractStatus'] == "open"
                                        && $o->jobApply['isEmployeerContractStatus'] == "close")
{{--                                        @php--}}
{{--                                            $date = \Carbon\Carbon::parse($o->jobApply['EmployerCloseContract']);--}}
{{--                                            $now = \Carbon\Carbon::now();--}}
{{--                                            echo $diff = $date->diffInDays($now) ."<br>";--}}
{{--                                        @endphp--}}
                                        @if($o->jobApply['EmployerCloseContract'] <= Carbon\Carbon::now()->subDays(90))
                                            <div class="project-review">
                                                <h4>{{ $o->jobDetails->title }}</h4>
                                                <div class="rating">
                                                    <div class="row">
                                                        <div class="col-lg-3">
                                                            <span>{{\Carbon\Carbon::parse($o->created_at)->format('M Y')}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <p style="padding: 5px 0;">
                                                    No feedback given
                                                </p>
                                            </div>
                                            <hr>
                                        @endif
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <!-- Candidate Detail start -->
                    <div class="job-header">
                        <div class="jobdetail">
                            <h3>{{__('Candidate Detail')}}</h3>
                            <ul class="jbdetail">

                                <li class="row">
                                    <div class="col-md-6 col-xs-6">{{__('Is Email Verified')}}</div>
                                    <div class="col-md-6 col-xs-6"><span>{{((bool)$user->verified)? 'Yes':'No'}}</span>
                                    </div>
                                </li>
                                <li class="row">
                                    <div class="col-md-6 col-xs-6">{{__('Immediate Available')}}</div>
                                    <div class="col-md-6 col-xs-6">
                                        <span>{{((bool)$user->is_immediate_available)? 'Yes':'No'}}</span></div>
                                </li>

                                <li style="display:none;" class="row">
                                    <div class="col-md-6 col-xs-6">{{__('Age')}}</div>
                                    <div class="col-md-6 col-xs-6"><span>{{$user->getAge()}} Years</span></div>
                                </li>
                                <li style="display:none;" class="row">
                                    <div class="col-md-6 col-xs-6">{{__('Gender')}}</div>
                                    <div class="col-md-6 col-xs-6"><span>{{$user->getGender('gender')}}</span></div>
                                </li>
                                <li style="display:none;" class="row">
                                    <div class="col-md-6 col-xs-6">{{__('Marital Status')}}</div>
                                    <div class="col-md-6 col-xs-6">
                                        <span>{{$user->getMaritalStatus('marital_status')}}</span></div>
                                </li>
                                <li class="row">
                                    <div class="col-md-6 col-xs-6">{{__('Experience')}}</div>
                                    <div class="col-md-6 col-xs-6">
                                        <span>{{$user->getJobExperience('job_experience')}}</span></div>
                                </li>
                                <li class="row">
                                    <div class="col-md-6 col-xs-6">{{__('Career Level')}}</div>
                                    <div class="col-md-6 col-xs-6">
                                        <span>{{$user->getCareerLevel('career_level')}}</span></div>
                                </li>
                                <li style="display:none;" class="row">
                                    <div class="col-md-6 col-xs-6">{{__('Current Salary')}}</div>
                                    <div class="col-md-6 col-xs-6"><span
                                                class="permanent">{{$user->current_salary}} {{$user->salary_currency}}</span>
                                    </div>
                                </li>
                                <li style="display:none;" class="row">
                                    <div class="col-md-6 col-xs-6">{{__('Expected Salary')}}</div>
                                    <div class="col-md-6 col-xs-6"><span
                                                class="freelance">{{$user->expected_salary}} {{$user->salary_currency}}</span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Google Map start -->
                    <div class="job-header">
                        <div class="jobdetail">
                            <h3>{{__('Skills')}}</h3>
                            <div id="skill_div"></div>
                        </div>
                    </div>

                    <div class="job-header">
                        <div class="jobdetail">
                            <h3>{{__('Languages')}}</h3>
                            <div id="language_div"></div>
                        </div>
                    </div>
                    <!-- Contact Company start -->
                    <div class="job-header">
                        <div class="jobdetail">
                            <h3 id="contact_applicant">{{__($form_title)}}</h3>
                            <div id="alert_messages"></div>
                            <?php
                            $from_name = $from_email = $from_phone = $subject = $message = $from_id = '';
                            if (isset($company)) {
                                $from_name = $company->name;
                                $from_email = $company->email;
                                $from_phone = $company->phone;
                                $from_id = $company->id;
                            }
                            if (Auth::guard('company')->check()) {
                                $from_name = Auth::guard('company')->user()->name;
                                $from_email = Auth::guard('company')->user()->email;
                                $from_phone = Auth::guard('company')->user()->phone;
                                $from_id = Auth::guard('company')->user()->id;
                            }
                            $from_name = old('name', $from_name);
                            $from_email = old('email', $from_email);
                            $from_phone = old('phone', $from_phone);
                            $subject = old('subject');
                            $message = old('message');
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="hireFreelancer" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    @include('order.pay_with_custom_paypal')
                </div>
            </div>

        </div>
    </div>
    <div class="modal fade" id="sendmessage" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <form action="" id="send-form">
                    @csrf
                    <input type="hidden" name="seeker_id" id="seeker_id" value="{{$user->id}}">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Send Message</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <textarea class="form-control" name="message" id="message" cols="10" rows="7"></textarea>
                            <input type="hidden" name="job_id" value="{{ Request::segment(3) }}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
    @include('includes.footer')
@endsection
@push('styles')
    <style type="text/css">
        .formrow iframe {
            height: 78px;
        }
    </style>
@endpush
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            /*$("#rateYo").rateYo({
                rating: 1,
                readOnly: true,
                onSet: function (r, rateYoInstance) {
                    rating = r;
                }
            });*/
            $(function () {
                $(".rateyo").rateYo({
                    readOnly: true,
                    starWidth: "10px",
                }).on("rateyo.change", function (e, data) {
                    var rating = data.rating;
                });
            });
            $(document).on('click', '#send_applicant_message', function () {
                var postData = $('#send-applicant-message-form').serialize();
                $.ajax({
                    type: 'POST',
                    url: "{{ route('contact.applicant.message.send') }}",
                    data: postData,
                    //dataType: 'json',
                    success: function (data) {
                        response = JSON.parse(data);
                        var res = response.success;
                        if (res == 'success') {
                            var errorString = '<div role="alert" class="alert alert-success">' + response.message + '</div>';
                            $('#alert_messages').html(errorString);
                            $('#send-applicant-message-form').hide('slow');
                            $(document).scrollTo('.alert', 2000);
                        } else {
                            var errorString = '<div class="alert alert-danger" role="alert"><ul>';
                            response = JSON.parse(data);
                            $.each(response, function (index, value) {
                                errorString += '<li>' + value + '</li>';
                            });
                            errorString += '</ul></div>';
                            $('#alert_messages').html(errorString);
                            $(document).scrollTo('.alert', 2000);
                        }
                    },
                });
            });
            showEducation();
            showProjects();
            showExperience();
            showSkills();
            showLanguages();
        });

        function showProjects() {
            $.post("{{ route('show.applicant.profile.projects', $user->id) }}", {
                user_id: {{$user->id}},
                _method: 'POST',
                _token: '{{ csrf_token() }}'
            })
                .done(function (response) {
                    $('#projects_div').html(response);
                });
        }

        function showExperience() {
            $.post("{{ route('show.applicant.profile.experience', $user->id) }}", {
                user_id: {{$user->id}},
                _method: 'POST',
                _token: '{{ csrf_token() }}'
            })
                .done(function (response) {
                    $('#experience_div').html(response);
                });
        }

        function showEducation() {
            $.post("{{ route('show.applicant.profile.education', $user->id) }}", {
                user_id: {{$user->id}},
                _method: 'POST',
                _token: '{{ csrf_token() }}'
            })
                .done(function (response) {
                    $('#education_div').html(response);
                });
        }

        function showLanguages() {
            $.post("{{ route('show.applicant.profile.languages', $user->id) }}", {
                user_id: {{$user->id}},
                _method: 'POST',
                _token: '{{ csrf_token() }}'
            })
                .done(function (response) {
                    $('#language_div').html(response);
                });
        }

        function showSkills() {
            $.post("{{ route('show.applicant.profile.skills', $user->id) }}", {
                user_id: {{$user->id}},
                _method: 'POST',
                _token: '{{ csrf_token() }}'
            })
                .done(function (response) {
                    $('#skill_div').html(response);
                });
        }

        function hire_freelancer() {
            const el = document.createElement('div')
            el.innerHTML = "Please <a class='btn' href='{{route('login')}}' onclick='set_session()'>log in</a> as a Employer and try again."
            @if(null!==(Auth::guard('company')->user()))

               @if($job->job_rate_type==0)
               // swal({
               //    title: 'Are you sure?',
               //    text: "Are you sure want to hire this candidate ?",
               //    icon: 'warning',
               //    showCancelButton: true,
               //    showConfirmButton:true,
               //    confirmButtonText: 'Yes, delete it!'
               //  }).then((result) => {
               //    if (result.value) {
               //      swal(
               //        'Successfully Hired!',
               //        'You have hired this candidate for our job',
               //        'success'
               //      )
               //    }
               //  })
               if(confirm('Are you sure hire this candidate for Job ?'))
               {
                    $.post("{{ route('hire.candidate', $job_application->id) }}", {
                        job_application_id: {{$job_application->id}},
                        job_candidate_id:{{$user->id}},
                        job_employee_id:{{Auth::guard('company')->user()->id}},
                        _method: 'POST',
                        _token: '{{ csrf_token() }}'
                    }).done(function (response) {
                        if(response)
                        {
                            window.location.reload();
                            location.reload();
                        }
                    });
                }
               
               @elseif($job->job_rate_type==1)
                 $('#hireFreelancer').modal('show');
               @else
                 swal({
                    title: "Job Rate type is not selected for this job. Please select",
                    content: el,
                    icon: "error",
                    button: "OK",
                });
               @endif  
               
            @else
            swal({
                title: "You are not Loged in",
                content: el,
                icon: "error",
                button: "OK",
            });
            @endif
        }

        function send_message() {
            const el = document.createElement('div')
            el.innerHTML = "Please <a class='btn' href='{{route('login')}}' onclick='set_session()'>log in</a> as a Employer and try again."
            @if(null!==(Auth::guard('company')->user()))
            $('#sendmessage').modal('show');
            @else
            swal({
                title: "You are not Loged in",
                content: el,
                icon: "error",
                button: "OK",
            });
            @endif
        }

        if ($("#send-form").length > 0) {
            $("#send-form").validate({
                validateHiddenInputs: true,
                ignore: "",

                rules: {
                    message: {
                        required: true,
                        maxlength: 5000
                    },
                },
                messages: {

                    message: {
                        required: "Message is required",
                    }

                },
                submitHandler: function (form) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    @if(null !== (Auth::guard('company')->user()))
                    $.ajax({
                        url: "{{route('submit-message-seeker')}}",
                        type: "POST",
                        data: $('#send-form').serialize(),
                        success: function (response) {
                            $("#send-form").trigger("reset");
                            $('#sendmessage').modal('hide');
                            swal({
                                title: "Success",
                                text: response["msg"],
                                icon: "success",
                                button: "OK",
                            });
                        }
                    });
                    @endif
                }
            })
        }
    </script>
@endpush
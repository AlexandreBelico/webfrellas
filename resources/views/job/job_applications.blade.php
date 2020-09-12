@extends('layouts.app')
@section('content')
    <!-- Header start -->
    @include('includes.header')
    <!-- Header end -->
    <!-- Inner Page Title start -->
    @include('includes.inner_page_title', ['page_title'=>__('Job Applications')])
    <!-- Inner Page Title end -->
    <div class="listpgWraper">
        <div class="container">
            @include('flash::message')
            <div class="row">
                @include('includes.company_dashboard_menu')

                <div class="col-md-9 col-sm-8">
                    <div class="myads">
                        <h3>{{__('Job Applications')}}</h3>
                        <ul class="searchList">
                            <!-- job start -->

                            @if(isset($job_applications) && count($job_applications))
                                @foreach($job_applications as $job_application)
                                    @php
                                        $user = $job_application->getUser();

                                        $job = $job_application->getJob();

                                        $company = $job->getCompany();

                                    @endphp
                                    @if($job_application !== null  && $user !== null && $job !== null && $company !== null )
                                        <li>
                                            <div class="row">
                                                <div class="col-md-5 col-sm-5">
                                                    <div class="jobimg">{{$user->printUserImage(100, 100)}}</div>
                                                    <div class="jobinfo">
                                                        <h3>
                                                            <a href="{{route('applicant.profile', $job_application->id)}}">{{$user->getName()}}</a>
                                                        </h3>
                                                        <div class="location"> {{$user->getLocation()}}</div>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                </div>

                                                {{-- {{ dd($job_application['isCandidateContractStatus'])}} --}}

                                                <div class="col-md-4 col-sm-4">
                                                    <div class="minsalary">{{$job_application->expected_salary}} {{$job_application->salary_currency}}
                                                        <span>/ {{$job->getSalaryPeriod('salary_period')}}</span></div>
                                                </div>
                                                <div class="col-md-3 col-sm-3">
                                                    <div class="listbtn">
                                                        <a href="{{route('applicant.profile.job', [$job_application->id, $job->id])}}">{{__('View Profile')}}</a>
                                                    </div>
                                                    <input type="hidden" class="form-control" id="feedback-id"
                                                           value=""/>
                                                    @if($job_application['isEmployeerContractStatus'] != "" && $job_application['isEmployeerContractStatus'] == "open")
{{--                                                    @php--}}
{{--                                                        $date = \Carbon\Carbon::parse($job_application['CandidateCloseContract']);--}}
{{--                                                        $now = \Carbon\Carbon::now();--}}
{{--                                                        echo $diff = $date->diffInDays($now);--}}
{{--                                                    @endphp--}}
                                                        @if($job_application['isCandidateContractStatus'] == "close")
                                                            @if($job_application['CandidateCloseContract'] >= Carbon\Carbon::now()->subDays(90))
                                                                <div class="listbtn">
                                                                    <button type="button" class="btn btn-danger btn-block" data-id="{{ $job->id }}"
                                                                            data-toggle="modal" data-target="#closeContract--{{ $job->id }}"
                                                                            onclick="addFeedback({{ $job->id }})">
                                                                        Close Contract
                                                                    </button>
                                                                </div>
                                                            @endif
                                                        @else
                                                            <div class="listbtn">
                                                                <button type="button" class="btn btn-danger btn-block" data-id="{{ $job->id }}"
                                                                        data-toggle="modal" data-target="#closeContract--{{ $job->id }}"
                                                                        onclick="addFeedback({{ $job->id }})">
                                                                    Close Contract
                                                                </button>
                                                            </div>
                                                        @endif
                                                    @elseif($job_application['isEmployeerContractStatus'] == "close"  && $job_application['isCandidateContractStatus'] == "open")

{{--                                                    @php--}}
{{--                                                        $date = \Carbon\Carbon::parse($job_application['EmployerCloseContract']);--}}
{{--                                                        $now = \Carbon\Carbon::now();--}}
{{--                                                        echo $diff = $date->diffInDays($now);--}}
{{--                                                    @endphp--}}
                                                        @if($job_application['EmployerCloseContract'] >= Carbon\Carbon::now()->subDays(90))
                                                            <div class="listbtn">
                                                                <button type="button" class="btn btn-success btn-block"
                                                                        data-toggle="modal" data-target="#closeContract"
                                                                        onclick="editFeedback({{$user->id}}, {{ $job->id }},{{$company->id}})">
                                                                    Update Review
                                                                </button>
                                                            </div>
                                                        @endif
                                                    @endif
                                                <!-- Modal -->
                                                    <div class="modal fade" id="closeContract--{{ $job->id }}" tabindex="-1"
                                                         role="dialog" aria-labelledby="closeContractLabel"
                                                         aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close"
                                                                            data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                    <h5 class="modal-title"
                                                                        id="closeContractLabel--{{ $job->id }}">
                                                                        Give Review</h5>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form class="form-closeContract"
                                                                          name="form-closeContract">
                                                                        <div class="row">
                                                                            <div class="col-lg-12">
                                                                                <div class="form-group">
                                                                                    <label style="margin-bottom: 10px;">
                                                                                        <b>Rating:</b>
                                                                                    </label>
                                                                                    <div id="rateYo--{{ $job->id }}"></div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-lg-12">
                                                                                <div class="form-group">
                                                                                    <label style="margin-bottom: 10px;">
                                                                                        <b>Give Feedback:</b>
                                                                                    </label>
                                                                                    <textarea class="form-control" id="feedback--{{ $job->id }}"></textarea>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                            data-dismiss="modal">Close
                                                                    </button>
                                                                    <input type="hidden" id="userName--{{ $job->id }}"
                                                                           value="{{$user->name}}">
                                                                    <input type="hidden" id="userEmail--{{ $job->id }}"
                                                                           value="{{$user->email}}">
                                                                    <button type="button" class="btn btn-primary"
                                                                            onclick="saveReview({{$user->id}},
                                                                            {{ $job->id }},{{$company->id}})">
                                                                        Give Review
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <p>{{str_limit($user->getProfileSummary('summary'),150,'...')}}</p>
                                        </li>
                                        <!-- job end -->
                                    @endif
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('includes.footer')
@endsection
@push('scripts')
    <script>
        var rating;

        function addFeedback(jobId) {
            $("#rateYo--"+jobId).rateYo({
                rating: 1,
                halfStar: true,
                onSet: function (r, rateYoInstance) {
                    rating = r;
                }
            });
        }

        function saveReview(userId, jobId, companyId) {
            var feedBackId = $('#feedback-id').val();
            var userName = $('#userName--'+jobId).val();
            var userEmail = $('#userEmail--'+jobId).val();
            var feedback = $('#feedback--'+jobId).val();
            var type = 'employer';

            if (feedBackId == "") {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ \Illuminate\Support\Facades\URL::to('feedback') }}",
                    type: "POST",
                    data: {
                        'user_id': userId,
                        'job_id': jobId,
                        'company_id': companyId,
                        'rating': rating,
                        'feedback': feedback,
                        'type': type,
                        'userName': userName,
                        'userEmail': userEmail
                    },
                    success: function (response) {
                        // location.reload();
                    },
                });
            } else {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ \Illuminate\Support\Facades\URL::to('update_feedback') }}" + '/' + userId + '/' + jobId + '/' + companyId,
                    type: "PUT",
                    data: {
                        'user_id': userId,
                        'job_id': jobId,
                        'company_id': companyId,
                        'rating': rating,
                        'feedback': feedback,
                        'type': type,
                        'userName': userName,
                        'userEmail': userEmail
                    },
                    success: function (response) {
                        // location.reload();
                    },
                });
            }
        }

        function editFeedback(userId, jobId, companyId) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ \Illuminate\Support\Facades\URL::to('editfeedback') }}" + '/' + userId + '/' + jobId + '/' + companyId,
                type: "GET",
                success: function (response) {
                    $('#closeContract--'+jobId).modal("show");
                    var feedback = $('#feedback--'+jobId).val(response.feedback.feedback);
                    var feedbackId = $('#feedback-id').val(response.feedback.id);
                    rating = response.feedback.rating;
                    $("#rateYo--"+jobId).rateYo({
                        rating: rating,
                        halfStar: true,
                        onSet: function (r, rateYoInstance) {
                            rating = r;
                        }
                    });
                },
            });
        }
    </script>
@endpush
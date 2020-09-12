@extends('layouts.app')
@push('styles')

@endpush
@section('content')
    <!-- Header start -->
    @include('includes.header')
    <!-- Header end -->
    <!-- Inner Page Title start -->
    @include('includes.inner_page_title', ['page_title'=>__('Applied Jobs')])
    <!-- Inner Page Title end -->
    <div class="listpgWraper">
        <div class="container">
            @include('flash::message')
            <div class="row">
                @include('includes.user_dashboard_menu')

                <div class="col-md-9 col-sm-8">
                    <div class="myads">
                        <h3>{{__('Applied Jobs')}}</h3>
                        <ul class="searchList">
                            <!-- job start -->
                            @if(isset($jobs) && count($jobs))
                                @foreach($jobs as $job)
                                    @php $company = $job->getCompany(); @endphp
                                    @if(null !== $company)
                                        <li>
                                            <div class="row">
                                                <div class="col-md-8 col-sm-8">
                                                    <div class="jobimg">{{$company->printCompanyImage()}}</div>
                                                    <div class="jobinfo">
                                                        <h3><a href="{{route('job.detail', [$job->slug])}}"
                                                               title="{{$job->title}}">{{$job->title}}</a></h3>
                                                        <div class="companyName"><a
                                                                    href="{{route('company.detail', $company->slug)}}"
                                                                    title="{{$company->name}}">{{$company->name}}</a>
                                                        </div>
                                                        <div class="location">
                                                            <label class="fulltime"
                                                                   title="{{$job->getJobShift('job_shift')}}">
                                                                {{$job->getJobShift('job_shift')}}
                                                            </label> - <span>{{$job->getCity('city')}}</span></div>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                </div>
                                                <div class="col-md-4 col-sm-4">
                                                    <div class="listbtn">
                                                        <a href="{{route('job.detail', [$job->slug])}}">
                                                            {{__('View Details')}}
                                                        </a>
                                                    </div>

                                                    <div class="listbtn">
                                                        @if($job->hiredStatus==0)
                                                            <button type="button" disabled="disabled"
                                                                    class="btn btn-danger btn-block">Pending
                                                            </button>
                                                        @else
                                                            <input type="hidden" class="form-control" id="feedback-id"
                                                                   value=""/>
                                                            <button type="button" class="btn btn-success btn-block">
                                                                Hired
                                                            </button>
                                                            @if($job->appliedUser->isCandidateContractStatus != "" && $job->appliedUser->isCandidateContractStatus == "open")
                                                                @if($job->appliedUser->isCandidateContractStatus == "close")
                                                                    @if($job->appliedUser['EmployerCloseContract'] >= Carbon\Carbon::now()->subDays(90))
                                                                        <button type="button" class="btn btn-danger btn-block" data-id="{{ $job->id }}"
                                                                                data-toggle="modal" data-target="#closeContract--{{ $job->id }}"
                                                                                onclick="addFeedback({{ $job->id }})">
                                                                            Close Contract
                                                                        </button>
                                                                    @endif
                                                                @else
                                                                    <button type="button" class="btn btn-danger btn-block" data-id="{{ $job->id }}"
                                                                            data-toggle="modal" data-target="#closeContract--{{ $job->id }}"
                                                                            onclick="addFeedback({{ $job->id }})">
                                                                        Close Contract
                                                                    </button>
                                                                @endif
                                                            @elseif($job->appliedUser->isEmployeerContractStatus == "open"  && $job->appliedUser->isCandidateContractStatus == "close")
{{--                                                                @php--}}
{{--                                                                    $date = \Carbon\Carbon::parse($job->appliedUser['EmployerCloseContract']);--}}
{{--                                                                    $now = \Carbon\Carbon::now();--}}
{{--                                                                    echo $diff = $date->diffInDays($now);--}}
{{--                                                                @endphp--}}
                                                                @if($job->appliedUser['CandidateCloseContract'] >= Carbon\Carbon::now()->subDays(90))
                                                                    <button type="button" class="btn btn-info btn-block"
                                                                            data-toggle="modal" data-target="#closeContract"
                                                                            onclick="editFeedback({{$company->id}},
                                                                            {{ $job->appliedUser->job_id }},
                                                                            {{$company->id}})">
                                                                        Update Feedback
                                                                    </button>
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
                                                                                    data-dismiss="modal"
                                                                                    aria-label="Close">
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
                                                                                                <strong>Rating:</strong></label>
                                                                                            <div id="rateYo--{{ $job->id }}"></div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-lg-12">
                                                                                        <div class="form-group">
                                                                                            <label style="margin-bottom: 10px;">
                                                                                                Give Feedback:
                                                                                            </label>
                                                                                            <textarea class="form-control" id="feedback--{{ $job->id }}"></textarea>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button"
                                                                                    class="btn btn-secondary"
                                                                                    data-dismiss="modal">Close
                                                                            </button>
                                                                            <input type="hidden"
                                                                                   value="{{$company->email}}"
                                                                                   id="companyEmail--{{ $job->id }}">
                                                                            <input type="hidden"
                                                                                   value="{{$company->name}}"
                                                                                   id="companyName--{{ $job->id }}">
                                                                            <button type="button"
                                                                                    class="btn btn-primary"
                                                                                    id="btn-review"
                                                                                    onclick="saveReview({{$company->id}}, {{ $job->id }},{{$company->id}})">
                                                                                Give Review
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <p>{!! str_limit(strip_tags($job->description), 150, '...') !!}</p>
                                        </li>
                                        <!-- job end -->
                                    @endif
                                @endforeach
                            @endif
                        </ul>
                        {{ $jobs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('includes.footer')
@endsection
@push('scripts')
    @include('includes.immediate_available_btn')
    <script>
        var rating = 1;

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

            var companyEmail = $('#companyEmail--'+jobId).val();
            var companyName = $('#companyName--'+jobId).val();
            var feedbackId = $('#feedback-id').val();
            var feedback = $('#feedback--'+jobId).val();

            console.log("feedback",feedback);
            var type = 'candidate';
            if (feedbackId == "") {
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
                        'companyEmail': companyEmail,
                        'companyName': companyName,
                    },
                    success: function (response) {
                        location.reload();
                    },
                });
            } else {
                console.log("feedback123",feedback);
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
                        'companyEmail': companyEmail,
                        'companyName': companyName,
                    },
                    success: function (response) {
                        location.reload();
                    }
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
                    console.log(jobId,response.feedback.feedback,response.feedback.id);

                    $('#closeContract--'+jobId).modal("show");
                    $('#feedback--'+jobId).val(response.feedback.feedback);
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
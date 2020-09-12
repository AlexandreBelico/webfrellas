@extends('layouts.app')
@section('content')
    <!-- Header start -->
    @include('includes.header')
    <!-- Header end -->
    <!-- Inner Page Title start -->
    @include('includes.inner_page_title', ['page_title'=>__('Notification')])
    <section class="notifications" id="notifications">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="notification-list">
                        <ul class="load-notification" id="load-notification">
                            {{ csrf_field() }}
                            {{--@foreach($notification as $o)
                            <li>
                                <div class="notification">
                                    <a href="{{ url('job').'/'.$o->getJobDetails->slug }}" style="color: #000;">{{ $o->content }}</a>
                                    <div class="pull-right">
                                        Jun 24
                                    </div>
                                </div>
                            </li>
                            @endforeach--}}
                        </ul>
                    </div>
                </div>
            </div>
            {{--<div class="row" style="margin: 30px 0;">
                <div class="col-sm-12 center-block">
                    <button class="btn btn-info center-block">Load More</button>
                </div>
            </div>--}}
        </div>
    </section>
    @include('includes.footer')
@endsection
@push('styles')
    <style>
        .notifications {
            padding: 50px 0;
        }

        .notifications .notification-list {
            margin: 0 50px;
        }

        .notifications .notification-list ul > li {
            padding: 10px 0;
            border-bottom: 1px solid #8b96a4;
        }

        .notification-list ul > li a {
            color: #000;
            text-decoration: none;
        }

        .notifications .notification-list ul > li:last-child {
            border-bottom: 0;
        }

        .btn {
            border-radius: 0px;
        }
    </style>
@endpush
@push('scripts')
    <script>
        $(document).ready(function () {

            var _token = $('input[name="_token"]').val();

            load_data('', _token);

            function load_data(id = "", _token) {
                $.ajax({
                    url: "{{ route('notification.load_notification') }}",
                    method: "POST",
                    data: {id: id, _token: _token},
                    success: function (data) {
                        console.log("====>>>>>>No Load More",data);

                        $('#load_more_button').remove();
                        $('#load-notification').append(data);
                    }
                })
            }

            $(document).on('click', '#load_more_button', function () {
                let id = $(this).data('id');
                console.log("====>>>>ID......",id);
                $('#load_more_button').html('<b>Loading...</b>');
                load_data(id, _token);
            });

        });
    </script>
@endpush
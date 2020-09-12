<div class="header">
    <div class="container">
        <div class="row">
            <div class="col-md-2 col-sm-3 col-xs-12"><a href="<?php echo e(url('/')); ?>" class="logo"><img
                            src="<?php echo e(asset('/public/')); ?>sitesetting_images/thumb/<?php echo e($siteSetting->site_logo); ?>"
                            alt="<?php echo e($siteSetting->site_name); ?>"/></a>
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
                            <li class="<?php echo e(Request::url() == route('index') ? 'active' : ''); ?>"><a
                                        href="<?php echo e(url('/')); ?>"><?php echo e(__('Home')); ?></a></li>
                            <?php if(!Auth::guard('company')->check()): ?>
                                <li class="<?php echo e(Request::url()); ?>"><a href="<?php echo e(url('/jobs')); ?>"><?php echo e(__('Jobs')); ?></a></li>
                                <li class="<?php echo e(Request::url()); ?>"><a href="<?php echo e(url('/companies')); ?>"><?php echo e(__('Companies')); ?></a>
                                </li>
                            <?php endif; ?>
                            <?php $__currentLoopData = $show_in_top_menu; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $top_menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <?php $cmsContent = App\CmsContent::getContentBySlug($top_menu->page_slug); ?>
                            <li class="<?php echo e(Request::url() == route('cms', $top_menu->page_slug) ? 'active' : ''); ?>"><a
                                        href="<?php echo e(route('cms', $top_menu->page_slug)); ?>"><?php echo e(isset($cmsContent->page_title) ? $cmsContent->page_title : ''); ?></a>
                            </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <li class="<?php echo e(Request::url() == route('contact.us') ? 'active' : ''); ?>"><a
                                        href="<?php echo e(route('contact.us')); ?>"><?php echo e(__('Contact us')); ?></a></li>
                            <?php if(Auth::check()): ?>
                                <li class="<?php echo e(Request::url() == route('my.chats') ? 'active' : ''); ?>"><a
                                            href="<?php echo e(route('my.chats')); ?>"><?php echo e(__('Messages')); ?></a></li>

                            <?php endif; ?>
                            <?php if(Auth::check()): ?>
                                <li class="dropdown userbtn"><a href=""><?php echo e(Auth::user()->printUserImage()); ?></a>
                                    <ul class="dropdown-menu">
                                        <li><a href="<?php echo e(route('home')); ?>"><i class="fa fa-tachometer"
                                                                           aria-hidden="true"></i> <?php echo e(__('Dashboard')); ?>

                                            </a></li>
                                        <li><a href="<?php echo e(route('my.profile')); ?>"><i class="fa fa-user"
                                                                                   aria-hidden="true"></i> <?php echo e(__('My Profile')); ?>

                                            </a></li>
                                        <li><a href="<?php echo e(route('view.public.profile', Auth::user()->id)); ?>"><i
                                                        class="fa fa-eye"
                                                        aria-hidden="true"></i> <?php echo e(__('View Public Profile')); ?></a></li>
                                        <li><a href="<?php echo e(route('my.job.applications')); ?>"><i class="fa fa-desktop"
                                                                                            aria-hidden="true"></i> <?php echo e(__('My Job Applications')); ?>

                                            </a></li>
                                        <!-- Below line number 33 added by Hetal -->
                                        <li><a href="<?php echo e(route('job.timesheets')); ?>"><i class="glyphicon glyphicon-time"
                                                                                       aria-hidden="true"></i> <?php echo e(__('My Timesheets')); ?>

                                            </a></li>
                                        <li><a href="<?php echo e(route('job.paymentdetails')); ?>"><i class="fa fa-cc-paypal"
                                                                                       aria-hidden="true"></i> <?php echo e(__('My payments')); ?>

                                            </a></li>

                                        <li><a href="<?php echo e(route('logout')); ?>"
                                               onclick="event.preventDefault(); document.getElementById('logout-form-header').submit();"><i
                                                        class="fa fa-sign-out" aria-hidden="true"></i> <?php echo e(__('Logout')); ?>

                                            </a></li>
                                        <form id="logout-form-header" action="<?php echo e(route('logout')); ?>" method="POST"
                                              style="display: none;">
                                            <?php echo e(csrf_field()); ?>

                                        </form>
                                    </ul>
                                </li>
                            <?php endif; ?>
                            <?php if(Auth::guard('company')->check()): ?>
                                <li class="postjob"><a href="<?php echo e(route('post.job')); ?>"><?php echo e(__('Post a job')); ?></a></li>
                                <li class=""><a href="<?php echo e(route('companychats.messages')); ?>"><?php echo e(__('Messages')); ?></a></li>
                                <li class="dropdown userbtn"><a
                                            href=""><?php echo e(Auth::guard('company')->user()->printCompanyImage()); ?></a>
                                    <ul class="dropdown-menu">
                                        <li><a href="<?php echo e(route('company.home')); ?>"><i class="fa fa-tachometer"
                                                                                   aria-hidden="true"></i> <?php echo e(__('Dashboard')); ?>

                                            </a></li>
                                        <li><a href="<?php echo e(route('company.profile')); ?>"><i class="fa fa-user"
                                                                                        aria-hidden="true"></i> <?php echo e(__('View Public Profile')); ?>

                                            </a></li>
                                        <li><a href="<?php echo e(route('post.job')); ?>"><i class="fa fa-desktop"
                                                                                 aria-hidden="true"></i> <?php echo e(__('Post Job')); ?>

                                            </a></li>
                                        <!-- Below line number 48 added by Hetal -->
                                        <li><a href="<?php echo e(route('posted.jobs')); ?>"><i class="glyphicon glyphicon-time"
                                                                                    aria-hidden="true"></i> <?php echo e(__('Timesheets')); ?>

                                            </a></li>
                                        <li><a href="<?php echo e(route('company.messages')); ?>"><i class="fa fa-envelope-o"
                                                                                       aria-hidden="true"></i> <?php echo e(__('Messages')); ?>

                                            </a></li>
                                        <li><a href="<?php echo e(route('company.logout')); ?>"
                                               onclick="event.preventDefault(); document.getElementById('logout-form-header1').submit();"><?php echo e(__('Logout')); ?></a>
                                        </li>
                                        <form id="logout-form-header1" action="<?php echo e(route('company.logout')); ?>"
                                              method="POST" style="display: none;">
                                            <?php echo e(csrf_field()); ?>

                                        </form>
                                    </ul>
                                </li>
                            <?php endif; ?> <?php if(!Auth::user() && !Auth::guard('company')->user()): ?>
                                <li class="postjob dropdown"><a href="<?php echo e(route('login')); ?>"><?php echo e(__('Sign in')); ?> <span
                                                class="caret"></span></a>

                                    <!-- dropdown start -->

                                    <ul class="dropdown-menu">
                                        <li><a href="<?php echo e(route('login')); ?>"><?php echo e(__('Sign in')); ?></a></li>
                                        <li><a href="<?php echo e(route('register')); ?>"><?php echo e(__('Register')); ?></a></li>
                                    </ul>

                                    <!-- dropdown end -->

                                </li>
                            <?php endif; ?>
                            <?php if(Auth::user() || Auth::guard('company')->user()): ?>
                                <li class="dropdown dropdown-notifications">
                                    <a href="#notifications-panel" class="dropdown-toggle" data-toggle="dropdown">
                                        <i data-count="<?php if(isset($notificationCount) && $notificationCount != 0): ?> <?php echo e($notificationCount); ?> <?php else: ?> 0 <?php endif; ?>" class="glyphicon glyphicon-bell notification-icon"></i>
                                    </a>
                                    <div class="dropdown-container" style="width: 275px;">
                                        <div class="dropdown-toolbar">
                                            
                                            <h3 class="dropdown-toolbar-title">Notifications (<span class="notif-count"><?php if(isset($notificationCount) && $notificationCount != 0): ?> <?php echo e($notificationCount); ?> <?php else: ?> 0 <?php endif; ?></span>)
                                            </h3>
                                        </div>
                                        <ul class="dropdown-menu" id="dropdown-menu">
                                            <?php if(isset($notification)): ?>
                                                <?php $__currentLoopData = $notification; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <li class="notification">
                                                        <a href="<?php echo e(url('job').'/'.$o->getJobDetails->slug); ?>"><?php echo e($o->content); ?></a>
                                                    </li>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>
                                        </ul>
                                        <div class="dropdown-footer text-center">
                                            <a href="<?php echo e(route('notification.list')); ?>" class="btn btn-block">
                                                View All
                                            </a>
                                        </div>
                                    </div>
                                </li>
                            <?php endif; ?>
                            <li class="dropdown userbtn"><a href="<?php echo e(url('/')); ?>"><img src="<?php echo e(asset('/public/')); ?>images/lang.png"
                                                                                     alt="" class="userimg"/></a>
                                <ul class="dropdown-menu">
                                    <?php $__currentLoopData = $siteLanguages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $siteLang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><a href="javascript:;"
                                               onclick="event.preventDefault(); document.getElementById('locale-form-<?php echo e($siteLang->iso_code); ?>').submit();"><?php echo e($siteLang->native); ?></a>
                                            <form id="locale-form-<?php echo e($siteLang->iso_code); ?>"
                                                  action="<?php echo e(route('set.locale')); ?>" method="POST"
                                                  style="display: none;">
                                                <?php echo e(csrf_field()); ?>

                                                <input type="hidden" name="locale" value="<?php echo e($siteLang->iso_code); ?>"/>
                                                <input type="hidden" name="return_url" value="<?php echo e(url()->full()); ?>"/>
                                                <input type="hidden" name="is_rtl" value="<?php echo e($siteLang->is_rtl); ?>"/>
                                            </form>
                                        </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
<?php $__env->startPush('scripts'); ?>
    <?php if(Auth::check() && !Auth::guard('company')->check()): ?>
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
                    dropdown_menu.append('<li class="notification"><a href="<?php echo e(url('job')); ?>' + '/' + hiredCandidateNotification.jobSlug + '">' + hiredCandidateNotification.content + '</li>');
                });
            });
        </script>
    <?php else: ?>
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
                dropdown_menu.append('<li class="notification"><a href="<?php echo e(url('job')); ?>' + '/' + employerNotification.jobSlug + '">' + employerNotification.content + '</li>');
            });
        </script>
    <?php endif; ?>
<?php $__env->stopPush(); ?>
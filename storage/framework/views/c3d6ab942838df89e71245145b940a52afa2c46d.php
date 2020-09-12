
<div class="col-md-3 col-sm-4">
    <ul class="usernavdash">
        <li class="active"><a href="<?php echo e(route('company.home')); ?>"><i class="fa fa-tachometer" aria-hidden="true"></i> <?php echo e(__('Dashboard')); ?></a></li>
        <li><a href="<?php echo e(route('company.profile')); ?>"><i class="fa fa-user" aria-hidden="true"></i> <?php echo e(__('Edit Profile')); ?></a></li>
        <li><a href="<?php echo e(route('company.detail', Auth::guard('company')->user()->slug)); ?>"><i class="fa fa-user" aria-hidden="true"></i> <?php echo e(__('View Profile')); ?></a></li>
        <li><a href="<?php echo e(route('post.job')); ?>"><i class="fa fa-desktop" aria-hidden="true"></i> <?php echo e(__('Post Job')); ?></a></li>
        <li><a href="<?php echo e(route('posted.jobs')); ?>"><i class="fa fa-desktop" aria-hidden="true"></i> <?php echo e(__('All Jobs')); ?></a></li>
        <!-- Below line is added by Hetal(line no : 9) -->
        <li><a href="<?php echo e(route('jobs.development.status')); ?>"><i class="fa fa-desktop" aria-hidden="true"></i> <?php echo e(__('Development Status')); ?></a></li>
        <!-- <li><a href="<?php echo e(route('company.messages')); ?>"><i class="fa fa-envelope-o" aria-hidden="true"></i> <?php echo e(__('Messages')); ?></a></li> -->
    
       <li><a href="<?php echo e(route('companychats.messages')); ?>"><i class="fa fa-comments" aria-hidden="true"></i> <?php echo e(__('Chat Messages')); ?>(<span id="my_msgcan_no"></span>)</a></li>
        <li><a href="<?php echo e(route('company.followers')); ?>"><i class="fa fa-user-o" aria-hidden="true"></i> <?php echo e(__('Company Followers')); ?></a></li>
        <li><a href="<?php echo e(route('company.logout')); ?>" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa fa-sign-out" aria-hidden="true"></i> <?php echo e(__('Logout')); ?></a>
            <form id="logout-form" action="<?php echo e(route('company.logout')); ?>" method="POST" style="display: none;"><?php echo e(csrf_field()); ?></form>
        </li>
    </ul>
    <div class="row">
        <div class="col-md-12"><?php echo $siteSetting->dashboard_page_ad; ?></div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="https://js.pusher.com/5.1/pusher.min.js"></script>
<script type="text/javascript">
     var receiver_id = '';
  var my_id = "<?php echo e(Auth::guard('company')->user()->id); ?>";

    $(document).ready(function() {
         var temp = '<?php echo e(App\Helpers\DataArrayHelper::TotalMessagesCountEmp(Auth::guard('company')->user()->id)); ?>';
        $('#my_msgcan_no').html(temp);


        var base_url = "<?php echo e(env('APP_URL')); ?>";
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });

        // Enable pusher logging - don't include this in production
        Pusher.logToConsole = true;

        var pusher = new Pusher('d0c7990bbda7deb1300c', {
          cluster: 'ap2',
          encrypted: false,
        });

        var channel = pusher.subscribe('my-channel');
        channel.bind('my-event', function(data) {
           console.log(data)
          var t = parseInt(temp)+1;
          //alert(t);
          $('#my_msgcan_no').html(t);
          temp = t;
        
        });
    });
</script>
<?php $__env->stopPush(); ?>
<ul class="row profilestat">
    <li class="col-md-2 col-sm-4 col-xs-6">
        <div class="inbox"> <i class="fa fa-eye" aria-hidden="true"></i>
            <h6><?php echo e(Auth::user()->num_profile_views); ?></h6>
            <strong><?php echo e(__('Profile Views')); ?></strong> </div>
    </li>
</ul>
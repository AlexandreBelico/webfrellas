<?php $__env->startSection('content'); ?>
<!-- Header start -->
<?php echo $__env->make('includes.header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<!-- Header end --> 
<!-- Inner Page Title start -->
<?php echo $__env->make('includes.inner_page_title', ['page_title'=>__('Login')], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<!-- Inner Page Title end -->
<div class="listpgWraper">
    <div class="container">
        <?php echo $__env->make('flash::message', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="userccount">
                    <div class="userbtns">
                        <ul class="nav nav-tabs">
                            <?php
                            $c_or_e = old('candidate_or_employer', 'candidate');
                            ?>
                            <li class="<?php echo e(($c_or_e == 'candidate')? 'active':''); ?>"><a data-toggle="tab" href="#candidate" aria-expanded="true"><?php echo e(__('Candidate')); ?></a></li>
                            <li class="<?php echo e(($c_or_e == 'employer')? 'active':''); ?>"><a data-toggle="tab" href="#employer" aria-expanded="false"><?php echo e(__('Employer')); ?></a></li>
                        </ul>
                    </div>
                    <div class="tab-content">
                        <div id="candidate" class="formpanel tab-pane fade <?php echo e(($c_or_e == 'candidate')? 'active in':''); ?>">
                            <div class="socialLogin">
                                        <h5><?php echo e(__('Login with Social')); ?></h5>
                                        <a href="<?php echo e(url('login/jobseeker/facebook')); ?>" class="fb"><i class="fa fa-facebook" aria-hidden="true"></i></a><a href="<?php echo e(url('login/jobseeker/twitter')); ?>" class="tw"><i class="fa fa-twitter" aria-hidden="true"></i></a> </div>
                            <form class="form-horizontal" method="POST" action="<?php echo e(route('login')); ?>">
                                <?php echo e(csrf_field()); ?>

                                <input type="hidden" name="candidate_or_employer" value="candidate" />
                                <div class="formpanel">
                                    <div class="formrow<?php echo e($errors->has('email') ? ' has-error' : ''); ?>">
                                        <input id="email" type="email" class="form-control" name="email" value="<?php echo e(old('email')); ?>" required autofocus placeholder="<?php echo e(__('Email Address')); ?>">
                                        <?php if($errors->has('email')): ?>
                                        <span class="help-block">
                                            <strong><?php echo e($errors->first('email')); ?></strong>
                                        </span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="formrow<?php echo e($errors->has('password') ? ' has-error' : ''); ?>">
                                        <input id="password" type="password" class="form-control" name="password" value="" required placeholder="<?php echo e(__('Password')); ?>">
                                        <?php if($errors->has('password')): ?>
                                        <span class="help-block">
                                            <strong><?php echo e($errors->first('password')); ?></strong>
                                        </span>
                                        <?php endif; ?>
                                    </div>            
                                    <input type="submit" class="btn" value="<?php echo e(__('Login')); ?>">
                                </div>
                                <!-- login form  end--> 
                            </form>
                            <!-- sign up form -->
                    <div class="newuser"><i class="fa fa-user" aria-hidden="true"></i> <?php echo e(__('New User')); ?>? <a href="<?php echo e(route('register')); ?>"><?php echo e(__('Register Here')); ?></a></div>
                    <div class="newuser"><i class="fa fa-user" aria-hidden="true"></i> <?php echo e(__('Forgot Your Password')); ?>? <a href="<?php echo e(route('password.request')); ?>"><?php echo e(__('Click here')); ?></a></div>
                    <!-- sign up form end-->
                        </div>
                        <div id="employer" class="formpanel tab-pane fade <?php echo e(($c_or_e == 'employer')? 'active in':''); ?>">
                            <div class="socialLogin">
                                        <h5><?php echo e(__('Login with Social')); ?></h5>
                                        <a href="<?php echo e(url('login/employer/facebook')); ?>" class="fb"><i class="fa fa-facebook" aria-hidden="true"></i></a> <a href="<?php echo e(url('login/employer/twitter')); ?>" class="tw"><i class="fa fa-twitter" aria-hidden="true"></i></a> </div>
                            <form class="form-horizontal" method="POST" action="<?php echo e(route('company.login')); ?>">
                                <?php echo e(csrf_field()); ?>

                                <input type="hidden" name="candidate_or_employer" value="employer" />
                                <div class="formpanel">
                                    <div class="formrow<?php echo e($errors->has('email') ? ' has-error' : ''); ?>">
                                        <input id="email" type="email" class="form-control" name="email" value="<?php echo e(old('email')); ?>" required autofocus placeholder="<?php echo e(__('Email Address')); ?>">
                                        <?php if($errors->has('email')): ?>
                                        <span class="help-block">
                                            <strong><?php echo e($errors->first('email')); ?></strong>
                                        </span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="formrow<?php echo e($errors->has('password') ? ' has-error' : ''); ?>">
                                        <input id="password" type="password" class="form-control" name="password" value="" required placeholder="<?php echo e(__('Password')); ?>">
                                        <?php if($errors->has('password')): ?>
                                        <span class="help-block">
                                            <strong><?php echo e($errors->first('password')); ?></strong>
                                        </span>
                                        <?php endif; ?>
                                    </div>            
                                    <input type="submit" class="btn" value="<?php echo e(__('Login')); ?>">
                                </div>
                                <!-- login form  end--> 
                            </form>
                            <!-- sign up form -->
                    <div class="newuser"><i class="fa fa-user" aria-hidden="true"></i> <?php echo e(__('New User')); ?>? <a href="<?php echo e(route('register')); ?>"><?php echo e(__('Register Here')); ?></a></div>
                    <div class="newuser"><i class="fa fa-user" aria-hidden="true"></i> <?php echo e(__('Forgot Your Password')); ?>? <a href="<?php echo e(route('company.password.request')); ?>"><?php echo e(__('Click here')); ?></a></div>
                    <!-- sign up form end-->
                        </div>
                    </div>
                    <!-- login form -->

                     

                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $__env->make('includes.footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->startSection('content'); ?>
<!-- Header start -->
<?php echo $__env->make('includes.header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<!-- Header end --> 
<!-- Inner Page Title start -->
<?php echo $__env->make('includes.inner_page_title', ['page_title'=>__('Contact Us')], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<!-- Inner Page Title end -->
<div class="inner-page"> 
    <!-- About -->
    <div class="container">
        <div class="contact-wrap">
            <div class="title"> <span><?php echo e(__('We Are Here For Your Help')); ?></span>
                <h2><?php echo e(__('GET IN TOUCH FAST')); ?></h2>
                <p>
                    <?php echo e(__('Vestibulum at magna tellus. Vivamus sagittis nunc aliquet. Vivamin orci aliquam')); ?>

                    <br>
                    <?php echo e(__('eros vel saphicula. Donec eget ultricies ipsmconsequat')); ?>

                </p>
            </div>
            <div class="row"> 
                <!-- Contact Info -->
                <div class="contact-now">
                    <div class="col-md-4 column">
                        <div class="contact"> <span><i class="fa fa-home"></i></span>
                            <div class="information"> <strong><?php echo e(__('Address')); ?>:</strong>
                                <p><?php echo e($siteSetting->site_street_address); ?></p>
                            </div>
                        </div>
                    </div>
                    <!-- Contact Info -->
                    <div class="col-md-4 column">
                        <div class="contact"> <span><i class="fa fa-envelope"></i></span>
                            <div class="information"> <strong><?php echo e(__('Email Address')); ?>:</strong>
                                <p><a href="mailto:<?php echo e($siteSetting->mail_to_address); ?>"><?php echo e($siteSetting->mail_to_address); ?></a></p>
                            </div>
                        </div>
                    </div>
                    <!-- Contact Info -->
                    <div class="col-md-4 column">
                        <div class="contact"> <span><i class="fa fa-phone"></i></span>
                            <div class="information"> <strong><?php echo e(__('Phone')); ?>:</strong>
                                <p><a href="tel:<?php echo e($siteSetting->site_phone_primary); ?>"><?php echo e($siteSetting->site_phone_primary); ?></a></p>
                                <p><a href="tel:<?php echo e($siteSetting->site_phone_secondary); ?>"><?php echo e($siteSetting->site_phone_secondary); ?></a></p>
                            </div>
                        </div>
                    </div>
                    <!-- Contact Info --> 
                </div>
                <div class="col-md-4 column"> 
                    <!-- Google Map -->
                    <div class="googlemap">
                        <?php echo $siteSetting->site_google_map; ?>

                    </div>
                </div>
                <!-- Contact form -->
                <div class="col-md-8 column">
                    <div class="contact-form">
                        <div id="message"></div>
                        <form method="post" action="<?php echo e(route('contact.us')); ?>" name="contactform" id="contactform">
                            <?php echo e(csrf_field()); ?>

                            <div class="row">
                                <div class="col-md-6<?php echo e($errors->has('full_name') ? ' has-error' : ''); ?>">                  
                                    <?php echo Form::text('full_name', null, array('id'=>'full_name', 'placeholder'=>__('Full Name'), 'required'=>'required', 'autofocus'=>'autofocus')); ?>                
                                    <?php if($errors->has('full_name')): ?> <span class="help-block"> <strong><?php echo e($errors->first('full_name')); ?></strong> </span> <?php endif; ?>
                                </div>
                                <div class="col-md-6<?php echo e($errors->has('email') ? ' has-error' : ''); ?>">                  
                                    <?php echo Form::text('email', null, array('id'=>'email', 'placeholder'=>__('Email'), 'required'=>'required')); ?>                
                                    <?php if($errors->has('email')): ?> <span class="help-block"> <strong><?php echo e($errors->first('email')); ?></strong> </span> <?php endif; ?>
                                </div>
                                <div class="col-md-6<?php echo e($errors->has('phone') ? ' has-error' : ''); ?>">                  
                                    <?php echo Form::text('phone', null, array('id'=>'phone', 'placeholder'=>__('Phone'))); ?>                
                                    <?php if($errors->has('phone')): ?> <span class="help-block"> <strong><?php echo e($errors->first('phone')); ?></strong> </span> <?php endif; ?>
                                </div>
                                <div class="col-md-6<?php echo e($errors->has('subject') ? ' has-error' : ''); ?>">                  
                                    <?php echo Form::text('subject', null, array('id'=>'subject', 'placeholder'=>__('Subject'), 'required'=>'required')); ?>                
                                    <?php if($errors->has('subject')): ?> <span class="help-block"> <strong><?php echo e($errors->first('subject')); ?></strong> </span> <?php endif; ?>
                                </div>
                                <div class="col-md-12<?php echo e($errors->has('message_txt') ? ' has-error' : ''); ?>">                  
                                    <?php echo Form::textarea('message_txt', null, array('id'=>'message_txt', 'placeholder'=>__('Message'), 'required'=>'required')); ?>                
                                    <?php if($errors->has('message_txt')): ?> <span class="help-block"> <strong><?php echo e($errors->first('message_txt')); ?></strong> </span> <?php endif; ?>
                                </div>
                                <div class="col-md-12<?php echo e($errors->has('g-recaptcha-response') ? ' has-error' : ''); ?>">
                                    <?php echo app('captcha')->display(); ?>

                                    <?php if($errors->has('g-recaptcha-response')): ?> <span class="help-block"> <strong><?php echo e($errors->first('g-recaptcha-response')); ?></strong> </span> <?php endif; ?>
                                </div>
                                <div class="col-md-12">
                                    <button title="" class="button" type="submit" id="submit"><?php echo e(__('Submit Now')); ?></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $__env->make('includes.footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
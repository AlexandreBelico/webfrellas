<!--Footer-->
<div class="largebanner shadow3_re">
    <div class="adin">
    <?php echo $siteSetting->above_footer_ad; ?>

    </div>
    <div class="clearfix"></div>
</div>


<div class="footerWrap"> 
    <div class="container">
        <div class="row"> 

            <!--Quick Links-->
            <div class="col-md-3 col-sm-6">
                <h5><?php echo e(__('Quick Links')); ?></h5>
                <!--Quick Links menu Start-->
                <ul class="quicklinks">
                    <li><a href="<?php echo e(route('index')); ?>"><?php echo e(__('Home')); ?></a></li>
                    <li><a href="<?php echo e(route('contact.us')); ?>"><?php echo e(__('Contact Us')); ?></a></li>
                    <li class="postad"><a href="<?php echo e(route('post.job')); ?>"><?php echo e(__('Post a Job')); ?></a></li>
                    <li><a href="<?php echo e(route('faq')); ?>"><?php echo e(__('FAQs')); ?></a></li>
                    <?php $__currentLoopData = $show_in_footer_menu; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $footer_menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                    $cmsContent = App\CmsContent::getContentBySlug($footer_menu->page_slug);
                    ?>

                    <li class="<?php echo e(Request::url() == route('cms', $footer_menu->page_slug) ? 'active' : ''); ?>"><a href="<?php echo e(route('cms', $footer_menu->page_slug)); ?>"><?php echo e(isset($cmsContent->page_title) ? $cmsContent->page_title : ''); ?></a></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
            <!--Quick Links menu end-->

            <div class="col-md-3 col-sm-6">
                <h5><?php echo e(__('Jobs By Functional Area')); ?></h5>
                <!--Quick Links menu Start-->
                <ul class="quicklinks">
                    <?php
                    $functionalAreas = App\FunctionalArea::getUsingFunctionalAreas(10);
                    ?>
                    <?php $__currentLoopData = $functionalAreas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $functionalArea): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><a href="<?php echo e(route('job.list', ['functional_area_id[]'=>$functionalArea->functional_area_id])); ?>"><?php echo e($functionalArea->functional_area); ?></a></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>

            <!--Jobs By Industry-->
            <div class="col-md-3 col-sm-6">
                <h5><?php echo e(__('Jobs By Industry')); ?></h5>
                <!--Industry menu Start-->
                <ul class="quicklinks">
                    <?php
                    $industries = App\Industry::getUsingIndustries(10);
                    ?>
                    <?php $__currentLoopData = $industries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $industry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><a href="<?php echo e(route('job.list', ['industry_id[]'=>$industry->industry_id])); ?>"><?php echo e($industry->industry); ?></a></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
                <!--Industry menu End-->
                <div class="clear"></div>
            </div>

            <!--About Us-->
            <div class="col-md-3 col-sm-12">
                <h5><?php echo e(__('Contact Us')); ?></h5>
                <div class="address"><?php echo e($siteSetting->site_street_address); ?></div>
                <div class="email"> <a href="mailto:<?php echo e($siteSetting->mail_to_address); ?>"><?php echo e($siteSetting->mail_to_address); ?></a> </div>
                <div class="phone"> <a href="tel:<?php echo e($siteSetting->site_phone_primary); ?>"><?php echo e($siteSetting->site_phone_primary); ?></a></div>
                <!-- Social Icons -->
                <div class="social"><?php echo $__env->make('includes.footer_social', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?></div>
                <!-- Social Icons end --> 

            </div>
            <!--About us End--> 


        </div>
    </div>
</div>
<!--Footer end--> 
<!--Copyright-->
<div class="copyright">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="bttxt"><?php echo e(__('Copyright')); ?> &copy; <?php echo e(date('Y')); ?> <?php echo e($siteSetting->site_name); ?>. <?php echo e(__('All Rights Reserved')); ?>. <?php echo e(__('Design by')); ?>: <a href="<?php echo e(url('/')); ?>http://graphicriver.net/user/ecreativesol" target="_blank">eCreativeSolutions</a></div>
            </div>
            <div class="col-md-4">
                <div class="paylogos"><img src="<?php echo e(asset('/public/')); ?>images/payment-icons.png" alt="" /></div>	
            </div>
        </div>

    </div>
</div>

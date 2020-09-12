<?php $__env->startSection('content'); ?> 
<!-- Header start --> 
<?php echo $__env->make('includes.header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?> 
<!-- Header end --> 
<!-- Inner Page Title start --> 
<?php echo $__env->make('includes.inner_page_title', ['page_title'=>__('Job Listing')], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?> 
<!-- Inner Page Title end -->
<div class="listpgWraper">
    <div class="container">
        <?php echo $__env->make('flash::message', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <form action="<?php echo e(route('job.list')); ?>" method="get">
            <!-- Page Title start -->
            <div class="pageSearch">
                <div class="row">
                    <div class="col-md-3">
                        <?php if(Auth::guard('company')->check()): ?>
                        <a href="<?php echo e(route('post.job')); ?>" class="btn"><i class="fa fa-file-text" aria-hidden="true"></i> <?php echo e(__('Post Job')); ?></a>
                        <?php else: ?>
                        <!--a href="<?php echo e(url('my-profile#cvs')); ?>" class="btn"><i class="fa fa-file-text" aria-hidden="true"></i> <?php echo e(__('Upload Your Resume')); ?></a-->
                        <?php endif; ?>

                    </div>
                    <div class="col-md-9">
                        <div class="searchform">
                            <div class="row">
                                <div class="col-md-<?php echo e(((bool)$siteSetting->country_specific_site)? 5:3); ?>">
                                    <input type="text" name="search" value="<?php echo e(Request::get('search', '')); ?>" class="form-control" placeholder="<?php echo e(__('Enter Skills or job title')); ?>" />
                                </div>
                                <div class="col-md-2"> <?php echo Form::select('functional_area_id[]', ['' => __('Select Functional Area')]+$functionalAreas, Request::get('functional_area_id', null), array('class'=>'form-control', 'id'=>'functional_area_id')); ?> </div>


                                <?php if((bool)$siteSetting->country_specific_site): ?>
                                <?php echo Form::hidden('country_id[]', Request::get('country_id[]', $siteSetting->default_country_id), array('id'=>'country_id')); ?>

                                <?php else: ?>
                                <div class="col-md-2">
                                    <?php echo Form::select('country_id[]', ['' => __('Select Country')]+$countries, Request::get('country_id', $siteSetting->default_country_id), array('class'=>'form-control', 'id'=>'country_id')); ?>

                                </div>
                                <?php endif; ?>

                                <div class="col-md-2">
                                    <span id="state_dd">
                                        <?php echo Form::select('state_id[]', ['' => __('Select State')], Request::get('state_id', null), array('class'=>'form-control', 'id'=>'state_id')); ?>

                                    </span>
                                </div>
                                <div class="col-md-2">
                                    <span id="city_dd">
                                        <?php echo Form::select('city_id[]', ['' => __('Select City')], Request::get('city_id', null), array('class'=>'form-control', 'id'=>'city_id')); ?>

                                    </span>
                                </div>
                                <div class="col-md-1">
                                    <button type="submit" class="btn"><i class="fa fa-search" aria-hidden="true"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Page Title end -->
        </form>
        <form action="<?php echo e(route('job.list')); ?>" method="get">
            <!-- Search Result and sidebar start -->
            <div class="row"> <?php echo $__env->make('includes.job_list_side_bar', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                <div style="display:none;" class="col-md-3 col-sm-6 pull-right">
                    <!-- Sponsord By -->
                    <div class="sidebar">
                        <h4 class="widget-title"><?php echo e(__('Sponsord By')); ?></h4>
                        <div class="gad"><?php echo $siteSetting->listing_page_vertical_ad; ?></div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12"> 
                    <!-- Search List -->
                    <ul class="searchList">
                        <!-- job start --> 
                        <?php if(isset($jobs) && count($jobs)): ?>
                        <?php $__currentLoopData = $jobs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $job): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $company = $job->getCompany(); ?>
                        <?php if(null !== $company): ?>
                        <li>
                            <div class="row">
                                <div class="col-md-8 col-sm-8">
                                    <div class="jobimg"><?php echo e($company->printCompanyImage()); ?></div>
                                    <div class="jobinfo">
                                        <h3><a href="<?php echo e(route('job.detail', [$job->slug])); ?>" title="<?php echo e($job->title); ?>"><?php echo e($job->title); ?></a></h3>
                                        <div class="companyName"><a href="<?php echo e(route('company.detail', $company->slug)); ?>" title="<?php echo e($company->name); ?>"><?php echo e($company->name); ?></a></div>
                                        <div class="location">
                                            <label class="fulltime" title="<?php echo e($job->getJobType('job_type')); ?>"><?php echo e($job->getJobType('job_type')); ?></label>
                                            - <span><?php echo e($job->getCity('city')); ?></span></div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="col-md-4 col-sm-4">
                                    <div class="listbtn"><a href="<?php echo e(route('job.detail', [$job->slug])); ?>"><?php echo e(__('View Details')); ?></a></div>
                                </div>
                            </div>
                            <p><?php echo e(str_limit(strip_tags($job->description), 150, '...')); ?></p>
                        </li>
                        <!-- job end --> 
                        <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </ul>

                    <!-- Pagination Start -->
                    <div class="pagiWrap">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="showreslt">
                                    <?php echo e(__('Showing Pages')); ?> : <?php echo e($jobs->firstItem()); ?> - <?php echo e($jobs->lastItem()); ?> <?php echo e(__('Total')); ?> <?php echo e($jobs->total()); ?>

                                </div>
                            </div>
                            <div class="col-md-7 text-right">
                                <?php if(isset($jobs) && count($jobs)): ?>
                                <?php echo e($jobs->appends(request()->query())->links()); ?>

                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <!-- Pagination end --> 
                    <div class=""><br /><?php echo $siteSetting->listing_page_horizontal_ad; ?></div>

                </div>
            </div>
        </form>
    </div>
</div>
<?php echo $__env->make('includes.footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('styles'); ?>
<style type="text/css">
    .searchList li .jobimg {
        min-height: 80px;
    }
    .hide_vm_ul{
        height:100px;
        overflow:hidden;
    }
    .hide_vm{
        display:none !important;
    }
    .view_more{
        cursor:pointer;
    }
</style>
<?php $__env->stopPush(); ?>
<?php $__env->startPush('scripts'); ?> 
<script>
    $(document).ready(function ($) {
        $("form").submit(function () {
            $(this).find(":input").filter(function () {
                return !this.value;
            }).attr("disabled", "disabled");
            return true;
        });
        $("form").find(":input").prop("disabled", false);

        $(".view_more_ul").each(function () {
            if ($(this).height() > 100)
            {
                $(this).addClass('hide_vm_ul');
                $(this).next().removeClass('hide_vm');
            }
        });
        $('.view_more').on('click', function (e) {
            e.preventDefault();
            $(this).prev().removeClass('hide_vm_ul');
            $(this).addClass('hide_vm');
        });

    });
</script>
<?php echo $__env->make('includes.country_state_city_js', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
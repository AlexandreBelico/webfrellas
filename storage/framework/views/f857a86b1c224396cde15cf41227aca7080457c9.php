<?php $__env->startSection('content'); ?> 
<!-- Header start --> 
<?php echo $__env->make('includes.header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?> 
<!-- Header end --> 
<!-- Inner Page Title start --> 
<?php echo $__env->make('includes.inner_page_title', ['page_title'=>__('Job Detail')], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?> 
<!-- Inner Page Title end -->
<?php
$company = $job->getCompany();
?>

<!-- =========== Submit Milestone Popup ==========  -->
<div class="modal fade" id="Submitmilestonemodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <?php echo Form::open(array('method' => 'post', 'route' => ['post.submitmilestone'])); ?> 
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Submit milestone</h5>
      </div>
      <div class="modal-body">
        <div class="form-group">
            <textarea class="form-control" name="messagewhilesugmitmilestone" placeholder="Enter final message for employer here..."></textarea>
            <input type="hidden" name="submitmilestoneId" class="submitmilestoneId">
            <input type="hidden" name="job_slug" value="<?php echo e($job->slug); ?>">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <input type="submit" class="btn btn-info" value="Submit">
      </div>
    </div>
    <?php echo Form::close(); ?> 
  </div>
</div>
<!-- =========== Submit Milestone Popup ==========  -->

<div class="listpgWraper">
    <div class="container"> 
        <?php echo $__env->make('flash::message', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
       

        <!-- Job Detail start -->
        <div class="row">
            <div class="col-md-8"> 
				
				 <!-- Job Header start -->
        <div class="job-header">
            <div class="jobinfo">
                <h2><?php echo e($job->title); ?> - <?php echo e($company->name); ?></h2>
                <div class="ptext"><?php echo e(__('Date Posted')); ?>: <?php echo e(date('d-m-Y', strtotime($job->created_at))); ?></div>
                <?php if(!(bool)$job->hide_salary): ?>
                <div class="salary"><?php echo e(__('Project Cost')); ?>: <strong><?php echo e($job->salary_from.' '.$job->salary_currency); ?></strong></div>
                <?php endif; ?>
                <?php if(Auth::check() && Auth::user() && !empty($jobs_apply)): ?>
                    <?php
                    $percentVal = '5';
                    if(isset($percentage->id)){
                        $percentVal = $percentage->percent;
                    }
                    
                    $newprice = $jobs_apply->expected_salary - ($jobs_apply->expected_salary * (6/100));
                    $newprice = $newprice - ($newprice * ($percentVal/100))
                    ?>
                    <div class="salary"><?php echo e(__('Project Cost You Bid')); ?>: <strong><?php echo e($jobs_apply->expected_salary.' '.$job->salary_currency); ?></strong></div>
                    <div class="salary"><?php echo e(__('You will receive')); ?>: <strong><?php echo e(number_format((float)$newprice, 2, '.', '').' '.$job->salary_currency); ?></strong></div>
                <?php endif; ?>
            </div>
            <?php if(Auth::user()): ?>
            <div class="jobButtons">
                
                <?php if(Auth::check() && Auth::user()->isAppliedOnJob($job->id)): ?>
                    <a href="javascript:;" class="btn apply"><i class="fa fa-paper-plane" aria-hidden="true"></i> <?php echo e(__('Already Applied')); ?></a>
                    <a href="" class="btn apply"><i class="fa fa-paper-plane" aria-hidden="true"></i> <?php echo e(__('Submit Your Work')); ?></a>
                <?php else: ?>
                <a href="<?php echo e(route('apply.job', $job->slug)); ?>" class="btn apply"><i class="fa fa-paper-plane" aria-hidden="true"></i> <?php echo e(__('Apply Now')); ?></a>
                <?php endif; ?>
                
                <?php if(Auth::check() && Auth::user()->isFavouriteJob($job->slug)): ?> <a href="<?php echo e(route('remove.from.favourite', $job->slug)); ?>" class="btn"><i class="fa fa-floppy-o" aria-hidden="true"></i> <?php echo e(__('Favourite Job')); ?> </a> <?php else: ?> <a href="<?php echo e(route('add.to.favourite', $job->slug)); ?>" class="btn"><i class="fa fa-floppy-o" aria-hidden="true"></i> <?php echo e(__('Add to Favourite')); ?></a> <?php endif; ?>
                <a href="<?php echo e(route('report.abuse', $job->slug)); ?>" class="btn report"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <?php echo e(__('Report Abuse')); ?></a>
            </div>
            <?php endif; ?>
        </div>
				
				
				
    <!-- Job Description start -->
    <div class="job-header">
        <div class="contentbox">
            <h3><?php echo e(__('Job Description')); ?></h3>
            <p><?php echo $job->description; ?></p>

            <hr>
            <h3><?php echo e(__('Skills Required')); ?></h3>
            <ul class="skillslist">
                <?php echo $job->getJobSkillsList(); ?>

            </ul>

            <hr>
            <?php if(count($milestones)>0): ?>
            <h3><?php echo e(__('Milestones')); ?></h3>
            <ul class="skillslist">
        <div class="userccount">
            <?php
                $i = 1;
            ?>
            <?php $__currentLoopData = $milestones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $milestone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $status = '';
                $startdate = date("d M, Y", strtotime($milestone->start_date));
                $enddate = date("d M, Y", strtotime($milestone->end_date));
            ?>

        <div class="row">
            <div class="col-md-4">
                <h5>Milestone : <?php echo e($i++); ?></h5>
            </div>
            <div class="col-md-3">
                 <?php if($milestone->status==0): ?>
                        <span class="milestoneobjects">Status: Open</span>
                    <?php elseif($milestone->status==1): ?>
                        <span class="milestoneobjects">Status: In progress</span>
                    <?php elseif($milestone->status==2): ?>
                        <span class="milestoneobjects">Status: Submitted</span>
                    <?php elseif($milestone->status==3): ?>
                        <span class="milestoneobjects">Status: Completed</span>
                    <?php elseif($milestone->status==4): ?>
                        <span class="milestoneobjects">Status: Paused</span>
                    <?php endif; ?>
            </div>
            <div class="col-md-5 text-right">
                <?php if($milestone->status==0): ?>
                    <a href="<?php echo e(route('milestone.changestatus', [$milestone->id, 1])); ?>" class="btn btn-info btn-sm">Start work</a>
                <?php elseif($milestone->status==1): ?>
                    <a href="<?php echo e(route('milestone.changestatus', [$milestone->id, 4])); ?>" class="btn btn-warning btn-sm">Stop work</a>
                <?php elseif($milestone->status==2): ?>
                    <a href="javascript:void(0)" class="btn btn-primary btn-sm">Submitted</a>
                <?php elseif($milestone->status==3): ?>
                    <span class="btn btn-success btn-sm">Completed</span>
                <?php elseif($milestone->status==4): ?>
                    <a href="<?php echo e(route('milestone.changestatus', [$milestone->id, 1])); ?>" class="btn btn-warning btn-sm">Start work</a>
                <?php endif; ?>
                <?php if($milestone->status>0 && $milestone->status!=3 && $milestone->status!=2): ?>
                    <a href="javascript: void(0)" onclick="Submitmilestone(<?php echo e($milestone->id); ?>)" class="btn btn-danger btn-sm">Mark completed</a>
                <?php endif; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <ul >
                    <li class="milestoneobjects">Start date : <?php echo e($startdate); ?> </li>
                    <li class="milestoneobjects">End date : <?php echo e($enddate); ?> </li>
                    <li class="milestoneobjects">Price : <?php echo e($milestone->price); ?> </li>
                </ul>
            </div>

            <div class="col-md-8 no-gutters">
                <p class="milestonedescription"> <?php echo e(str_limit($milestone->description, 400, '')); ?> 
                    <?php if(strlen($milestone->description) > 400): ?>
                        <span id="dots_<?php echo e($milestone->id); ?>">...</span>
                        <span id="moredescription_<?php echo e($milestone->id); ?>" class="moredescription"><?php echo e(substr($milestone->description, 400)); ?></span>
                        <button class="btn-link btn-anchor" href="javascript:void(0)" onclick="readmore(<?php echo e($milestone->id); ?>)" id="readmorebtn_<?php echo e($milestone->id); ?>">Read more</button>
                    <?php endif; ?>
                </p>
            </div>
        </div>
        <hr class="rowseparator">
         <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
            </ul>
            <?php endif; ?>
        </div>
    </div>
    <!-- Job Description end --> 

                <!-- related jobs start -->
                <div class="relatedJobs">
                    <?php if(isset($relatedJobs) && count($relatedJobs)): ?>
                    <h3><?php echo e(__('Related Jobs')); ?></h3>
                    <ul class="searchList">
                        
                        <?php $__currentLoopData = $relatedJobs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $relatedJob): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $relatedJobCompany = $relatedJob->getCompany(); ?>
                        <?php if(null !== $relatedJobCompany): ?>
                        <!--Job start-->
                        <li>
                            <div class="row">
                                <div class="col-md-8 col-sm-8">
                                    <div class="jobimg"><a href="<?php echo e(route('job.detail', [$relatedJob->slug])); ?>" title="<?php echo e($relatedJob->title); ?>">
                                            <?php echo e($relatedJobCompany->printCompanyImage()); ?>

                                        </a></div>
                                    <div class="jobinfo">
                                        <h3><a href="<?php echo e(route('job.detail', [$relatedJob->slug])); ?>" title="<?php echo e($relatedJob->title); ?>"><?php echo e($relatedJob->title); ?></a></h3>
                                        <div class="companyName"><a href="<?php echo e(route('company.detail', $relatedJobCompany->slug)); ?>" title="<?php echo e($relatedJobCompany->name); ?>"><?php echo e($relatedJobCompany->name); ?></a></div>
                                        <div class="location">
                                            <label class="fulltime"><?php echo e($relatedJob->getJobType('job_type')); ?></label>
                                            <label class="partTime"><?php echo e($relatedJob->getJobShift('job_shift')); ?></label>   - <span><?php echo e($relatedJob->getCity('city')); ?></span></div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="col-md-4 col-sm-4">
                                    <div class="listbtn"><a href="<?php echo e(route('job.detail', [$relatedJob->slug])); ?>"><?php echo e(__('View Detail')); ?></a></div>
                                </div>
                            </div>
                            <p><?php echo e(str_limit(strip_tags($relatedJob->description), 150, '...')); ?></p>
                        </li>
                        <!--Job end--> 
                        <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <!-- Job end -->
                    </ul>
                <?php else: ?>
                <ul class="searchList">
                    </ul>
                 <?php endif; ?>
                 </div>
            </div>
            <!-- related jobs end -->

            <div class="col-md-4"> 
				
				<div class="companyinfo">
                            <div class="companylogo"><a href="<?php echo e(route('company.detail',$company->slug)); ?>"><?php echo e($company->printCompanyImage()); ?></a></div>
                            <div class="title"><a href="<?php echo e(route('company.detail',$company->slug)); ?>"><?php echo e($company->name); ?></a></div>
                            <div class="ptext"><?php echo e($company->getLocation()); ?></div>
                            <div class="opening">
                                <a href="<?php echo e(route('company.detail',$company->slug)); ?>">
                                    <?php echo e(App\Company::countNumJobs('company_id', $company->id)); ?> <?php echo e(__('Current Jobs Openings')); ?>

                                </a>
                            </div>
                            <div class="clearfix"></div>
                        </div>
				
				
                <!-- Job Detail start -->
                <div class="job-header">
                    <div class="jobdetail">
                        <h3><?php echo e(__('Job Detail')); ?></h3>
                        <ul class="jbdetail">
                            <li class="row">
                                <div class="col-md-4 col-xs-5"><?php echo e(__('Location')); ?></div>
                                <div class="col-md-8 col-xs-7">
                                    
                                    <span><?php echo e(str_replace( ', ', '', $job->getLocation() )); ?></span>
                                    
                                </div>
                            </li>
                            <li class="row">
                                <div class="col-md-4 col-xs-5"><?php echo e(__('Company')); ?></div>
                                <div class="col-md-8 col-xs-7"><a href="<?php echo e(route('company.detail', $company->id)); ?>"><?php echo e($company->name); ?></a></div>
                            </li>
                            <li style="display:none;" class="row">
                                <div class="col-md-4 col-xs-5"><?php echo e(__('Type')); ?></div>
                                <div class="col-md-8 col-xs-7"><span class="permanent"><?php echo e($job->getJobType('job_type')); ?></span></div>
                            </li>
                            <li style="display:none;" class="row">
                                <div class="col-md-4 col-xs-5"><?php echo e(__('Shift')); ?></div>
                                <div class="col-md-8 col-xs-7"><span class="freelance"><?php echo e($job->getJobShift('job_shift')); ?></span></div>
                            </li>
                            <li class="row">
                                <div class="col-md-4 col-xs-5"><?php echo e(__('Career Level')); ?></div>
                                <div class="col-md-8 col-xs-7"><span><?php echo e($job->getCareerLevel('career_level')); ?></span></div>
                            </li>
                            <li class="row">
                                <div class="col-md-4 col-xs-5"><?php echo e(__('Positions')); ?></div>
                                <div class="col-md-8 col-xs-7"><span><?php echo e($job->num_of_positions); ?></span></div>
                            </li>
                            <li style="display:none;" class="row">
                                <div class="col-md-4 col-xs-5"><?php echo e(__('Experience')); ?></div>
                                <div class="col-md-8 col-xs-7"><span><?php echo e($job->getJobExperience('job_experience')); ?></span></div>
                            </li>
                            <li style="display:none;" class="row">
                                <div class="col-md-4 col-xs-5"><?php echo e(__('Gender')); ?></div>
                                <div class="col-md-8 col-xs-7"><span><?php echo e($job->getGender('gender')); ?></span></div>
                            </li>
                            <li style="display:none;" class="row">
                                <div class="col-md-4 col-xs-5"><?php echo e(__('Degree')); ?></div>
                                <div class="col-md-8 col-xs-7"><span><?php echo e($job->getDegreeLevel('degree_level')); ?></span></div>
                            </li>
                            <li style="display:none;" class="row">
                                <div class="col-md-4 col-xs-5"><?php echo e(__('Apply Before')); ?></div>
                                <div class="col-md-8 col-xs-7"><span><?php echo e(date('d-m-Y', strtotime($job->expiry_date))); ?></span></div>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- Google Map start -->
                <div class="job-header">
                    <div class="jobdetail">
                        <h3><?php echo e(__('Google Map')); ?></h3>
                        <div class="gmap">
                            <?php echo $company->map; ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $__env->make('includes.footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('styles'); ?>
<style type="text/css">
    .view_more{display:none !important;}
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
                $(this).css('height', 100);
                $(this).css('overflow', 'hidden');
                //alert($( this ).next());
                $(this).next().removeClass('view_more');
            }
        });



    });
</script> 
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
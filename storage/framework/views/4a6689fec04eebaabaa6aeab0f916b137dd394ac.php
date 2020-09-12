<div class="col-md-3 col-sm-6"> 
    <!-- Side Bar start -->
    <div class="sidebar">
        <input type="hidden" name="search" value="<?php echo e(Request::get('search', '')); ?>"/>
        <!-- Jobs By Title -->
        <div class="widget">
            <h4 class="widget-title"><?php echo e(__('Jobs By Title')); ?></h4>
            <ul class="optionlist view_more_ul">
                <?php if(isset($jobTitlesArray) && count($jobTitlesArray)): ?>
                <?php $__currentLoopData = $jobTitlesArray; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$jobTitle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li>
                    <?php
                    $checked = (in_array($jobTitle, Request::get('job_title', array())))? 'checked="checked"':'';
                    ?>
                    <input type="checkbox" name="job_title[]" id="job_title_<?php echo e($key); ?>" value="<?php echo e($jobTitle); ?>" <?php echo e($checked); ?> >
                    <label for="job_title_<?php echo e($key); ?>"></label>
                    <?php echo e($jobTitle); ?> <span><?php echo e(App\Job::countNumJobs('title', $jobTitle)); ?></span> </li>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </ul>
            <!-- title end --> 
            <span class="text text-primary view_more hide_vm"><?php echo e(__('View More')); ?></span> </div>

        <!-- Jobs By Country -->
        <div class="widget">
            <h4 class="widget-title"><?php echo e(__('Jobs By Country')); ?></h4>
            <ul class="optionlist view_more_ul">
                <?php if(isset($countryIdsArray) && count($countryIdsArray)): ?>
                <?php $__currentLoopData = $countryIdsArray; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$country_id): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                $country = App\Country::where('country_id','=',$country_id)->lang()->active()->first();			  
                ?>
                <?php if(null !== $country): ?>
                <?php
                $checked = (in_array($country->country_id, Request::get('country_id', array())))? 'checked="checked"':'';
                ?>
                <li>
                    <input type="checkbox" name="country_id[]" id="country_<?php echo e($country->country_id); ?>" value="<?php echo e($country->country_id); ?>" <?php echo e($checked); ?>>
                    <label for="country_<?php echo e($country->country_id); ?>"></label>
                    <?php echo e($country->country); ?> <span><?php echo e(App\Job::countNumJobs('country_id', $country->country_id)); ?></span> </li>
                <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </ul>
            <span class="text text-primary view_more hide_vm"><?php echo e(__('View More')); ?></span> </div>
        <!-- Jobs By Country end--> 


        <!-- Jobs By State -->
        <div class="widget">
            <h4 class="widget-title"><?php echo e(__('Jobs By State')); ?></h4>
            <ul class="optionlist view_more_ul">
                <?php if(isset($stateIdsArray) && count($stateIdsArray)): ?>
                <?php $__currentLoopData = $stateIdsArray; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$state_id): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                $state = App\State::where('state_id','=',$state_id)->lang()->active()->first();			  
                ?>
                <?php if(null !== $state): ?>
                <?php
                $checked = (in_array($state->state_id, Request::get('state_id', array())))? 'checked="checked"':'';
                ?>
                <li>
                    <input type="checkbox" name="state_id[]" id="state_<?php echo e($state->state_id); ?>" value="<?php echo e($state->state_id); ?>" <?php echo e($checked); ?>>
                    <label for="state_<?php echo e($state->state_id); ?>"></label>
                    <?php echo e($state->state); ?> <span><?php echo e(App\Job::countNumJobs('state_id', $state->state_id)); ?></span> </li>
                <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </ul>
            <span class="text text-primary view_more hide_vm"><?php echo e(__('View More')); ?></span> </div>
        <!-- Jobs By State end--> 


        <!-- Jobs By City -->
        <div class="widget">
            <h4 class="widget-title"><?php echo e(__('Jobs By City')); ?></h4>
            <ul class="optionlist view_more_ul">
                <?php if(isset($cityIdsArray) && count($cityIdsArray)): ?>
                <?php $__currentLoopData = $cityIdsArray; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$city_id): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                $city = App\City::where('city_id','=',$city_id)->lang()->active()->first();			  
                ?>
                <?php if(null !== $city): ?>
                <?php
                $checked = (in_array($city->city_id, Request::get('city_id', array())))? 'checked="checked"':'';
                ?>
                <li>
                    <input type="checkbox" name="city_id[]" id="city_<?php echo e($city->city_id); ?>" value="<?php echo e($city->city_id); ?>" <?php echo e($checked); ?>>
                    <label for="city_<?php echo e($city->city_id); ?>"></label>
                    <?php echo e($city->city); ?> <span><?php echo e(App\Job::countNumJobs('city_id', $city->city_id)); ?></span> </li>
                <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </ul>
            <span class="text text-primary view_more hide_vm"><?php echo e(__('View More')); ?></span> </div>
        <!-- Jobs By City end--> 

        <!-- Jobs By Experience -->
        <div class="widget">
            <h4 class="widget-title"><?php echo e(__('Jobs By Experience')); ?></h4>
            <ul class="optionlist view_more_ul">
                <?php if(isset($jobExperienceIdsArray) && count($jobExperienceIdsArray)): ?>
                <?php $__currentLoopData = $jobExperienceIdsArray; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$job_experience_id): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                $jobExperience = App\JobExperience::where('job_experience_id','=',$job_experience_id)->lang()->active()->first();			  
                ?>
                <?php if(null !== $jobExperience): ?>
                <?php
                $checked = (in_array($jobExperience->job_experience_id, Request::get('job_experience_id', array())))? 'checked="checked"':'';
                ?>
                <li>
                    <input type="checkbox" name="job_experience_id[]" id="job_experience_<?php echo e($jobExperience->job_experience_id); ?>" value="<?php echo e($jobExperience->job_experience_id); ?>" <?php echo e($checked); ?>>
                    <label for="job_experience_<?php echo e($jobExperience->job_experience_id); ?>"></label>
                    <?php echo e($jobExperience->job_experience); ?> <span><?php echo e(App\Job::countNumJobs('job_experience_id', $jobExperience->job_experience_id)); ?></span> </li>
                <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </ul>
            <span class="text text-primary view_more hide_vm"><?php echo e(__('View More')); ?></span> </div>
        <!-- Jobs By Experience end --> 

        <!-- Jobs By Job Type -->
        <div class="widget">
            <h4 class="widget-title"><?php echo e(__('Jobs By Job Type')); ?></h4>
            <ul class="optionlist view_more_ul">
                <?php if(isset($jobTypeIdsArray) && count($jobTypeIdsArray)): ?>
                <?php $__currentLoopData = $jobTypeIdsArray; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$job_type_id): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                $jobType = App\JobType::where('job_type_id','=',$job_type_id)->lang()->active()->first();
                ?>
                <?php if(null !== $jobType): ?>
                <?php
                $checked = (in_array($jobType->job_type_id, Request::get('job_type_id', array())))? 'checked="checked"':'';
                ?>
                <li>
                    <input type="checkbox" name="job_type_id[]" id="job_type_<?php echo e($jobType->job_type_id); ?>" value="<?php echo e($jobType->job_type_id); ?>" <?php echo e($checked); ?>>
                    <label for="job_type_<?php echo e($jobType->job_type_id); ?>"></label>
                    <?php echo e($jobType->job_type); ?> <span><?php echo e(App\Job::countNumJobs('job_type_id', $jobType->job_type_id)); ?></span> </li>
                <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </ul>
            <span class="text text-primary view_more hide_vm"><?php echo e(__('View More')); ?></span> </div>
        <!-- Jobs By Job Type end --> 

        <!-- Jobs By Job Shift -->
        <div class="widget">
            <h4 class="widget-title"><?php echo e(__('Jobs By Job Shift')); ?></h4>
            <ul class="optionlist view_more_ul">
                <?php if(isset($jobShiftIdsArray) && count($jobShiftIdsArray)): ?>
                <?php $__currentLoopData = $jobShiftIdsArray; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$job_shift_id): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                $jobShift = App\JobShift::where('job_shift_id','=',$job_shift_id)->lang()->active()->first();
                ?>
                <?php if(null !== $jobShift): ?>
                <?php
                $checked = (in_array($jobShift->job_shift_id, Request::get('job_shift_id', array())))? 'checked="checked"':'';
                ?>
                <li>
                    <input type="checkbox" name="job_shift_id[]" id="job_shift_<?php echo e($jobShift->job_shift_id); ?>" value="<?php echo e($jobShift->job_shift_id); ?>" <?php echo e($checked); ?>>
                    <label for="job_shift_<?php echo e($jobShift->job_shift_id); ?>"></label>
                    <?php echo e($jobShift->job_shift); ?> <span><?php echo e(App\Job::countNumJobs('job_shift_id', $jobShift->job_shift_id)); ?></span> </li>
                <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </ul>
            <span class="text text-primary view_more hide_vm"><?php echo e(__('View More')); ?></span> </div>
        <!-- Jobs By Job Shift end --> 

        <!-- Jobs By Career Level -->
        <div class="widget">
            <h4 class="widget-title"><?php echo e(__('Jobs By Career Level')); ?></h4>
            <ul class="optionlist view_more_ul">
                <?php if(isset($careerLevelIdsArray) && count($careerLevelIdsArray)): ?>
                <?php $__currentLoopData = $careerLevelIdsArray; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$career_level_id): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                $careerLevel = App\CareerLevel::where('career_level_id','=',$career_level_id)->lang()->active()->first();
                ?>
                <?php if(null !== $careerLevel): ?>
                <?php
                $checked = (in_array($careerLevel->career_level_id, Request::get('career_level_id', array())))? 'checked="checked"':'';
                ?>
                <li>
                    <input type="checkbox" name="career_level_id[]" id="career_level_<?php echo e($careerLevel->career_level_id); ?>" value="<?php echo e($careerLevel->career_level_id); ?>" <?php echo e($checked); ?>>
                    <label for="career_level_<?php echo e($careerLevel->career_level_id); ?>"></label>
                    <?php echo e($careerLevel->career_level); ?> <span><?php echo e(App\Job::countNumJobs('career_level_id', $careerLevel->career_level_id)); ?></span> </li>
                <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </ul>
            <span class="text text-primary view_more hide_vm"><?php echo e(__('View More')); ?></span> </div>
        <!-- Jobs By Career Level end --> 


        <!-- Jobs By Degree Level -->
        <div class="widget">
            <h4 class="widget-title"><?php echo e(__('Jobs By Degree Level')); ?></h4>
            <ul class="optionlist view_more_ul">
                <?php if(isset($degreeLevelIdsArray) && count($degreeLevelIdsArray)): ?>
                <?php $__currentLoopData = $degreeLevelIdsArray; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$degree_level_id): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                $degreeLevel = App\DegreeLevel::where('degree_level_id','=',$degree_level_id)->lang()->active()->first();
                ?>
                <?php if(null !== $degreeLevel): ?>
                <?php
                $checked = (in_array($degreeLevel->degree_level_id, Request::get('degree_level_id', array())))? 'checked="checked"':'';
                ?>
                <li>
                    <input type="checkbox" name="degree_level_id[]" id="degree_level_<?php echo e($degreeLevel->degree_level_id); ?>" value="<?php echo e($degreeLevel->degree_level_id); ?>" <?php echo e($checked); ?>>
                    <label for="degree_level_<?php echo e($degreeLevel->degree_level_id); ?>"></label>
                    <?php echo e($degreeLevel->degree_level); ?> <span><?php echo e(App\Job::countNumJobs('degree_level_id', $degreeLevel->degree_level_id)); ?></span> </li>
                <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </ul>
            <span class="text text-primary view_more hide_vm"><?php echo e(__('View More')); ?></span> </div>
        <!-- Jobs By Degree Level end --> 


        <!-- Jobs By Gender -->
        <div class="widget">
            <h4 class="widget-title"><?php echo e(__('Jobs By Gender')); ?></h4>
            <ul class="optionlist view_more_ul">
                <?php if(isset($genderIdsArray) && count($genderIdsArray)): ?>
                <?php $__currentLoopData = $genderIdsArray; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$gender_id): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                $gender = App\Gender::where('gender_id','=',$gender_id)->lang()->active()->first();
                ?>
                <?php if(null !== $gender): ?>
                <?php
                $checked = (in_array($gender->gender_id, Request::get('gender_id', array())))? 'checked="checked"':'';
                ?>
                <li>
                    <input type="checkbox" name="gender_id[]" id="gender_<?php echo e($gender->gender_id); ?>" value="<?php echo e($gender->gender_id); ?>" <?php echo e($checked); ?>>
                    <label for="gender_<?php echo e($gender->gender_id); ?>"></label>
                    <?php echo e($gender->gender); ?> <span><?php echo e(App\Job::countNumJobs('gender_id', $gender->gender_id)); ?></span> </li>
                <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </ul>
            <span class="text text-primary view_more hide_vm"><?php echo e(__('View More')); ?></span> </div>
        <!-- Jobs By Gender end --> 



        <!-- Jobs By Industry -->
        <div class="widget">
            <h4 class="widget-title"><?php echo e(__('Jobs By Industry')); ?></h4>
            <ul class="optionlist view_more_ul">
                <?php if(isset($industryIdsArray) && count($industryIdsArray)): ?>
                <?php $__currentLoopData = $industryIdsArray; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$industry_id): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                $industry = App\Industry::where('id','=',$industry_id)->lang()->active()->first();
                ?>
                <?php if(null !== $industry): ?>
                <?php
                $checked = (in_array($industry->id, Request::get('industry_id', array())))? 'checked="checked"':'';
                ?>
                <li>
                    <input type="checkbox" name="industry_id[]" id="industry_<?php echo e($industry->id); ?>" value="<?php echo e($industry->id); ?>" <?php echo e($checked); ?>>
                    <label for="industry_<?php echo e($industry->id); ?>"></label>
                    <?php echo e($industry->industry); ?> <span><?php echo e(App\Job::countNumJobs('industry_id', $industry->id)); ?></span> </li>
                <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </ul>
            <span class="text text-primary view_more hide_vm"><?php echo e(__('View More')); ?></span> </div>
        <!-- Jobs By Industry end --> 

        <!-- Jobs By Skill -->
        <div class="widget">
            <h4 class="widget-title"><?php echo e(__('Jobs By Skill')); ?></h4>
            <ul class="optionlist view_more_ul">
                <?php if(isset($skillIdsArray) && count($skillIdsArray)): ?>
                <?php $__currentLoopData = $skillIdsArray; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$job_skill_id): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                $jobSkill = App\JobSkill::where('job_skill_id','=',$job_skill_id)->lang()->active()->first();
                ?>
                <?php if(null !== $jobSkill): ?>

                <?php
                $checked = (in_array($jobSkill->job_skill_id, Request::get('job_skill_id', array())))? 'checked="checked"':'';
                ?>
                <li>
                    <input type="checkbox" name="job_skill_id[]" id="job_skill_<?php echo e($jobSkill->job_skill_id); ?>" value="<?php echo e($jobSkill->job_skill_id); ?>" <?php echo e($checked); ?>>
                    <label for="job_skill_<?php echo e($jobSkill->job_skill_id); ?>"></label>
                    <?php echo e($jobSkill->job_skill); ?> <span><?php echo e(App\Job::countNumJobs('job_skill_id', $jobSkill->job_skill_id)); ?></span> </li>
                <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </ul>
            <span class="text text-primary view_more hide_vm"><?php echo e(__('View More')); ?></span> </div>
        <!-- Jobs By Industry end --> 


        <div class="widget">
            <h4 class="widget-title"><?php echo e(__('Jobs By Functional Areas')); ?></h4>
            <ul class="optionlist view_more_ul">
                <?php if(isset($functionalAreaIdsArray) && count($functionalAreaIdsArray)): ?>
                <?php $__currentLoopData = $functionalAreaIdsArray; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$functional_area_id): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                $functionalArea = App\FunctionalArea::where('functional_area_id','=',$functional_area_id)->lang()->active()->first();
                ?>
                <?php if(null !== $functionalArea): ?>
                <?php
                $checked = (in_array($functionalArea->functional_area_id, Request::get('functional_area_id', array())))? 'checked="checked"':'';
                ?>
                <li>
                    <input type="checkbox" name="functional_area_id[]" id="functional_area_id_<?php echo e($functionalArea->functional_area_id); ?>" value="<?php echo e($functionalArea->functional_area_id); ?>" <?php echo e($checked); ?>>
                    <label for="functional_area_id_<?php echo e($functionalArea->functional_area_id); ?>"></label>
                    <?php echo e($functionalArea->functional_area); ?> <span><?php echo e(App\Job::countNumJobs('functional_area_id', $functionalArea->functional_area_id)); ?></span>
                </li>                
                <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>

            </ul>
            <!-- title end --> 
            <span class="text text-primary view_more hide_vm"><?php echo e(__('View More')); ?></span> </div>


        <!-- Top Companies -->
        <div class="widget">
            <h4 class="widget-title"><?php echo e(__('Jobs By Company')); ?></h4>
            <ul class="optionlist view_more_ul">
                <?php if(isset($companyIdsArray) && count($companyIdsArray)): ?>
                <?php $__currentLoopData = $companyIdsArray; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$company_id): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                $company = App\Company::where('id','=',$company_id)->active()->first();
                ?>
                <?php if(null !== $company): ?>
                <?php
                $checked = (in_array($company->id, Request::get('company_id', array())))? 'checked="checked"':'';
                ?>
                <li>
                    <input type="checkbox" name="company_id[]" id="company_<?php echo e($company->id); ?>" value="<?php echo e($company->id); ?>" <?php echo e($checked); ?>>
                    <label for="company_<?php echo e($company->id); ?>"></label>
                    <?php echo e($company->name); ?> <span><?php echo e(App\Job::countNumJobs('company_id', $company->id)); ?></span> </li>
                <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </ul>
            <span class="text text-primary view_more hide_vm"><?php echo e(__('View More')); ?></span> </div>
        <!-- Top Companies end --> 

        <!-- Salary -->
        <div class="widget">
            <h4 class="widget-title"><?php echo e(__('Salary Range')); ?></h4>
            <div class="form-group">
                <?php echo Form::number('salary_from', Request::get('salary_from', null), array('class'=>'form-control', 'id'=>'salary_from', 'placeholder'=>__('Salary From'))); ?>

            </div>
            <div class="form-group">
                <?php echo Form::number('salary_to', Request::get('salary_to', null), array('class'=>'form-control', 'id'=>'salary_to', 'placeholder'=>__('Salary To'))); ?>

            </div>
            <div class="form-group">
                <?php echo Form::select('salary_currency', ['' =>__('Select Salary Currency')]+$currencies, Request::get('salary_currency', $siteSetting->default_currency_code), array('class'=>'form-control', 'id'=>'salary_currency')); ?>

            </div>
            <!-- Salary end --> 

            <!-- button -->
            <div class="searchnt">
                <button type="submit" class="btn"><i class="fa fa-search" aria-hidden="true"></i> <?php echo e(__('Search Jobs')); ?></button>
            </div>
            <!-- button end--> 
        </div>
        <!-- Side Bar end --> 
    </div>
</div>
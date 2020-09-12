<h5><?php echo e(__('Job Details')); ?></h5>
<?php if(isset($job)): ?>
<?php echo Form::model($job, array('method' => 'put', 'route' => array('update.front.job', $job->id), 'class' => 'form')); ?>

<?php echo Form::hidden('id', $job->id); ?>

<?php else: ?>
<?php echo Form::open(array('method' => 'post', 'route' => array('store.front.job'), 'class' => 'form')); ?>

<?php endif; ?>
<div class="row">
    <div class="col-md-12">
        <div class="formrow <?php echo APFrmErrHelp::hasError($errors, 'title'); ?>"> <?php echo Form::text('title', null, array('class'=>'form-control', 'id'=>'title', 'placeholder'=>__('Job title'))); ?>

            <?php echo APFrmErrHelp::showErrors($errors, 'title'); ?> </div>
    </div>
    <div class="col-md-12">
        <div class="formrow <?php echo APFrmErrHelp::hasError($errors, 'description'); ?>"> <?php echo Form::textarea('description', null, array('class'=>'form-control', 'id'=>'description', 'placeholder'=>__('Job description'))); ?>

            <?php echo APFrmErrHelp::showErrors($errors, 'description'); ?> </div>
    </div>
    <div class="col-md-12">
        <div class="formrow <?php echo APFrmErrHelp::hasError($errors, 'skills'); ?>">
            <?php
            $skills = old('skills', $jobSkillIds);
            ?>
            <?php echo Form::select('skills[]', $jobSkills, $skills, array('class'=>'form-control select2-multiple', 'id'=>'skills', 'multiple'=>'multiple')); ?>

            <?php echo APFrmErrHelp::showErrors($errors, 'skills'); ?> </div>
    </div>
    <div class="col-md-12">
        <div class="formrow <?php echo APFrmErrHelp::hasError($errors, 'country_id'); ?>" id="country_id_div"> <?php echo Form::select('country_id', ['' => __('Select Country')]+$countries, old('country_id', (isset($job))? $job->country_id:$siteSetting->default_country_id), array('class'=>'form-control', 'id'=>'country_id')); ?>

            <?php echo APFrmErrHelp::showErrors($errors, 'country_id'); ?> </div>
    </div>

    <div class="col-md-6">
        <div class="formrow <?php echo APFrmErrHelp::hasError($errors, 'salary_from'); ?>" id="salary_from_div"> <?php echo Form::number('salary_from', null, array('class'=>'form-control', 'id'=>'salary_from', 'placeholder'=>__('Budget'))); ?>

            <?php echo APFrmErrHelp::showErrors($errors, 'salary_from'); ?> </div>
    </div>

    <div class="col-md-6">
        <div class="formrow <?php echo APFrmErrHelp::hasError($errors, 'salary_period_id'); ?>" id="salary_period_id_div"> <?php echo Form::select('salary_period_id', ['' => __('Select Project length')]+$salaryPeriods, null, array('class'=>'form-control', 'id'=>'salary_period_id')); ?>

            <?php echo APFrmErrHelp::showErrors($errors, 'salary_period_id'); ?> </div>
    </div>
    <div class="col-md-6">
        <div class="formrow <?php echo APFrmErrHelp::hasError($errors, 'career_level_id'); ?>" id="career_level_id_div"> <?php echo Form::select('career_level_id', ['' => __('Select Career level')]+$careerLevels, null, array('class'=>'form-control', 'id'=>'career_level_id')); ?>

            <?php echo APFrmErrHelp::showErrors($errors, 'career_level_id'); ?> </div>
    </div>

    <div class="col-md-6">
        <div class="formrow <?php echo APFrmErrHelp::hasError($errors, 'functional_area_id'); ?>" id="functional_area_id_div"> <?php echo Form::select('functional_area_id', ['' => __('Select Functional Area')]+$functionalAreas, null, array('class'=>'form-control', 'id'=>'functional_area_id')); ?>

            <?php echo APFrmErrHelp::showErrors($errors, 'functional_area_id'); ?> </div>
    </div>
    <div class="col-md-6">
        <div class="formrow <?php echo APFrmErrHelp::hasError($errors, 'job_type_id'); ?>" id="job_type_id_div"> <?php echo Form::select('job_type_id', ['' => __('Select Job Type')]+$jobTypes, null, array('class'=>'form-control', 'id'=>'job_type_id')); ?>

            <?php echo APFrmErrHelp::showErrors($errors, 'job_type_id'); ?> </div>
    </div>
    <div class="col-md-6">
        <div class="formrow <?php echo APFrmErrHelp::hasError($errors, 'num_of_positions'); ?>" id="num_of_positions_div"> <?php echo Form::select('num_of_positions', ['' => __('Select number of Positions')]+MiscHelper::getNumPositions(), null, array('class'=>'form-control', 'id'=>'num_of_positions')); ?>

            <?php echo APFrmErrHelp::showErrors($errors, 'num_of_positions'); ?> </div>
    </div>
    <div class="col-md-6">
        <div class="formrow <?php echo APFrmErrHelp::hasError($errors, 'job_rate_type'); ?>" id="job_rate_type_div"> <?php echo Form::select('job_rate_type', ['' => __('Select Job Rate Type')]+$jobRateType, null, array('class'=>'form-control', 'id'=>'job_rate_type')); ?>

            <?php echo APFrmErrHelp::showErrors($errors, 'job_rate_type'); ?> 
        </div>
    </div>
    <div class="col-md-6" style="display:none;">
        <div class="formrow <?php echo APFrmErrHelp::hasError($errors, 'is_freelance'); ?>"> <?php echo Form::label('is_freelance', __('Is Freelance?'), ['class' => 'bold']); ?>

            <div class="radio-list">
                <?php
                $is_freelance_1 = 'checked="checked"';
                $is_freelance_2 = '';
                if (old('is_freelance', ((isset($job)) ? $job->is_freelance : 0)) == 1) {
                    $is_freelance_1 = 'checked="checked"';
                    $is_freelance_2 = '';
                }
                ?>
                <label class="radio-inline">
                    <input id="is_freelance_yes" name="is_freelance" type="radio" value="1" <?php echo e($is_freelance_1); ?>>
                    <?php echo e(__('Yes')); ?> </label>
                <label class="radio-inline">
                    <input id="is_freelance_no" name="is_freelance" type="radio" value="0" <?php echo e($is_freelance_2); ?>>
                    <?php echo e(__('No')); ?> </label>
            </div>
            <?php echo APFrmErrHelp::showErrors($errors, 'is_freelance'); ?> </div>
    </div>
    <div class="col-md-12">
        <div class="formrow">
            <?php if(isset($job)): ?>
            <button type="submit" class="btn"><?php echo e(__('Update Job')); ?> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></button>
            <?php else: ?>
            <button type="submit" class="btn"><?php echo e(__('Create Job')); ?><i class="fa fa-arrow-circle-right" aria-hidden="true"></i></button>
            <?php endif; ?>

        </div>
    </div>
</div>
<input type="file" name="image" id="image" style="display:none;" accept="image/*"/>
<?php echo Form::close(); ?>

<hr>
<?php $__env->startPush('styles'); ?>
<style type="text/css">
    .datepicker>div {
        display: block;
    }
</style>
<?php $__env->stopPush(); ?>
<?php $__env->startPush('scripts'); ?>
<?php echo $__env->make('includes.tinyMCEFront', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<script type="text/javascript">
    $(document).ready(function () {
        $('.select2-multiple').select2({
            placeholder: "<?php echo e(__('Select Required Skills')); ?>",
            allowClear: true
        });
        $(".datepicker").datepicker({
            autoclose: true,
            format: 'yyyy-m-d'
        });
        $('#country_id').on('change', function (e) {
            e.preventDefault();
            filterLangStates(0);
        });
        $(document).on('change', '#state_id', function (e) {
            e.preventDefault();
            filterLangCities(0);
        });
        filterLangStates(<?php echo old('state_id', (isset($job)) ? $job->state_id : 0); ?>);
    });
    function filterLangStates(state_id)
    {
        var country_id = $('#country_id').val();
        if (country_id != '') {
            $.post("<?php echo e(route('filter.lang.states.dropdown')); ?>", {country_id: country_id, state_id: state_id, _method: 'POST', _token: '<?php echo e(csrf_token()); ?>'})
                    .done(function (response) {
                        $('#default_state_dd').html(response);
                        filterLangCities(<?php echo old('city_id', (isset($job)) ? $job->city_id : 0); ?>);
                    });
        }
    }
    function filterLangCities(city_id)
    {
        var state_id = $('#state_id').val();
        if (state_id != '') {
            $.post("<?php echo e(route('filter.lang.cities.dropdown')); ?>", {state_id: state_id, city_id: city_id, _method: 'POST', _token: '<?php echo e(csrf_token()); ?>'})
                    .done(function (response) {
                        $('#default_city_dd').html(response);
                    });
        }
    }
</script>
<?php $__env->stopPush(); ?>
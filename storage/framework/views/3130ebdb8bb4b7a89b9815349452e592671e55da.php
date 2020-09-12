<?php $__env->startSection('content'); ?> 
<!-- Header start --> 
<?php echo $__env->make('includes.header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?> 
<!-- Header end --> 
<!-- Inner Page Title start --> 
<?php echo $__env->make('includes.inner_page_title', ['page_title'=>__('Apply on Job')], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?> 
<!-- Inner Page Title end -->
<div class="listpgWraper">
    <div class="container"> <?php echo $__env->make('flash::message', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="userccount">
                    <div class="formpanel"> <?php echo Form::open(array('method' => 'post', 'route' => ['post.apply.job', $job_slug])); ?> 
                        <!-- Job Information -->
                        <h5><?php echo e($job->title); ?></h5>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="formrow<?php echo e($errors->has('cover_letter') ? ' has-error' : ''); ?>">
                                <?php echo Form::textarea('cover_letter', null, ['id' => 'cover_letter', 'rows' => 4, 'cols' => 54, 'style' => 'resize:none;margin: 0px; width: 687px; height: 430px;', 'placeholder'=>__('Cover Letter')]); ?>

                                    
                                    <?php if($errors->has('cover_letter')): ?> <span class="help-block"> <strong><?php echo e($errors->first('cover_letter')); ?></strong> </span> <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="formrow<?php echo e($errors->has('expected_salary') ? ' has-error' : ''); ?>"> <?php echo Form::number('expected_salary', null, array('class'=>'form-control', 'id'=>'expected_salary', 'placeholder'=>__('Expected Cost').'')); ?>

                                    <?php if($errors->has('expected_salary')): ?> <span class="help-block"> <strong><?php echo e($errors->first('expected_salary')); ?></strong> </span> <?php endif; ?>
                                </div>
                            </div>
                        
                            <div class="col-md-6">
                                <div class="formrow<?php echo e($errors->has('salary_period_id') ? ' has-error' : ''); ?>" id="salary_period_id_div"> <?php echo Form::select('salary_period_id', ['' => __('Select Project length')]+$salaryPeriods, null, array('class'=>'form-control', 'id'=>'salary_period_id')); ?>

                                    <?php echo APFrmErrHelp::showErrors($errors, 'salary_period_id'); ?> </div>
                            </div>
                            <div class="col-md-6">
                            	<span id="cost_show"></span>	
                            </div>
                        </div>
                        <br>
                        <input type="submit" class="btn" value="<?php echo e(__('Apply on Job')); ?>">
                        <?php echo Form::close(); ?> </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $__env->make('includes.footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('scripts'); ?> 
<script>
    $(document).ready(function () {
        var real_val = '<?php echo $percentage->percent; ?>';
        $("#expected_salary").keyup(function(){
            var value = $(this).val();
            if(value > 0){
                listPrice = parseFloat(value);
                discount  = parseFloat(real_val);
                var payFee = 6;
                var show_Cost = listPrice - ( listPrice * discount / 100 ).toFixed(2);
                var showCost = show_Cost - ( show_Cost * payFee / 100 );
                $("#cost_show").text("You will receive "+showCost.toFixed(2)+" USD");
            }else{
                $("#cost_show").text("You need to set some amount");
            }
        });
        $('#salary_currency').typeahead({
            source: function (query, process) {
                return $.get("<?php echo e(route('typeahead.currency_codes')); ?>", {query: query}, function (data) {
                    console.log(data);
                    data = $.parseJSON(data);
                    return process(data);
                });
            }
        });

    });
</script> 
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
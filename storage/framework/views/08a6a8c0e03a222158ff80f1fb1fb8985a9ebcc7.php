<div class="">
	<div class="formpanel">
		<?php echo $__env->make('flash::message', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
		<h5><?php echo e(__('Paypal - Card Details')); ?></h5>
		<p>Please fix your project amount for hire this person for Candidate security purpose.</p>
		<?php
		$new_or_upgrade = 'new';
		$route = 'order.upgrade.package';
		if($new_or_upgrade == 'new'){
		    $route = 'pay.fee';
		} 
		?>
		<?php echo Form::open(array('method' => 'post', 'route' => $route, 'id' => 'paypal-form', 'class' => 'form')); ?>

		<?php echo e(Form::hidden('pay_type', 'credit_card')); ?>

		<div class="row">
			<div class="col-md-12" id="error_div"></div>
			<div class="col-md-12">
				<div class="formrow">
					<label><?php echo e(__('Name on Credit Card')); ?></label>
					<input class="form-control" autocomplete="off" name="card_name" id="card_name" placeholder="<?php echo e(__('Name on Credit Card')); ?>" type="text">
				</div>
			</div>
			<div class="col-md-12">
				<div class="formrow">
					<label><?php echo e(__('Credit card Number')); ?></label>
					<input class="form-control" id="card_no" name="card_no" autocomplete="off" placeholder="<?php echo e(__('Credit card Number')); ?>" type="text">
				</div>
			</div>
			<div class="col-md-6">
				<div class="formrow">
					<label><?php echo e(__('Credit card Expiry Month')); ?></label>
					<select class="form-control" name="ccExpiryMonth" id="ccExpiryMonth">
						<?php for($counter = 1; $counter <= 12; $counter++): ?>
						<?php
						$val = str_pad($counter, 2, '0', STR_PAD_LEFT);
						?>
						<option value="<?php echo e($val); ?>"><?php echo e($val); ?></option>
						<?php endfor; ?>
					</select>
				</div>
			</div>
			<div class="col-md-6">
				<div class="formrow">
					<label><?php echo e(__('Credit card Expiry Year')); ?></label>
					<select class="form-control" name="ccExpiryYear" id="ccExpiryYear">
						<?php
						$ccYears = MiscHelper::getCcExpiryYears();
						?>
						<?php $__currentLoopData = $ccYears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<option value="<?php echo e($year); ?>"><?php echo e($year); ?></option>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</select>
				</div>
			</div>
			<div class="col-md-12">
				<div class="formrow">
					<label><?php echo e(__('CVV Number')); ?></label>
					<input class="form-control" id="cvvNumber" name="cvvNumber" autocomplete="off" placeholder="<?php echo e(__('CVV number')); ?>" type="number" pattern="\d*" maxlength="4">
				</div>
			</div>
			<div class="col-md-12">
				<div class="formrow">
					<button type="submit" class="btn"><?php echo e(__('Pay')); ?> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></button>
				</div>
			</div>
		</div>
		<?php echo Form::close(); ?>

		<hr>
	</div>
</div>
<?php $__env->startPush('scripts'); ?> 
<script type="text/javascript">
var d = new Date();
var n = d.getMonth();
n = n+1;
if(n<10){
    n = '0'+n;
}
$("#card_no").attr({
    maxlength: 16
});
$("#ccExpiryMonth").val(n);
</script> 
<?php $__env->stopPush(); ?>
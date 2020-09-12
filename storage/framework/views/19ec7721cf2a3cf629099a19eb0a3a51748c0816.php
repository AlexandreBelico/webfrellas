<?php if(Auth::guard('company')->check()): ?>
<form action="<?php echo e(route('job.seeker.list')); ?>" method="get">
    <div class="searchbar">
		<div class="srchbox">
		<div class="row srcsubfld additional_fields">
		<div class="col-md-<?php echo e(((bool)$siteSetting->country_specific_site)? 6:3); ?>">
            <?php echo Form::select('functional_area_id[]', ['' => __('Select Functional Area')]+$functionalAreas, Request::get('functional_area_id', null), array('class'=>'form-control', 'id'=>'functional_area_id')); ?>

        </div>

        <?php if((bool)$siteSetting->country_specific_site): ?>
        <?php echo Form::hidden('country_id[]', Request::get('country_id[]', $siteSetting->default_country_id), array('id'=>'country_id')); ?>

        <?php else: ?>
        <div class="col-md-3">
            <?php echo Form::select('country_id[]', ['' => __('Select Country')]+$countries, Request::get('country_id', $siteSetting->default_country_id), array('class'=>'form-control', 'id'=>'country_id')); ?>

        </div>
        <?php endif; ?>

        <div class="col-md-3">
            <span id="state_dd">
                <?php echo Form::select('state_id[]', ['' => __('Select State')], Request::get('state_id', null), array('class'=>'form-control', 'id'=>'state_id')); ?>

            </span>
        </div>
        <div class="col-md-3">
            <span id="city_dd">
                <?php echo Form::select('city_id[]', ['' => __('Select City')], Request::get('city_id', null), array('class'=>'form-control', 'id'=>'city_id')); ?>

            </span>
        </div>
		</div>
		
		
		<div class="input-group">
		  <input type="text"  name="search" id="empsearch" value="<?php echo e(Request::get('search', '')); ?>" class="form-control" placeholder="<?php echo e(__('Enter Skills or Job Seeker Details')); ?>" autocomplete="off" />
		  <span class="input-group-btn">
			<input type="submit" class="btn" value="<?php echo e(__('Search Job Seeker')); ?>">
		  </span>
		</div>
		</div>
		
       
        
    </div>
</form>
<?php else: ?>
<form action="<?php echo e(route('job.list')); ?>" method="get">
    <div class="searchbar">
		<div class="srchbox">
		<div class="row srcsubfld additional_fields">
        <div class="col-md-<?php echo e(((bool)$siteSetting->country_specific_site)? 6:3); ?>">
            <?php echo Form::select('functional_area_id[]', ['' => __('Select Functional Area')]+$functionalAreas, Request::get('functional_area_id', null), array('class'=>'form-control', 'id'=>'functional_area_id')); ?>

        </div>

        <?php if((bool)$siteSetting->country_specific_site): ?>
        <?php echo Form::hidden('country_id[]', Request::get('country_id[]', $siteSetting->default_country_id), array('id'=>'country_id')); ?>

        <?php else: ?>
        <div class="col-md-3">
            <?php echo Form::select('country_id[]', ['' => __('Select Country')]+$countries, Request::get('country_id', $siteSetting->default_country_id), array('class'=>'form-control', 'id'=>'country_id')); ?>

        </div>
        <?php endif; ?>

        <div class="col-md-3">
            <span id="state_dd">
                <?php echo Form::select('state_id[]', ['' => __('Select State')], Request::get('state_id', null), array('class'=>'form-control', 'id'=>'state_id')); ?>

            </span>
        </div>
        <div class="col-md-3">
            <span id="city_dd">
                <?php echo Form::select('city_id[]', ['' => __('Select City')], Request::get('city_id', null), array('class'=>'form-control', 'id'=>'city_id')); ?>

            </span>
        </div>
		</div>
		
		
		<div class="input-group">
		  <input type="text"  name="search" id="jbsearch" value="<?php echo e(Request::get('search', '')); ?>" class="form-control" placeholder="<?php echo e(__('Enter Skills or job title')); ?>" autocomplete="off" />
		  <span class="input-group-btn">
			<input type="submit" class="btn" value="<?php echo e(__('Search Job')); ?>">
		  </span>
		</div>
		</div>
      
		
		
		
		
    </div>
</form>
<?php endif; ?>
<h5><?php echo e(__('Personal Information')); ?></h5>
<?php echo Form::model($company, array('method' => 'put', 'route' => array('update.company.profile'), 'class' => 'form', 'files'=>true)); ?>

<h6><?php echo e(__('Logo')); ?></h6>
<div class="row">
    <div class="col-md-6">
        <div class="formrow"> <?php echo e(ImgUploader::print_image("company_logos/$company->logo", 100, 100)); ?> </div>
    </div>
    <div class="col-md-6">
        <div class="formrow">
            <div id="thumbnail"></div>
            <label class="btn btn-default"> <?php echo e(__('Select Logo')); ?>

                <input type="file" name="logo" id="logo" style="display: none;">
            </label>
            <?php echo APFrmErrHelp::showErrors($errors, 'logo'); ?> </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="formrow <?php echo APFrmErrHelp::hasError($errors, 'name'); ?>"> <?php echo Form::text('name', null, array('class'=>'form-control', 'id'=>'name', 'placeholder'=>__('Company Name'))); ?>

            <?php echo APFrmErrHelp::showErrors($errors, 'name'); ?> </div>
    </div>
    <div style="display:none;" class="col-md-4">
        <div class="formrow <?php echo APFrmErrHelp::hasError($errors, 'email'); ?>"> <?php echo Form::text('email', null, array('class'=>'form-control', 'id'=>'email', 'placeholder'=>__('Company Email'))); ?>

            <?php echo APFrmErrHelp::showErrors($errors, 'email'); ?> </div>
    </div>
    <div style="display:none;"class="col-md-4">
        <div class="formrow <?php echo APFrmErrHelp::hasError($errors, 'password'); ?>"> <?php echo Form::password('password', array('class'=>'form-control', 'id'=>'password', 'placeholder'=>__('Password'))); ?>

            <?php echo APFrmErrHelp::showErrors($errors, 'password'); ?> </div>
    </div>
    <div class="col-md-6">
        <div class="formrow <?php echo APFrmErrHelp::hasError($errors, 'ceo'); ?>"> <?php echo Form::text('ceo', null, array('class'=>'form-control', 'id'=>'ceo', 'placeholder'=>__('Company CEO'))); ?>

            <?php echo APFrmErrHelp::showErrors($errors, 'ceo'); ?> </div>
    </div>
    <div class="col-md-6">
        <div class="formrow <?php echo APFrmErrHelp::hasError($errors, 'industry_id'); ?>"> <?php echo Form::select('industry_id', ['' => __('Select Industry')]+$industries, null, array('class'=>'form-control', 'id'=>'industry_id')); ?>

            <?php echo APFrmErrHelp::showErrors($errors, 'industry_id'); ?> </div>
    </div>
    <div class="col-md-6">
        <div class="formrow <?php echo APFrmErrHelp::hasError($errors, 'ownership_type'); ?>"> <?php echo Form::select('ownership_type_id', ['' => __('Select Ownership type')]+$ownershipTypes, null, array('class'=>'form-control', 'id'=>'ownership_type_id')); ?>

            <?php echo APFrmErrHelp::showErrors($errors, 'ownership_type_id'); ?> </div>
    </div>
    <div class="col-md-12">
        <div class="formrow <?php echo APFrmErrHelp::hasError($errors, 'description'); ?>"> <?php echo Form::textarea('description', null, array('class'=>'form-control', 'id'=>'description', 'placeholder'=>__('Company details'))); ?>

            <?php echo APFrmErrHelp::showErrors($errors, 'description'); ?> </div>
    </div>
    <div class="col-md-12">
        <div class="formrow <?php echo APFrmErrHelp::hasError($errors, 'location'); ?>"> <?php echo Form::text('location', null, array('class'=>'form-control', 'id'=>'location', 'placeholder'=>__('Location'))); ?>

            <?php echo APFrmErrHelp::showErrors($errors, 'location'); ?> </div>
    </div>
    <div class="col-md-4">
        <div class="formrow <?php echo APFrmErrHelp::hasError($errors, 'no_of_offices'); ?>"> <?php echo Form::select('no_of_offices', ['' => __('Select num. of offices')]+MiscHelper::getNumOffices(), null, array('class'=>'form-control', 'id'=>'no_of_offices')); ?>

            <?php echo APFrmErrHelp::showErrors($errors, 'no_of_offices'); ?> </div>
    </div>
    <div class="col-md-4">
        <div class="formrow <?php echo APFrmErrHelp::hasError($errors, 'website'); ?>"> <?php echo Form::text('website', null, array('class'=>'form-control', 'id'=>'website', 'placeholder'=>__('Website'))); ?>

            <?php echo APFrmErrHelp::showErrors($errors, 'website'); ?> </div>
    </div>
    <div class="col-md-4">
        <div class="formrow <?php echo APFrmErrHelp::hasError($errors, 'no_of_employees'); ?>"> <?php echo Form::select('no_of_employees', ['' => __('Select num. of employees')]+MiscHelper::getNumEmployees(), null, array('class'=>'form-control', 'id'=>'no_of_employees')); ?>

            <?php echo APFrmErrHelp::showErrors($errors, 'no_of_employees'); ?> </div>
    </div>
    <div class="col-md-4">
        <div class="formrow <?php echo APFrmErrHelp::hasError($errors, 'established_in'); ?>"> <?php echo Form::select('established_in', ['' => __('Select Established In')]+MiscHelper::getEstablishedIn(), null, array('class'=>'form-control', 'id'=>'established_in')); ?>

            <?php echo APFrmErrHelp::showErrors($errors, 'established_in'); ?> </div>
    </div>
    <div class="col-md-4">
        <div class="formrow <?php echo APFrmErrHelp::hasError($errors, 'fax'); ?>"> <?php echo Form::text('fax', null, array('class'=>'form-control', 'id'=>'fax', 'placeholder'=>__('Fax'))); ?>

            <?php echo APFrmErrHelp::showErrors($errors, 'fax'); ?> </div>
    </div>
    <div class="col-md-4">
        <div class="formrow <?php echo APFrmErrHelp::hasError($errors, 'phone'); ?>"> <?php echo Form::text('phone', null, array('class'=>'form-control', 'id'=>'phone', 'placeholder'=>__('Phone'))); ?>

            <?php echo APFrmErrHelp::showErrors($errors, 'phone'); ?> </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-md-6">
        <div class="formrow <?php echo APFrmErrHelp::hasError($errors, 'facebook'); ?>"> <?php echo Form::text('facebook', null, array('class'=>'form-control', 'id'=>'facebook', 'placeholder'=>__('Facebook'))); ?>

            <?php echo APFrmErrHelp::showErrors($errors, 'facebook'); ?> </div>
    </div>
    <div class="col-md-6">
        <div class="formrow <?php echo APFrmErrHelp::hasError($errors, 'twitter'); ?>"> <?php echo Form::text('twitter', null, array('class'=>'form-control', 'id'=>'twitter', 'placeholder'=>__('Twitter'))); ?>

            <?php echo APFrmErrHelp::showErrors($errors, 'twitter'); ?> </div>
    </div>
    <div class="col-md-4">
        <div class="formrow <?php echo APFrmErrHelp::hasError($errors, 'linkedin'); ?>"> <?php echo Form::text('linkedin', null, array('class'=>'form-control', 'id'=>'linkedin', 'placeholder'=>__('Linkedin'))); ?>

            <?php echo APFrmErrHelp::showErrors($errors, 'linkedin'); ?> </div>
    </div>
    <div class="col-md-4">
        <div class="formrow <?php echo APFrmErrHelp::hasError($errors, 'google_plus'); ?>"> <?php echo Form::text('google_plus', null, array('class'=>'form-control', 'id'=>'google_plus', 'placeholder'=>__('Google+'))); ?>

            <?php echo APFrmErrHelp::showErrors($errors, 'google_plus'); ?> </div>
    </div>
    <div class="col-md-4">
        <div class="formrow <?php echo APFrmErrHelp::hasError($errors, 'pinterest'); ?>"> <?php echo Form::text('pinterest', null, array('class'=>'form-control', 'id'=>'pinterest', 'placeholder'=>__('Pinterest'))); ?>

            <?php echo APFrmErrHelp::showErrors($errors, 'pinterest'); ?> </div>
    </div>
    <div class="col-md-4">
        <div class="formrow <?php echo APFrmErrHelp::hasError($errors, 'country_id'); ?>"> <?php echo Form::select('country_id', ['' => __('Select Country')]+$countries, old('country_id', (isset($company))? $company->country_id:$siteSetting->default_country_id), array('class'=>'form-control', 'id'=>'country_id')); ?>

            <?php echo APFrmErrHelp::showErrors($errors, 'country_id'); ?> </div>
    </div>
    <div class="col-md-4">
        <div class="formrow <?php echo APFrmErrHelp::hasError($errors, 'state_id'); ?>"> <span id="default_state_dd"> <?php echo Form::select('state_id', ['' => __('Select State')], null, array('class'=>'form-control', 'id'=>'state_id')); ?> </span> <?php echo APFrmErrHelp::showErrors($errors, 'state_id'); ?> </div>
    </div>
    <div class="col-md-4">
        <div class="formrow <?php echo APFrmErrHelp::hasError($errors, 'city_id'); ?>"> <span id="default_city_dd"> <?php echo Form::select('city_id', ['' => __('Select City')], null, array('class'=>'form-control', 'id'=>'city_id')); ?> </span> <?php echo APFrmErrHelp::showErrors($errors, 'city_id'); ?> </div>
    </div>
    <div style="display:none;" class="col-md-12">
        <div class="formrow <?php echo APFrmErrHelp::hasError($errors, 'map'); ?>"> <?php echo Form::textarea('map', null, array('class'=>'form-control', 'id'=>'map', 'placeholder'=>__('Google Map'))); ?>

            <?php echo APFrmErrHelp::showErrors($errors, 'map'); ?> </div>
    </div>
    <div style="display:none;" class="col-md-12">
    <div class="formrow <?php echo APFrmErrHelp::hasError($errors, 'is_subscribed'); ?>">
    <?php
	$is_checked = 'checked="checked"';	
	//if (old('is_subscribed', ((isset($company)) ? $company->is_subscribed : 1)) == 0) {
//		$is_checked = '';
//	}
	?>
      <input type="checkbox" value="1" name="is_subscribed" <?php echo e($is_checked); ?> />
      <?php echo e(__('Subscribe to news letter')); ?>

      <?php echo APFrmErrHelp::showErrors($errors, 'is_subscribed'); ?>

      </div>
  </div>
    <div class="col-md-12">
        <div class="formrow">
            <button type="submit" class="btn"><?php echo e(__('Update Profile and Save')); ?> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></button>
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
        $('#country_id').on('change', function (e) {
            e.preventDefault();
            filterLangStates(0);
        });
        $(document).on('change', '#state_id', function (e) {
            e.preventDefault();
            filterLangCities(0);
        });
        filterLangStates(<?php echo old('state_id', (isset($company)) ? $company->state_id : 0); ?>);

        /*******************************/
        var fileInput = document.getElementById("logo");
        fileInput.addEventListener("change", function (e) {
            var files = this.files
            showThumbnail(files)
        }, false)
    });

    function showThumbnail(files) {
        $('#thumbnail').html('');
        for (var i = 0; i < files.length; i++) {
            var file = files[i]
            var imageType = /image.*/
            if (!file.type.match(imageType)) {
                console.log("Not an Image");
                continue;
            }
            var reader = new FileReader()
            reader.onload = (function (theFile) {
                return function (e) {
                    $('#thumbnail').append('<div class="fileattached"><img height="100px" src="' + e.target.result + '" > <div>' + theFile.name + '</div><div class="clearfix"></div></div>');
                };
            }(file))
            var ret = reader.readAsDataURL(file);
        }
    }


    function filterLangStates(state_id)
    {
        var country_id = $('#country_id').val();
        if (country_id != '') {
            $.post("<?php echo e(route('filter.lang.states.dropdown')); ?>", {country_id: country_id, state_id: state_id, _method: 'POST', _token: '<?php echo e(csrf_token()); ?>'})
                    .done(function (response) {
                        $('#default_state_dd').html(response);
                        filterLangCities(<?php echo old('city_id', (isset($company)) ? $company->city_id : 0); ?>);
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
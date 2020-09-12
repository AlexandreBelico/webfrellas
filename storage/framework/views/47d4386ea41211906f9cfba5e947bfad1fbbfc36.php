<div class="subscribe">
  <div class="container">
    <h6><?php echo e(__('Subscribe to our newsletter')); ?></h6> 
    <p><?php echo e(__('Subscribe to our newsletter and stay updated on the latest developments and special offers!')); ?></p>
    <div class="row">
      <div class="col-md-8 col-md-offset-2">
      <div id="alert_messages"></div>       
       <form method="post" action="<?php echo e(route('subscribe.newsletter')); ?>" name="subscribe_newsletter_form" id="subscribe_newsletter_form">
      <?php echo e(csrf_field()); ?>

		   
		   
		   <div class="row">
			   <div class="col-md-5"><input type="text" class="form-control" placeholder="<?php echo e(__('Name')); ?>" name="name" id="name" required="required"></div>
			    <div class="col-md-5"><input type="text" class="form-control" placeholder="<?php echo e(__('Email')); ?>" name="email" id="email" required="required"></div>
			    <div class="col-md-2"><button class="btn btn-default" type="button" id="send_subscription_form"><?php echo e(__('Subscribe')); ?></button></div>		 
		</div>
		   
		   
      
      
      
    
</form>         
      </div>
    </div>
  </div>
</div>

<!--<div class="section greybg">
  <div class="row">
    <div class="col-md-1"></div>
    <div class="col-md-10 align-center"><?php echo $siteSetting->index_page_below_subscribe_ad; ?></div>
    <div class="col-md-1"></div>
  </div>
</div>-->


<?php $__env->startPush('scripts'); ?> 
<script type="text/javascript">
    $(document).ready(function () {
        $(document).on('click', '#send_subscription_form', function () {
            var postData = $('#subscribe_newsletter_form').serialize();
            $.ajax({
                type: 'POST',
                url: "<?php echo e(route('subscribe.newsletter')); ?>",
                data: postData,
                //dataType: 'json',
                success: function (data)
                {
                    response = JSON.parse(data);
                    var res = response.success;
                    if (res == 'success')
                    {
                        var errorString = '<div role="alert" class="alert alert-success">' + response.message + '</div>';
                        $('#alert_messages').html(errorString);
                        $('#subscribe_newsletter_form').hide('slow');
                        $(document).scrollTo('.alert', 2000);
                    } else
                    {
                        var errorString = '<div class="alert alert-danger" role="alert"><ul>';
                        response = JSON.parse(data);
                        $.each(response, function (index, value)
                        {
                            errorString += '<li>' + value + '</li>';
                        });
                        errorString += '</ul></div>';
                        $('#alert_messages').html(errorString);
                        $(document).scrollTo('.alert', 2000);
                    }
                },
            });
        });
    });
</script> 
<?php $__env->stopPush(); ?>
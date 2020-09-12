<?php $__env->startSection('content'); ?>
<table border="0" cellpadding="0" cellspacing="0" class="force-row" style="width: 100%;    border-bottom: solid 1px #ccc;">
    <tr>
        <td class="content-wrapper" style="padding-left:24px;padding-right:24px"><br>
            <div class="title" style="font-family: Helvetica, Arial, sans-serif; font-size: 18px;font-weight:600;color: #18a6fd;text-align: center;
                 padding-top: 20px;">Hi <?php echo e($name); ?>,</div>
        </td>
    </tr>
    <tr>
        <td class="cols-wrapper" style="padding-left:12px;padding-right:12px">
            <!--[if mso]>
         <table border="0" width="576" cellpadding="0" cellspacing="0" style="width: 576px;">
            <tr>
               <td width="192" style="width: 192px;" valign="top">
                  <![endif]-->
            <table border="0" cellpadding="0" cellspacing="0" align="left" class="force-row" style="width: 100%;">
                <tr>
                    <td class="row" valign="top" style="padding-left:12px;padding-right:12px;padding-top:0px;padding-bottom:12px">
                        <table border="0" cellpadding="0" cellspacing="0" style="width:100%;">
                            <tr>
                                <div class="subtitle" style="font-family:Helvetica, Arial, sans-serif;font-size:16px;line-height:16px;font-weight:400;color:#333;padding-bottom:20px; text-align: center;">
                                    <br>
                                     <?php echo e($user); ?> has changed the milestone status for the job : <b> <?php echo e($jobname); ?> </b>.
                                </div>
                            </tr>
                            <tr>
                              <div style="font-family: Helvetica, Arial, sans-serif;font-size: 15px;font-weight: 400;color: #333;text-align: center;">Thanks,</div>
                            </tr>
                            <tr>
                                <div style="font-family: Helvetica, Arial, sans-serif;font-size: 15px;line-height: 8px;font-weight: 600;color: darkslategray; padding-bottom: 30px;text-align: center;"><br><?php echo e($siteSetting->site_name); ?> Team</div>
                            </tr>
                        </table>
                        <br>
                    </td>
                </tr>
            </table>
            <!--[if mso]>
               </td>
            </tr>
         </table>
         <![endif]-->
        </td>
    </tr>
</table>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.email_template', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
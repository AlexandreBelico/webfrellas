<!-- ========== Modadl for Upload File =============== -->
<div class="modal fade" id="uploadfilemodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
     <form method="post" id="upload_form" enctype="multipart/form-data">
        {{ csrf_field() }}
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Upload File</h5>
      </div>
      
      <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <label for="file-input">
                    <img src="https://goo.gl/pB9rpQ"/ class="selectFile">
                </label>

                <input id="file-input" name="inputfile" type="file"/>
                <span id="file_name"></span>
                <span id="file_error"></span>
            </div>
            <input type="hidden" name="file_received_id" id="file_received_id">
            <input type="hidden" name="file_job_id" id="file_job_id">

            <div class="col-md-6 previewImagediv" style="display: none;">
                <img id="previewImage" height="100px" width="100px">
            </div>
        </div>
        <div class="row">
          <div class="col-lg-12 uploadmessages">
            <!-- <div class="alert" id="message" style="display: none"></div> -->
          </div>    
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary closemodal" data-dismiss="modal">Close</button>
        <input type="submit" class="btn btn-primary uploadbtn" disabled="disabled" value="Upload">
      </div>
      </form>   
    </div>
  </div>
</div>
<!-- ========== Modadl for Upload File =============== -->

<!-- ========== Modadl for Delete confrmation of attached file =============== -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="deleteconfirmation" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
     <form method="post" id="deleteconfirmation_form">
        {{ csrf_field() }}
      <div class="modal-header">
        <h5 class="modal-title" id="deleteconfirmation">Delete Conformation</h5>
      </div>
      
      <div class="modal-body"><p>Are you sure want to remove this file?</p></div>
      <input type="hidden" name="deletemessageId" id="deletemessageId">
      <input type="hidden" name="deletereceiver_id" id="deletereceiver_id">

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary closemodal" data-dismiss="modal">No</button>
        <input type="submit" class="btn btn-danger" value="Yes">
      </div>
      </form>   
    </div>
  </div>
</div>
<!-- ========== Modadl for Delete confrmation of attached file =============== -->

<?php
  $f = Auth::user()->first_name;
  $l = Auth::user()->last_name;
  $ans = $f.' '.$l;

?>
<div class="messages-wrapper">
    <ul class="messages" id="messages">

        @foreach($messages as $message)
      
          @if($message->from===Auth::id())
          <img src="{{asset('/user_images/'.Auth::user()->image)}}" class="can_img">
            <div class="can_name"><?php echo $ans; ?></div>
             
         @else
              <p class="can_name_merge">{{ $company_data[0]->name}}</p>
              @if($company_data[0]->logo != '')
                  <p><img src="{{asset('/company_logos/'.$company_data[0]->logo)}}" class="default_image_css">
                  </p>
              @else
                  <p><img src="{{asset('/company_logos/default_logo.png')}}" class="default_image_css">
                  </p>
              @endif
         @endif
          <li class="message clearfix">

            <div class="{{ ($message->from===Auth::id()) ? 'messagesent': 'messagereceived' }}" style="margin-top: 10px;">

              @if($message->message_type==1)
                <span class="fa fa-paperclip attached"></span>
                <p>
                  <a href="{{asset('/images/chats/'.$message->message)}}" download>{{$message->original_name}}</a>
                </p>
                <p class="pull-right downloadIcon"><a href="{{asset('/images/chats/'.$message->message)}}" download><span class="glyphicon glyphicon-download"></span></a></p>
               @elseif($message->message_type==2)
                <p>
                  <span class="fa fa-paperclip attached"></span>
                  <a href="{{asset('/images/chats/'.$message->message)}}" download><img src="{{asset('/images/chats/'.$message->message)}}" height="200px" width="220px"></a>
                </p>
              
                <p class="pull-right downloadIcon"><a href="{{asset('/images/chats/'.$message->message)}}" download><span class="glyphicon glyphicon-download"></span></a></p>
              
              @else
           
                <p class="textmessage">{{$message->message}}</p>

              @endif
                
                <p class="date">{{ date('d M Y, h:i a', strtotime($message->created_at)) }}</p>
            </div>
        </li>

        @endforeach

    </ul>
</div>

<div class="image-upload pull-right">
    <label>
        <span class="fa fa-paperclip paperclipIcon" onclick="DisableModalOutsideClick('uploadfilemodal');" data-toggle="modal" data-target="#uploadfilemodal"></span>
    </label>
</div>

 <div class="input-group input-text inputgrouptextBox">
      <!-- <span class="input-group-addon"><i class="fa fa-paperclip"></i></span> -->
      <input type="text" name="message" class="submit" placeholder="Type a messsage...">
 </div>

<div style="margin-top: 5px;">
    <label>
        <button type="button" class="btn btn-primary sendMessage">Send</button>
    </label>
</div>
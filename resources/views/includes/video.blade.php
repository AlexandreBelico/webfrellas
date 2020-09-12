@if(isset($video))
<div class="videowraper section">
    <div class="container">
		
		<div class="row">
			<div class="col-md-6">
			
				<div class="embed-responsive embed-responsive-16by9">
              <iframe src="{{$video->video_link}}" frameborder="0" allowfullscreen></iframe>
            </div>
			
			</div>
			<div class="col-md-6">
			
			 <!-- title start -->
        <div class="titleTop">
            <div class="subtitle">{{__('Here You Can See')}}</div>
            <h3>{{__('Watch Our')}} <span>{{__('Video')}}</span></h3>
        </div>
        <!-- title end -->
        <p>{{$video->video_text}}</p>
			
			</div>
		</div>
		
		
       
       </div>
</div>
@endif
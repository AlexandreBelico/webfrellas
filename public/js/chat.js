function DisableModalOutsideClick(modalId) {
	$('#'+modalId).modal({
	    backdrop: 'static',
	    keyboard: false,
	    show: false
	})
}

$( document ).ready(function() {
    var url  = window.location.href; 
    var last = url.substring(url.lastIndexOf("/") + 1, url.length);
    if(last!="my-chats"){
    	$.ajax({
              type: 'post',
              url: "/",
              data: { '_token': $('input[name=_token]').val(),
                      'receiver_id' : '12' },
              success: function(res) {
                  
              }
            });
    }
});
jQuery(document).ready(function($) {	
	//Custom From submistion
	$("#leadgen_form").submit(function(e) {
	    e.preventDefault();
	}).validate({
		rules:{
		  phone:{
			  minlength:9,
			  maxlength:10,
			  number: true
		  },
		},
	    submitHandler: function(form) {
	    	var ajax_url = FrontAjax.ajaxurl;
	    	var formdata = new FormData(document.getElementById('leadgen_form'));
			formdata.append('action', 'form_submit_with_ajax');
	        
	        $.ajax({
	            url: ajax_url,
	            type: 'POST',
	            data: formdata,
	            contentType:false,
		    	processData:false,
	            success: function(response) {
	                $(document.getElementById('leadgen_form')).trigger("reset");
	            }            
	        });
	    }
	});
});
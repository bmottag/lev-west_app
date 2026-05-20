$( document ).ready( function () {
	
	jQuery.validator.addMethod("campoTo", function(value, element, param) {
		var to = $('#to').val();
		if ( to != "" && value == "" ) {
			return false;
		}else{
			return true;
		}
	}, "This field is required.");
	
	jQuery.validator.addMethod("campoFrom", function(value, element, param) {
		var from = $('#from').val();
		if ( from != "" && value == "" ) {
			return false;
		}else{
			return true;
		}
	}, "This field is required.");
	
	$( "#form" ).validate( {
		rules: {
			jobName:		{ required: true },
			from: 			{ required: true, campoTo: "#to" },
			to: 			{ required: true, campoFrom: "#from" }
		},
		errorElement: "em",
		errorPlacement: function ( error, element ) {
			// Add the `help-block` class to the error element
			error.addClass( "help-block" );
			error.insertAfter( element );

		},
		highlight: function ( element, errorClass, validClass ) {
			$( element ).parents( ".col-sm-5" ).addClass( "has-error" ).removeClass( "has-success" );
		},
		unhighlight: function (element, errorClass, validClass) {
			$( element ).parents( ".col-sm-5" ).addClass( "has-success" ).removeClass( "has-error" );
		},
		submitHandler: function (form) {
			return true;
		}
	});
	
	$("#btnSubmit").click(function(){		
		if ($("#form").valid() == true){
			var form = document.getElementById('form');
			form.submit();	
		}else
		{
			//alert('Error.');
		}
	});

});
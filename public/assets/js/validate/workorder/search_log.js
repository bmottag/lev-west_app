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

	jQuery.validator.addMethod("unCampo", function(value, element, param) {
		var jobName = $('#jobName').val();
		var user = $('#user').val();
		var workOrderNumber = $('#workOrderNumber').val();
		var from = $('#from').val();
		var to = $('#to').val();
		if ( jobName == "" && from == "" && to == "" && workOrderNumber == "" && user == "") {
			return false;
		}else{
			return true;
		}
	}, "You must select at least one field.");

	$("#workOrderNumber").bloquearTexto().maxlength(6);

	$( "#form" ).validate( {
		rules: {
			jobName:	{ unCampo: true },
			user:	{ unCampo: true },
			workOrderNumber:	{ unCampo: true, number: true, maxlength: 6 },
			from: 			{ campoTo: "#to", unCampo: true },
			to: 			{ campoFrom: "#from", unCampo: true }
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
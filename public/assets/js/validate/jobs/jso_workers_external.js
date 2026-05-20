$( document ).ready( function () {

jQuery.validator.addMethod("campoNit", function(value, element, param) {
	var license = $('#license').val();
	if ( license == 1 && value == "" ) {
		return false;
	}else{
		return true;
	}
}, "This field is required.");


	
	$( "#formWorker" ).validate( {
		rules: {
			name:	 				{ required: true, minlength:2, maxlength:100 },
			phone_number:	 		{ required: true, minlength:2, maxlength:20 }
		},
		errorElement: "em",
		errorPlacement: function ( error, element ) {
			// Add the `help-block` class to the error element
			error.addClass( "help-block" );
			error.insertAfter( element );

		},
		highlight: function ( element, errorClass, validClass ) {
			$( element ).parents( ".col-sm-6" ).addClass( "has-error" ).removeClass( "has-success" );
		},
		unhighlight: function (element, errorClass, validClass) {
			$( element ).parents( ".col-sm-6" ).addClass( "has-success" ).removeClass( "has-error" );
		},
		submitHandler: function (form) {
			return true;
		}
	});
	
	$("#btnSubmitWorker").click(function(){		
		
		if ($("#formWorker").valid() == true){
		
				//Activa icono guardando
				$('#btnSubmitWorker').prop('disabled', true);
				$("#div_error").hide();
				$("#div_load").show();
			
				$.ajax({
					type: "POST",	
					url: base_url + "jobs/saveJSOWorker",
					data: $("#formWorker").serialize(),
					dataType: "json",
					cache: false,
					
					success: function(data){
                                            
						$("#div_load").hide();
						$('#btnSubmitWorker').prop('disabled', false);

						if (data.status === "error") {
							$("#div_error").show();
							$("#span_msj").html(data.message);
							return;
						}

						if (data.status === "success") {
							window.location.href = base_url + "jobs/jso_worker_view/" + data.idRecordExternal;
						} else {
							alert('Error. Reload the web page.');
							$("#div_error").show();
						}
					},
					error: function(xhr) {
						console.error(xhr.responseText);
						alert('Error. Reload the web page.');
						$("#div_load").hide();
						$("#div_error").show();
						$('#btnSubmitWorker').prop('disabled', false);
					}
					
				});	
		
		}
	});
});
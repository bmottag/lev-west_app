$( document ).ready( function () {
			
	$( "#formVerify" ).validate( {
		rules: {
			login: 		{required:	true },
			password: 	{required:	true }
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
				
	$("#btnSubmitVerification").click(function(){		
	
		if ($("#formVerify").valid() == true){
		
				//Activa icono guardando
				$('#btnSubmit').prop('disabled', true);
				$("#div_error_message").hide();
				$("#div_load").show();
			
				$.ajax({
					type: "POST",	
					url: base_url + "safety/save_signature_credentials",	
					data: $("#formVerify").serialize(),
					dataType: "json",
					cache: false,
					
					success: function(data){
                                            
						$("#div_load").hide();
						$('#btnSubmit').prop('disabled', false);

						if (data.status === "error") {
							$("#div_error_message").show();
							$("#span_msj_error").html(data.message);
							return;
						}

						if (data.status === "success") {
							window.location.href = base_url + data.path;
						} else {
							alert('Error. Reload the web page.');
							$("#div_error_message").show();
						}
					},
					error: function(xhr) {
						console.error(xhr.responseText);
						alert('Error. Reload the web page.');
						$("#div_load").hide();
						$("#div_error_message").show();
						$('#btnSubmit').prop('disabled', false);
					}
					
				});	
		
		}
	});

});
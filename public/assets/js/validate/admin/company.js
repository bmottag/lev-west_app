$( document ).ready( function () {

	$("#contact").bloquearNumeros().maxlength(50);		
	$("#movilNumber").bloquearTexto().maxlength(10);
	
	
	$( "#form" ).validate( {
		rules: {
			company:				{ required: true, minlength: 3, maxlength:50 },
			contact:				{ required: true, minlength: 3, maxlength:50 },
			movilNumber:			{ required: true },
			email:					{ required: true, email: true, maxlength:50 }
			
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
	
	$("#btnSubmit").click(function(){		
	
		if ($("#form").valid() == true){
		
				//Activa icono guardando
				$('#btnSubmit').prop('disabled', true);
				$("#div_error").hide();
				$("#div_load").show();
			
				$.ajax({
					type: "POST",	
					url: base_url + "admin/save_company",	
					data: $("#form").serialize(),
					dataType: "json",
					cache: false,
					
					success: function(data){
                                            
						$("#div_load").hide();
						$('#btnSubmit').prop('disabled', false);

						if (data.status === "error") {
							$("#div_error").show();
							return;
						}

						if (data.status === "success") {
							window.location.href = base_url + "admin/company";
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
						$('#btnSubmit').prop('disabled', false);
					}
					
				});	
		
		}
	});
});
$( document ).ready( function () {

	$("#firstName").bloquearNumeros().maxlength(25);
	$("#lastName").bloquearNumeros().maxlength(25);		
	$("#insuranceNumber").bloquearTexto().maxlength(10);
	$("#healthNumber").bloquearTexto().maxlength(10);
	$("#movilNumber").bloquearTexto().maxlength(10);

	$( "#form" ).validate( {
		rules: {
			inputPassword: 		{ required: true, minlength: 6, maxlength:15 },
			inputConfirm: 		{ required: true, minlength: 6, maxlength:15, equalTo: "#inputPassword" },
			firstName: 			{ required: true, minlength: 3, maxlength:25 },
			lastName: 			{ required: true, minlength: 3, maxlength:25 },
			user: 				{ required: true, minlength: 4, maxlength:12 },
			email: 				{ required: true, email: true, maxlength:60 },
			confirmEmail: 		{ required: true, email: true, equalTo: "#email" },
			birth: 				{ required: true, date: true },
			insuranceNumber:	{ required: true, number: true, minlength: 6, maxlength: 10 },
			movilNumber: 		{ required: true },
			address: 			{ minlength: 4, maxlength:200}
		},
		messages: {
			inputPassword: {
				required: "Please provide a password",
			},
			inputConfirm: {
				required: "Please provide a password",
				equalTo: "Please enter the same password as above"
			}
		},
		errorElement: "em",
		errorPlacement: function ( error, element ) {
			// Add the `help-block` class to the error element
			error.addClass( "help-block" );
			error.insertAfter( element );

		},
		highlight: function ( element, errorClass, validClass ) {
			$( element ).parents( ".col-sm-2" ).addClass( "has-error" ).removeClass( "has-success" );
		},
		unhighlight: function (element, errorClass, validClass) {
			$( element ).parents( ".col-sm-2" ).addClass( "has-success" ).removeClass( "has-error" );
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
					url: base_url + "external/save_employee",	
					data: $("#form").serialize(),
					dataType: "json",
					cache: false,
					
					success: function(data){
                                            
						$("#div_load").hide();
						$('#btnSubmit').prop('disabled', false);

						if (data.status === "error") {
							$("#div_error").show();
							$("#span_msj").html(data.message);
							return;
						}

						if (data.status === "success") {
							window.location.href = base_url + "external/new_employee/uiAqv828TZr";
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
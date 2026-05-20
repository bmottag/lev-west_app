$( document ).ready( function () {

	// Método para validar formato YYYY-MM-DD
	$.validator.addMethod("dateISO", function(value, element) {
		return this.optional(element) || /^\d{4}-\d{2}-\d{2}$/.test(value);
	}, "Please enter a valid date (YYYY-MM-DD).");
			
	$( "#form" ).validate( {
		rules: {
			type				: {	required	:	true },
			observation			: {	required	:	true },
            date: { 
                required: true,
                dateISO: true // Aquí aplicamos la regla
            }
		},
        messages: {
            date: {
                required: "Please select a date",
                dateISO: "Invalid format. Use YYYY-MM-DD"
            }
        },
		errorElement: "em",
		errorPlacement: function ( error, element ) {
			// Add the `help-block` class to the error element
			error.addClass( "help-block" );
			error.insertAfter( element );

		},
		highlight: function ( element, errorClass, validClass ) {
			$( element ).parents( ".col-sm-12" ).addClass( "has-error" ).removeClass( "has-success" );
		},
		unhighlight: function (element, errorClass, validClass) {
			$( element ).parents( ".col-sm-12" ).addClass( "has-success" ).removeClass( "has-error" );
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
					url: base_url + "dayoff/save_dayoff",	
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
							window.location.href = base_url + "dayoff";
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
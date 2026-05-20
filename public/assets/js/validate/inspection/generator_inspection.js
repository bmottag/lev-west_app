$( document ).ready( function () {
			
	$("#hours").bloquearTexto().maxlength(10);
	$( "#form" ).validate( {
		rules: {
			hours: 				{ number: true, minlength: 2, maxlength: 10 },
			belt:				{ required: true },
			fuelFilter:			{ required: true },
			oil: 				{ required: true },
			coolantLevel: 		{ required: true },
			coolantLeaks: 		{ required: true },
			turnSignal:			{ required: true },
			hazardLights:		{ required: true },			
			tailLights:			{ required: true },
			floodLights:		{ required: true },
			boom:				{ required: true },	
			gears:				{ required: true },
			gauges:				{ required: true },
			pulley:				{ required: true },
			electrical:			{ required: true },
			brackers:			{ required: true },	
			tires:				{ required: true },
			cleanExterior:		{ required: true },
			decals:				{ required: true }
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
		
				//Activa icono guardando
				$('#btnSubmit').prop('disabled', true);
				$("#div_error").hide();
				$("#div_load").show();
			
				$.ajax({
					type: "POST",	
					url: base_url + "inspection/save_generator_inspection",	
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
							window.location.href = base_url + "inspection/add_generator_inspection/" + data.idGeneratorInspection;
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
		else
		{
			alert('There are missing fields that have not been filled.');
			
		}					
	});

});
$( document ).ready( function () {
			
	$( "#form" ).validate( {
		rules: {
			manager:				{ required: true },
			operator:				{ required: true },
			supervisor:		{ required: true },
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
				$("#div_guardado").hide();
				$("#div_error").hide();
				$("#div_msj").hide();
				$("#div_cargando").show();

				$.ajax({
					type: "POST",	
					url: base_url + "jobs/save_personnel",	
					data: $("#form").serialize(),
					dataType: "json",
					contentType: "application/x-www-form-urlencoded;charset=UTF-8",
					cache: false,
					
					success: function(data){
                        $('#btnSubmit').prop('disabled', false);       
						
						if( data.result == "error" )
						{
							$("#div_cargando").hide();					
							$("#span_msj").html(data.mensaje);
							$("#div_msj").show();
							return false;
					
						} 

						if (data.status === "success") {         
							$("#div_cargando").hide();
							$("#div_guardado").show();

							window.location.href = base_url + "jobs/upload_excavation_personnel/" + data.idExcavation;
						}
						else
						{
							alert('Error. Reload the web page.');
							$("#div_cargando").hide();
							$("#div_error").show();
						}	
					},
					error: function(result) {
						alert('Error. Reload the web page.');
						$("#div_cargando").hide();	
						$("#div_error").show();
					}
					
		
				});	
		
		}else{
			alert("There are missing fields.");
		}	
	});

});
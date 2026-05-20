$( document ).ready( function () {

jQuery.validator.addMethod("fieldTestedExplanation", function(value, element, param) {
	var tested_daily = $('#tested_daily').val();
	if(tested_daily==1 && value == ""){
		return false;
	}else{
		return true;
	}
}, "This field is required.");

jQuery.validator.addMethod("fieldVentilationExplanation", function(value, element, param) {
	var ventilation = $('#ventilation').val();
	if(ventilation==1 && value == ""){
		return false;
	}else{
		return true;
	}
}, "This field is required.");
			
	$( "#form" ).validate( {
		rules: {
			project_location:			{ required: true },
			depth:						{ required: true, minlength: 1, maxlength:2 },
			width:						{ required: true, minlength: 1, maxlength:2 },
			length:						{ required: true, minlength: 1, maxlength:3 },
			confined_space:				{ required: true },
			tested_daily:				{ required: true },
			tested_daily_explanation:	{ fieldTestedExplanation: "#tested_daily" },
			ventilation:				{ required: true },
			ventilation_explanation:	{ fieldVentilationExplanation: "#ventilation" },
			soil_classification:		{ required: true },
			soil_type:					{ required: true }
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
					url: base_url + "jobs/save_excavation",	
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
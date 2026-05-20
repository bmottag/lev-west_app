$( document ).ready( function () {

jQuery.validator.addMethod("fieldOperated", function(value, element, param) {
	var standby = $('#standby').val();
	if(standby == 2 && value == ""){
		return false;
	}else{
		return true;
	}
}, "This field is required.");

jQuery.validator.addMethod("equipmentValidation", function(value, element, param) {
	var type = $('#type').val();
	if(type != 8 && value == ""){
		return false;
	}else{
		return true;
	}
}, "This field is required.");	

	$( "#formEquipment" ).validate( {
		rules: {
			type: 				{ required: true },
			truck: 				{ equipmentValidation: true },
			hour: 				{ required: true, number: true, maxlength:10 },
			quantity: 			{ number: true, maxlength:10 },
			operatedby:			{ fieldOperated: true },
			description: 		{ required: true }
		},
		errorElement: "em",
		errorPlacement: function ( error, element ) {
			// Add the `help-block` class to the error element
			error.addClass( "help-block" );
			error.insertAfter( element );

		},
		highlight: function ( element, errorClass, validClass ) {
			$( element ).parents( ".col-sm-12" ).addClass( "has-error" ).removeClass( "has-success" );
			$( element ).parents( ".col-sm-6" ).addClass( "has-error" ).removeClass( "has-success" );
		},
		unhighlight: function (element, errorClass, validClass) {
			$( element ).parents( ".col-sm-12" ).addClass( "has-success" ).removeClass( "has-error" );
			$( element ).parents( ".col-sm-6" ).addClass( "has-success" ).removeClass( "has-error" );
		},
		submitHandler: function (form) {
			return true;
		}
	});
	
	$("#btnSubmitEquipment").click(function(){		
		
		if ($("#formEquipment").valid() == true){
		
				//Activa icono guardando
				$('#btnSubmitEquipment').prop('disabled', true);
				$("#div_error").hide();
				$("#div_load").show();
			
				$.ajax({
					type: "POST",	
					url: base_url + "workorders/save/saveEquipment",
					data: $("#formEquipment").serialize(),
					dataType: "json",
					cache: false,
					
					success: function(data){
                                            
						$("#div_load").hide();
						$('#btnSubmitEquipment').prop('disabled', false);

						if (data.status === "error") {
							$("#div_error").show();
							$("#span_msj").html(data.message);
							return;
						}

						if (data.status === "success") {
							window.location.href = base_url + "workorders/" + data.controlador + "/" + data.idRecord;
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
						$('#btnSubmitEquipment').prop('disabled', false);
					}
					
				});	
		
		}
	});
});
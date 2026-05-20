$( document ).ready( function () {
			
	$( "#form" ).validate( {
		rules: {
			sites_watered:					{ required: true },
			being_swept:					{ required: true },
			dusty_covered:					{ required: true },
			speed_control:					{ required: true },
			noise_permit:					{ required: true },
			air_compressors:				{ required: true },
			noise_mitigation:				{ required: true },
			idle_plan:						{ required: true },
			garbage_bin:					{ required: true },
			disposed_periodically:			{ required: true },
			recycling_being:				{ required: true },
			spill_containment:				{ required: true },
			spillage_happen:				{ required: true },
			chemicals_stored:				{ required: true },
			absorbing_chemical:				{ required: true },
			spill_kits:						{ required: true },
			excessive_use:					{ required: true },
			materials_stored:				{ required: true },
			fire_extinguishers:				{ required: true },
			preventive_actions:				{ required: true }
		},
		errorElement: "em",
		errorPlacement: function ( error, element ) {
			// Add the `help-block` class to the error element
			error.addClass( "help-block" );
			error.insertAfter( element );

		},
		highlight: function ( element, errorClass, validClass ) {
			$( element ).parents( ".col-sm-3" ).addClass( "has-error" ).removeClass( "has-success" );
		},
		unhighlight: function (element, errorClass, validClass) {
			$( element ).parents( ".col-sm-3" ).addClass( "has-success" ).removeClass( "has-error" );
		},
		submitHandler: function (form) {
			return true;
		}
	});
			
	$("#btnSubmit").click(function(){	
		
		if ($("#form").valid() == true){
		
				//Activa icono guardando
				$('#btnSubmit').attr('disabled','-1');
				$("#div_error").css("display", "none");
				$("#div_load").css("display", "inline");
			
				$.ajax({
					type: "POST",	
					url: base_url + "more/save_environmental",	
					data: $("#form").serialize(),
					dataType: "json",
					contentType: "application/x-www-form-urlencoded;charset=UTF-8",
					cache: false,
					
					success: function(data){
                                            
						if( data.result == "error" )
						{
							$("#div_load").css("display", "none");
							$("#div_error").css("display", "inline");
							$("#span_msj").html(data.mensaje);
							$('#btnSubmit').removeAttr('disabled');							
							return false;
						} 

						if( data.result )//true
						{	                                                        
							$("#div_load").css("display", "none");
							$('#btnSubmit').removeAttr('disabled');

							var url = base_url + "more/add_environmental/" + data.idRecord + "/" + data.idEnvironmental;
							$(location).attr("href", url);
						}
						else
						{
							alert('Error. Reload the web page.');
							$("#div_load").css("display", "none");
							$("#div_error").css("display", "inline");
							$('#btnSubmit').removeAttr('disabled');
						}	
					},
					error: function(result) {
						alert('Error. Reload the web page.');
						$("#div_load").css("display", "none");
						$("#div_error").css("display", "inline");
						$('#btnSubmit').removeAttr('disabled');
					}
					
		
				});	
		
		}else{
			alert("There are missing fields.");
		}
	});
	

});
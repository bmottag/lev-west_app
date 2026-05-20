$( document ).ready( function () {

    $('#verification').change(function () {
        $('#verification option:selected').each(function () {
            var verification = $('#verification').val();
			$("#next_hours").css("display", "none");
			$("#next_date").css("display", "none");
			if(verification==1){
				$("#next_hours").css("display", "block");
				$('#next_hours_maintenance').val("");
			}else if (verification==2){
				$("#next_date").css("display", "block");
				$('#next_date_maintenance').val("");
			}
        });
    });

	jQuery.validator.addMethod("fieldSpecify", function(value, element, param) {
		var verification = $('#verification').val();
		var hours = $('#next_hours_maintenance').val();
		var date = $('#next_date_maintenance').val();
		if(verification==1 && (hours == "" | hours== 0)){
			return false;
		}else if(verification==2 && (date == "" || date == "0000-00-00")){
			return false;
		}else{
			return true;
		}
	}, "This field is required.");
	
	$( "#formMaintenance" ).validate( {
		rules: {
			maintenance_type:				{ required: true },
			description:					{ required: true},
			next_hours_maintenance:			{ fieldSpecify: true },
			next_date_maintenance:			{ fieldSpecify: true }
		},
		errorElement: "em",
		errorPlacement: function ( error, element ) {
			// Add the `help-block` class to the error element
			error.addClass( "help-block" );
			error.insertAfter( element );

		},
		highlight: function ( element, errorClass, validClass ) {
			$( element ).parents( ".col-sm-6" ).addClass( "has-error" ).removeClass( "has-success" );
			$( element ).parents( ".col-sm-12" ).addClass( "has-error" ).removeClass( "has-success" );
		},
		unhighlight: function (element, errorClass, validClass) {
			$( element ).parents( ".col-sm-6" ).addClass( "has-success" ).removeClass( "has-error" );
			$( element ).parents( ".col-sm-12" ).addClass( "has-success" ).removeClass( "has-error" );
		},
		submitHandler: function (form) {
			return true;
		}
	});
	
	$("#btnSubmitMaintenance").click(function(){		
	
		if ($("#formMaintenance").valid() == true){
		
				//Activa icono guardando
				$('#btnSubmitMaintenance').attr('disabled','-1');
				$("#div_error").css("display", "none");
				$("#div_load").css("display", "inline");
			
				$.ajax({
					type: "POST",	
					url: base_url + "serviceorder/save_preventive_maintenance",	
					data: $("#formMaintenance").serialize(),
					dataType: "json",
					contentType: "application/x-www-form-urlencoded;charset=UTF-8",
					cache: false,
					
					success: function(data){
                                            
						if( data.result == "error" )
						{
							$("#div_load").css("display", "none");
							$('#btnSubmitMaintenance').removeAttr('disabled');							
							return false;
						} 

						if( data.result )//true
						{	                                                        
							$("#div_load").css("display", "none");
							$('#btnSubmitMaintenance').removeAttr('disabled');
							$('#modalMaintenance').modal('hide');
							loadEquipmentDetail( data.idEquipment, 'tab_preventive_maintenance' );
						}
						else
						{
							alert('Error. Reload the web page.');
							$("#div_load").css("display", "none");
							$("#div_error").css("display", "inline");
							$('#btnSubmitMaintenance').removeAttr('disabled');
						}	
					},
					error: function(result) {
						alert('Error. Reload the web page.');
						$("#div_load").css("display", "none");
						$("#div_error").css("display", "inline");
						$('#btnSubmitMaintenance').removeAttr('disabled');
					}
					
		
				});	
		
		}//if			
	});
});
$( document ).ready( function () {

    $('#status').change(function () {
        $('#status option:selected').each(function () {
            var status = $('#status').val();
			var verification = $('#hddVerificationBy').val();
			$("#next_hours").css("display", "none");
			$("#next_date").css("display", "none");
			$('#comments').val("");
			$('#next_hours_maintenance').val("");
			$('#next_date_maintenance').val("");
			if(status=="closed_so"){
				$("#div_comments").css("display", "block");
				if(verification==1){
					$("#next_hours").css("display", "block");
				}else if (verification==2){
					$("#next_date").css("display", "block");
				}
			}else{
				$("#div_comments").css("display", "none");
			}
        });
    });

	jQuery.validator.addMethod("fieldSpecify", function(value, element, param) {
		var status = $(param).val();
		if(status=="closed_so" && (value=="" || value== 0)){
			return false;
		}else{
			return true;
		}
	}, "This field is required.");

	jQuery.validator.addMethod("fieldNext", function(value, element, param) {
		var verification = $('#hddVerificationBy').val();
		var hours = $('#next_hours_maintenance').val();
		var date = $('#next_date_maintenance').val();
		var status = $('#status').val();
		if(status=="closed_so"){
			if(verification==1 && (hours == "" | hours== 0)){
				return false;
			}else if(verification==2 && (date == "" || date == "0000-00-00")){
				return false;
			}else{
				return true;
			}
		}else{
			return true;
		}	
	}, "This field is required.");

	$( "#form" ).validate( {
		rules: {
			comments:						{ fieldSpecify: "#status" },
			next_hours_maintenance:			{ fieldNext: true },
			next_date_maintenance:			{ fieldNext: true }
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
	
	$("#btnSubmit").click(function(){		
	
		if ($("#form").valid() == true){
		
				//Activa icono guardando
				$('#btnSubmit').attr('disabled','-1');
				$("#div_error").css("display", "none");
				$("#div_load").css("display", "inline");
			
				$.ajax({
					type: "POST",	
					url: base_url + "serviceorder/save_service_order",	
					data: $("#form").serialize(),
					dataType: "json",
					contentType: "application/x-www-form-urlencoded;charset=UTF-8",
					cache: false,
					
					success: function(data){
                                            
						if( data.result == "error" )
						{
							$("#div_load").css("display", "none");
							$('#btnSubmit').removeAttr('disabled');							
							return false;
						} 

						if( data.result )//true
						{	                                                        
							$("#div_load").css("display", "none");
							$('#btnSubmit').removeAttr('disabled');
							$('#modalServiceOrder').modal('hide');
							loadEquipmentDetail( data.idEquipment, 'tab_service_order_detail', data.idServiceOrder );
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
		}//if	
	});
});
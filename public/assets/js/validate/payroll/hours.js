$( document ).ready( function () {
	
jQuery.validator.addMethod("validacion", function(value, element, param) {
	
	var start_date = $('#start_date').val();
	var start_hour = $('#start_hour').val();
	var start_min = $('#start_min').val();
	var finish_hour = $('#finish_hour').val();
	var finish_min = $('#finish_min').val();
	
	var hddfechaInicio = $('#hddfechaInicio').val();
	var hddhoraInicio = $('#hddhoraInicio').val();
	var hddminutosInicio = $('#hddminutosInicio').val();
	var hddfechaFin = $('#hddfechaFin').val();
	var hddhoraFin = $('#hddhoraFin').val();
	var hddminutosFin = $('#hddminutosFin').val();
	
	if (hddfechaInicio == start_date &&  hddhoraInicio == start_hour  &&  hddminutosInicio == start_min &&  hddhoraFin == finish_hour &&  hddminutosFin == finish_min) {
		return false;
	}else{
		return true;
	}
}, "One of the field have to be different.");
	
	$( "#formWorker" ).validate( {
		rules: {
			start_date:	 			{ required: true },
			start_hour:	 			{ required: true },
			start_min:	 			{ required: true },
			finish_hour:	 		{ required: true },
			finish_min:	 			{ required: true },
			observation:	 		{ required: true, validacion:true }
		},
		errorElement: "em",
		errorPlacement: function ( error, element ) {
			// Add the `help-block` class to the error element
			error.addClass( "help-block" );
			error.insertAfter( element );

		},
		highlight: function ( element, errorClass, validClass ) {
			$( element ).parents( ".col-sm-4" ).addClass( "has-error" ).removeClass( "has-success" );
			$( element ).parents( ".col-sm-12" ).addClass( "has-error" ).removeClass( "has-success" );
		},
		unhighlight: function (element, errorClass, validClass) {
			$( element ).parents( ".col-sm-4" ).addClass( "has-success" ).removeClass( "has-error" );
			$( element ).parents( ".col-sm-12" ).addClass( "has-success" ).removeClass( "has-error" );
		},
		submitHandler: function (form) {
			return true;
		}
	});
	
	$("#btnSubmit").click(function(){		
		
		if ($("#formWorker").valid() == true){
		
				//Activa icono guardando
				$('#btnSubmit').prop('disabled', true);
				$("#div_error").hide();
				$("#div_load").show();
			
				$.ajax({
					type: "POST",	
					url: base_url + "payroll/savePayrollHour",	
					data: $("#formWorker").serialize(),
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
							window.location.href = base_url + "dashboard/info_by_day/payrollInfo/" + data.datePayroll;
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
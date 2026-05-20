$( document ).ready( function () {

	$("#hours").bloquearTexto().maxlength(10);
	$( "#form" ).validate( {
		rules: {
			hours: 			{ number: true, minlength: 2, maxlength: 10 },
			belt:			{ required: true },
			oil: 			{ required: true },
			coolantLevel: 	{ required: true },
			coolantLeaks:	{ required: true },
			workingLamps:	{ required: true },
			beaconLights:	{ required: true },
			heater:			{ required: true },
			operatorSeat:	{ required: true },
			gauges:			{ required: true },
			horn:			{ required: true },
			seatbelt:		{ required: true },
			cleanInterior:	{ required: true },
			windows:		{ required: true },
			cleanExterior:	{ required: true },
			wipers:			{ required: true },
			backupBeeper:	{ required: true },
			door:			{ required: true },
			decals:			{ required: true },
			boom:			{ required: true },
			tableExcavator:	{ required: true },
			bucketPins:		{ required: true },
			bladePins:		{ required: true },
			ripper:			{ required: true },
			frontAxle:		{ required: true },
			rearAxle:		{ required: true },
			tableDozer:		{ required: true },
			pivinPoints:	{ required: true },
			bucketPinsSkit:	{ required: true },
			sideArms:		{ required: true },
			bucket:			{ required: true },
			cutting:		{ required: true },
			blades:			{ required: true },
			tracks:			{ required: true },
			rubberTrucks:	{ required: true },
			rollers:		{ required: true },
			thamper:		{ required: true },
			drill:			{ required: true },
			fire:			{ required: true },
			aid:			{ required: true },
			spillKit:		{ required: true },
			tire:			{ required: true },
			turn:			{ required: true },
			rims:			{ required: true },
			brake:			{ required: true },
			transmission:	{ required: true },
			hydrolic: 	    { required: true },
			def:		    { required: true }
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
					url: base_url + "inspection/save_heavy_inspection",
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
							window.location.href = base_url + "inspection/add_heavy_inspection/" + data.idHeavyInspection;
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
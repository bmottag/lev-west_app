$( document ).ready( function () {
			
	$("#hours").bloquearTexto().maxlength(10);
	$("#hours2").bloquearTexto().maxlength(10);
	
	$( "#form" ).validate( {
		rules: {
			hours: 				{ number: true, minlength: 2, maxlength: 10 },
			hours2: 				{ number: true, minlength: 2, maxlength: 10 },
			belt:				{ required: true },
			powerSteering:		{ required: true },
			oil:				{ required: true },
			coolantLevel:		{ required: true },
			coolantLeaks:		{ required: true },
			hydraulic:			{ required: true },
			beltSweeper:		{ required: true },
			oilSweeper:			{ required: true },
			coolantLevelSweeper:{ required: true },
			coolantLeaksSweeper:{ required: true },
			headLamps:			{ required: true },
			hazardLights:		{ required: true },
			clearanceLights:	{ required: true },
			tailLights:			{ required: true },
			workLights:			{ required: true },
			turnSignals:		{ required: true },
			beaconLights:		{ required: true },
			tires:				{ required: true },
			windows:			{ required: true },
			cleanExterior:		{ required: true },
			wipers:				{ required: true },
			backupBeeper:		{ required: true },
			door:				{ required: true },
			decals:				{ required: true },
			SteringWheels:		{ required: true },
			drives:				{ required: true },
			frontDrive:			{ required: true },
			elevator:			{ required: true },
			rotor:				{ required: true },
			mixtureBox:			{ required: true },
			lfRotor:			{ required: true },
			elevatorSweeper:	{ required: true },
			mixtureContainer:	{ required: true },
			broom:				{ required: true },
			rightBroom:			{ required: true },
			leftBroom:			{ required: true },
			sprinkerls:			{ required: true },
			waterTank:			{ required: true },
			hose:				{ required: true },
			cam:				{ required: true },
			brake:				{ required: true },
			emergencyBrake:		{ required: true },
			gauges:				{ required: true },
			horn:				{ required: true },
			seatbelt:			{ required: true },
			seat:				{ required: true },
			insurance:			{ required: true },
			registration:		{ required: true },
			cleanInterior:		{ required: true },
			fire:				{ required: true },
			aid:				{ required: true },
			emergencyKit:		{ required: true },
			spillKit:			{ required: true }
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
					url: base_url + "inspection/save_sweeper_inspection",	
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
							window.location.href = base_url + "inspection/add_sweeper_inspection/" + data.idSweeperInspection;
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
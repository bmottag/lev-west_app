$( document ).ready( function () {

	jQuery.validator.addMethod("validation", function(value, element, param) {
		var login_before = $('#login_before').val();
		var id_name = $('#id_name').val();
		var new_name = $('#new_name').val();
		var new_phone_number = $('#new_phone_number').val();
		if ( login_before == 1 && id_name == "" ) {
			return false;
		}else{
			return true;
		}
	}, "This field is required.");

	jQuery.validator.addMethod("validation2", function(value, element, param) {
		var login_before = $('#login_before').val();
		var id_name = $('#id_name').val();
		var new_name = $('#new_name').val();
		var new_phone_number = $('#new_phone_number').val();
		if( login_before == 2 && new_name == "") {
			return false;
		}else{
			return true;
		}
	}, "This field is required.");

	jQuery.validator.addMethod("validation3", function(value, element, param) {
		var login_before = $('#login_before').val();
		var id_name = $('#id_name').val();
		var new_name = $('#new_name').val();
		var new_phone_number = $('#new_phone_number').val();
		if( login_before == 2 && new_phone_number == "") {
			return false;
		}else{
			return true;
		}
	}, "This field is required.");
			
	$("#hours").bloquearTexto().maxlength(10);
	$( "#form" ).validate( {
		rules: {
			login_before: 			{ required: true },
			id_name: 				{ validation: true},
			new_name: 				{ validation2: true},
			new_phone_number: 		{ validation3: true}
		},
		errorElement: "em",
		errorPlacement: function ( error, element ) {
			// Add the `help-block` class to the error element
			error.addClass( "help-block" );
			error.insertAfter( element );

		},
		highlight: function ( element, errorClass, validClass ) {
			$( element ).parents( ".col-sm-6" ).addClass( "has-error" ).removeClass( "has-success" );
		},
		unhighlight: function (element, errorClass, validClass) {
			$( element ).parents( ".col-sm-6" ).addClass( "has-success" ).removeClass( "has-error" );
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
					url: base_url + "external/save_checkin",	
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
							window.location.href = base_url + "external/checkin/" + data.idCheckin;
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
			alert('Faltan campos por diligenciar.');
			
		}

	});

});
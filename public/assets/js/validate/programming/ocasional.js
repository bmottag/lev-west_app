$(document).ready(function () {
	$('#divHauling').hide();
	$( "#formOcasional" ).validate( {
		rules: {
			company: 			{ required: true },
			equipment: 			{ required: true },
			quantity: 			{ number: true, maxlength:10 },
			unit:	 			{ minlength:2 , maxlength:20 },
			hour: 				{ number: true, maxlength:10 }
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

	$("#btnSubmitOcasional").click(function(){

		if ($("#formOcasional").valid() == true){
		
				//Activa icono guardando
				$('#btnSubmitOcasional').prop('disabled', true);
				$("#div_error").hide();
				$("#div_load").show();
			
				$.ajax({
					type: "POST",	
					url: base_url + "programming/save_ocasional",
					data: $("#formOcasional").serialize(),
					dataType: "json",
					cache: false,
					
					success: function(data){
                                            
						$("#div_load").hide();
						$('#btnSubmitOcasional').prop('disabled', false);

						if (data.status === "error") {
							$("#div_error").show();
							$("#span_msj").html(data.message);
							return;
						}

						if (data.status === "success") {
							window.location.href = base_url + "programming/" + data.controller + "/" + data.path;
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
						$('#btnSubmitOcasional').prop('disabled', false);
					}
					
				});	
		
		}
	});

	$('#companySelect').change(function () {
		var company = $(this).find('option:selected');
		var companyValue = company.val();
		var dataHauling = company.data('hauling');

		if (dataHauling == 1) {
			$('#divHauling').show();
			$('#quantity').attr('required', true);
		} else {
			$('#divHauling').hide();
			$('#quantity').removeAttr('required');
		}
    });
});
$(document).ready(function () {
	var state = $("#state").val();

	if (state && state != 1) {
		$("form :input").prop('disabled', true);
		$("#btnSubmit").prop('disabled', true);
		$("#btnEmail").prop('disabled', true);
		$('.class_disabled').attr('disabled', 'true');
		$('.class_disabled').removeAttr("href");
	}

	$( "#form" ).validate( {
		rules: {
			company:			{ required: true },
			truckType: 			{ required: true },
			materialType: 		{ required: true },
			fromSite:	 		{ required: true },
			toSite:				{ required: true },
			hourIn:				{ required: true },
			hourOut:			{ required: true },
			payment:			{ required: true },
			plate: { minlength: 3, maxlength: 15 },
			list_work_order: {
				required: function() {
					return $("#id_work_order").val() == "2";
				}
			}
		},
		errorElement: "em",
		errorPlacement: function (error, element) {
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
					url: base_url + "hauling/save_hauling",	
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
							window.location.href = base_url + "hauling/add_hauling/" + data.idHauling;
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
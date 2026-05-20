$( document ).ready( function () {

    $('#company').change(function () {
        $('#company option:selected').each(function () {
            var company = $('#company').val();

            if ( company == '') {
                $("#div_subcontractor_detail").css("display", "block");
            }else{
				$("#div_subcontractor_detail").css("display", "none");
			}
        });
    });


	jQuery.validator.addMethod("fieldSpecify", function(value, element, param) {
		var company = $('#company').val();
		var subcontractor_name = $('#subcontractor_name').val();
		if(company=="" && subcontractor_name == ""){
			return false;
		}else{
			return true;
		}
	}, "Select the Subcontractor or Add a new one.");

	$("#subcontractor_contact").bloquearNumeros().maxlength(50);		
	$("#subcontractor_mobile_number").bloquearTexto().maxlength(10);
	$("#invoice_number").bloquearTexto().maxlength(10);
			
	$( "#form" ).validate( {
		rules: {
			company:						{ fieldSpecify: true },
			subcontractor_name: 			{ fieldSpecify: true, minlength: 3, maxlength:60 },
			invoice_number:				 	{ required: true },
			amount: 						{ required: true, number: true, maxlength:6 }
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
				$('#btnSubmit').attr('disabled','-1');
				$("#div_error").css("display", "none");
				$("#div_load").css("display", "inline");

				var formData = new FormData($("#form")[0]);

				$.ajax({
					type: "POST",	
					url: base_url + "workorders/save_subcontractor_invoice",	
					data: formData, 
					dataType: "json",
					contentType: false,
					processData: false,
					cache: false,
					
					success: function(data){             
						if (data.status == "error") {
							$("#div_load").css("display", "none");
							$("#div_error").css("display", "inline");
							$("#span_msj").html(data.message);
							$('#btnSubmit').removeAttr('disabled');
							return;
						}
										
						if( data.status === "success" )//true
						{	                                                        
							$("#div_load").css("display", "none");
							$('#btnSubmit').removeAttr('disabled');

							var url = base_url + "workorders/subcontractor_invoice";
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
		}//if			
	});
	
});
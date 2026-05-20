$( document ).ready( function () {
	
	$("#re_oxygen").bloquearTexto().maxlength(10);
	$("#re_explosive_limit").bloquearTexto().maxlength(10);
			
	$( "#form" ).validate( {
		rules: {
			completed_flha:				{ required: true },
			location:				{ required: true },
			purpose:				{ required: true },
			start_date:	 			{ required: true },
			start_hour:	 			{ required: true },
			start_min:	 			{ required: true },
			finish_date:			{ required: true },
			finish_hour:	 		{ required: true },
			finish_min:	 			{ required: true },
			toxic_atmosphere_cond: 			{ maxlength:200},
			instruments_used: 				{ maxlength:200},
			re_toxic_atmosphere: 			{ maxlength:200},
			re_instruments_used: 			{ maxlength:200},
			authorization:	 			{ required: true },
			cancellation:	 			{ required: true }
		},
		errorElement: "em",
		errorPlacement: function ( error, element ) {
			// Add the `help-block` class to the error element
			error.addClass( "help-block" );
			error.insertAfter( element );

		},
		highlight: function ( element, errorClass, validClass ) {
			$( element ).parents( ".col-sm-12" ).addClass( "has-error" ).removeClass( "has-success" );
		},
		unhighlight: function (element, errorClass, validClass) {
			$( element ).parents( ".col-sm-12" ).addClass( "has-success" ).removeClass( "has-error" );
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
					url: base_url + "more/save_confined",
					data: $("#form").serialize(),
					dataType: "json",
					cache: false,
					
					success: function(data){
                                            
						$("#div_load").hide();
						$('#btnSubmit').prop('disabled', false);

						if (data.status === "error") {
							$("#div_error").show();
							return;
						}

						if (data.status === "success") {
							window.location.href = base_url + "more/add_confined/" + data.idRecord + "/" + data.idConfined;
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
	
	$(".btn-danger").click(function () {	
			var oID = $(this).attr("id");
			
			//Activa icono guardando
			if(window.confirm('Are you sure you want to delete this record?'))
			{
					$(".btn-danger").attr('disabled','-1');
					$.ajax ({
						type: 'POST',
						url: base_url + 'jobs/deleteRecordNewHazard',
						data: {'identificador': oID},
						cache: false,
						success: function(data){
												
							if( data.result == "error" )
							{
								alert(data.mensaje);
								$(".btn-danger").removeAttr('disabled');							
								return false;
							} 
											
							if( data.result )//true
							{	                                                        
								$(".btn-danger").removeAttr('disabled');

								var url = base_url + "jobs/add_tool_box/" + data.idRecord;
								$(location).attr("href", url);
							}
							else
							{
								alert('Error. Reload the web page.');
								$(".btn-danger").removeAttr('disabled');
							}	
						},
						error: function(result) {
							alert('Error. Reload the web page.');
							$(".btn-danger").removeAttr('disabled');
						}

					});
			}
	});

});
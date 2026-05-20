$( document ).ready( function () {
	
	$( "#form" ).validate( {
		rules: {
			description:			{ required: true },
			unit: 					{ required: true },
			quantity: 				{ required: true },
			unit_price: 			{ required: true }
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
	
	$("#btnSave").click(function(){		
		
		if ($("#form").valid() == true){
		
				$("#chapter").prop("disabled", false);
				$("#chapter_number").prop("disabled", false);
				//Activa icono guardando
				$('#btnSubmit').prop('disabled', true);
				$("#div_error").hide();
				$("#div_load").show();
			
				$.ajax({
					type: "POST",	
					url: base_url + "jobs/save_job_detail",	
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
							window.location.href = base_url + "jobs/job_detail/" + data.idRecord;
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

	$(".btn-delete-job-detail").click(function () {	
		var oID = $(this).attr("id");
		
		//Activa icono guardando
		if(window.confirm('Are you sure you want to delete this record?'))
		{
				$(".btn-delete-job-detail").attr('disabled','-1');
				$.ajax ({
					type: 'POST',
					url: base_url + 'jobs/deleteRecordJobDetail',
					data: {'identificador': oID},
					cache: false,
					success: function(data){
											
						if( data.result == "error" )
						{
							alert(data.mensaje);
							$(".btn-delete-job-detail").removeAttr('disabled');							
							return false;
						} 
										
						if( data.result )//true
						{	                                                        
							$(".btn-delete-job-detail").removeAttr('disabled');

							var url = base_url + "jobs/job_detail/" + data.idRecord;
							$(location).attr("href", url);
						}
						else
						{
							alert('Error. Reload the web page.');
							$(".btn-delete-job-detail").removeAttr('disabled');
						}	
					},
					error: function(result) {
						alert('Error. Reload the web page.');
						$(".btn-delete-job-detail").removeAttr('disabled');
					}

				});
		}
	});
});
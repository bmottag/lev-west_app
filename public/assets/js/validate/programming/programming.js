$(document).ready(function () {

	$('.js-example-basic-single').select2();
	$(".date_range").css("display", "none");

	jQuery.validator.addMethod("fieldValidationDate", function(value, element, param) {
		var flag = $('#flag_date').val();
		if(flag == 1 && value == ""){
			return false;
		}else{
			return true;
		}
	}, "This field is required.");

	jQuery.validator.addMethod("fieldValidationPeriod", function(value, element, param) {
		var flag = $('#flag_date').val();
		if(flag == 2 && value == ""){
			return false;
		}else{
			return true;
		}
	}, "This field is required.");

	$('#jobName').change(function () {
		var planning = $('#jobName option:selected').data('planning');

		if (planning == 1) {
			$(".date_range").css("display", "block");
			$('#job_planning').val(1);
		}else{
			$(".date_range").css("display", "none");
			$('#job_planning').val(2);
			$('#flag_date').val(1);
			$('.period-fields').css("display", "none");
			$('.date-fields').css("display", "block");
		}
    });

	$('#flag_date').change(function () {
        $('#flag_date option:selected').each(function () {

			var flag = $('#flag_date').val();
			if (flag == 1) {
				$(".period-fields").css("display", "none");
				$(".date-fields").css("display", "block");

				$('#from').val("");
				$('#to').val("");
			}else{
				$(".period-fields").css("display", "block");
				$(".date-fields").css("display", "none");
				$('#date').val("");
			}
        });
    });

	$( "#form" ).validate( {
		rules: {
			date:					{ fieldValidationDate: true },
			from:					{ fieldValidationPeriod: true },
			to:						{ fieldValidationPeriod: true },
			apply_for:				{ fieldValidationPeriod: true },
			jobName: 				{ required: true },
			observation: 			{ required: true }
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

	$(".btn-delete-programming").click(function () {
			var oID = $(this).attr("id");

			//Activa icono guardando
			if(window.confirm('Are you sure you want to delete the Planning ?'))
			{
					$(".btn-delete-programming").attr('disabled','-1');
					$.ajax ({
						type: 'POST',
						url: base_url + 'programming/delete_programming',
						data: {'identificador': oID},
						cache: false,
						success: function(data){

							if( data.status === "error" )
							{
								alert(data.mensaje);
								$(".btn-delete-programming").removeAttr('disabled');
								return false;
							}

							if (data.status === "success")
							{
								$(".btn-delete-programming").removeAttr('disabled');
								window.location.href = base_url + "programming/index/" + data.path;
							}
							else
							{
								alert('Error. Reload the web page.');
								$(".btn-delete-programming").removeAttr('disabled');
							}
						},
						error: function(result) {
							alert('Error. Reload the web page.');
							$(".btn-delete-programmingr").removeAttr('disabled');
						}

					});
			}
	});

	$("#btnSubmit").click(function(){

		if ($("#form").valid() == true){
		
				$("#jobName").prop("disabled", false);
				//Activa icono guardando
				$('#btnSubmit').prop('disabled', true);
				$("#div_error").hide();
				$("#div_load").show();
			
				$.ajax({
					type: "POST",	
					url: base_url + "programming/save_programming",
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
							window.location.href = base_url + "programming/index/" + data.path;
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

	$("#btnSubmitClone").click(function(){

		if ($("#clonePlanning").valid() == true){
		
				//Activa icono guardando
				$('#bbtnSubmitClone').prop('disabled', true);
				$("#loader").addClass("loader");
			
				$.ajax({
					type: "POST",	
					url: base_url + "programming/clone_planning",
					data: $("#clonePlanning").serialize(),
					dataType: "json",
					cache: false,
					
					success: function(data){
                                            
						$("#loader").removeClass("loader");

						if (data.status === "error") {
							$("#div_error").show();
							$('#btnSubmitClone').removeAttr('disabled');
							return;
						}

						if (data.status === "success") {
							$("#div_guardado").css("display", "block");
							$('#btnSubmitClone').removeAttr('disabled');
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
						$('#bbtnSubmitClone').prop('disabled', false);
					}
					
				});	
		
		}
	});

});
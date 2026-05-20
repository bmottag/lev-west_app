$( document ).ready( function () {

	$( "#form" ).validate( {
		rules: {
			date:        { required: true },
			jobName:     { required: true },
			foreman:     { minlength: 6, maxlength: 70 },
			email:       { email: true, maxlength: 70 },
			observation: { required: true }
		},
		errorElement: "em",
		errorPlacement: function ( error, element ) {
			error.addClass( "help-block" );
			error.insertAfter( element );
		},
		highlight: function ( element, errorClass, validClass ) {
			$( element ).parents( ".col-sm-5" ).addClass( "has-error" ).removeClass( "has-success" );
		},
		unhighlight: function ( element, errorClass, validClass ) {
			$( element ).parents( ".col-sm-5" ).addClass( "has-success" ).removeClass( "has-error" );
		},
		submitHandler: function ( form ) {
			return true;
		}
	});

	$( "#formState" ).validate( {
		rules: {
			state:       { required: true },
			information: { required: true }
		},
		errorElement: "em",
		errorPlacement: function ( error, element ) {
			error.addClass( "help-block" );
			error.insertAfter( element );
		},
		highlight: function ( element, errorClass, validClass ) {
			$( element ).parents( ".col-sm-8" ).addClass( "has-error" ).removeClass( "has-success" );
		},
		unhighlight: function ( element, errorClass, validClass ) {
			$( element ).parents( ".col-sm-8" ).addClass( "has-success" ).removeClass( "has-error" );
		},
		submitHandler: function ( form ) {
			return true;
		}
	});

	$("#btnClose").click(function () {
		if ( window.confirm('Are you sure you want to close this Work Order Report?') ) {
			$.ajax({
				type: "POST",
				url: base_url + "workorders/update_workorder",
				data: $("#form").serialize(),
				dataType: "json",
				contentType: "application/x-www-form-urlencoded;charset=UTF-8",
				cache: false,
				success: function ( data ) {
					if ( data.status == "error" ) {
						$("#div_cargando").css("display", "none");
						$('#btnSubmit').prop('disabled', false);
						$("#span_msj").html(data.mensaje);
						$("#div_msj").css("display", "inline");
						return false;
					}

					if ( data.status == "success" ) {
						$("#div_cargando").css("display", "none");
						$("#div_guardado").css("display", "inline");
						$('#btnSubmit').prop('disabled', false);

						const url = base_url + "workorders/add_workorder/" + data.idWorkorder;
						window.location.href = url;
					} else {
						alert('Error. Reload the web page.');
						$("#div_cargando").css("display", "none");
						$("#div_error").css("display", "inline");
						$('#btnSubmit').prop('disabled', false);
					}
				},
				error: function ( result ) {
					alert('Error. Reload the web page.');
					$("#div_cargando").css("display", "none");
					$("#div_error").css("display", "inline");
					$('#btnSubmit').prop('disabled', false);
				}
			});
		}
	});

	$('#jobName').change(function () {
		const idJob = $('#jobName').val();
		if (idJob && idJob > 0){
			$.ajax({
				type: "POST",
				url: base_url + "workorders/foremanInfo",
				data: { 'idJob': idJob },
				dataType: "json",
				contentType: "application/x-www-form-urlencoded;charset=UTF-8",
				cache: false,
				success: function ( data ) {

					if ( data.status === "success" ) {
						$("#company").val(data.company_id);
						$("#companyName").val(data.company_name);
						$("#foreman").val(data.foreman_name);
						$("#movilNumber").val(data.foreman_movil);
						$("#email").val(data.foreman_email);
					}
				}
			});
		}
	});

	$("#btnSubmit").click(function () {
		if ( $("#form").valid() == true ) {
			$('#btnSubmit').prop('disabled', true);
			$("#div_guardado").css("display", "none");
			$("#div_error").css("display", "none");
			$("#div_msj").css("display", "none");
			$("#div_cargando").css("display", "inline");

			$.ajax({
				type: "POST",
				url: base_url + "workorders/save_workorder",
				data: $("#form").serialize(),
				dataType: "json",
				contentType: "application/x-www-form-urlencoded;charset=UTF-8",
				cache: false,
				success: function ( data ) {
					if ( data.status == "error" ) {
						$("#div_cargando").css("display", "none");
						$('#btnSubmit').prop('disabled', false);
						$("#span_msj").html(data.mensaje);
						$("#div_msj").css("display", "inline");
						return false;
					}

					if ( data.status == "success" ) {
						$("#div_cargando").css("display", "none");
						$("#div_guardado").css("display", "inline");
						$('#btnSubmit').prop('disabled', false);

						const url = base_url + "workorders/add_workorder/" + data.idWorkorder;
						window.location.href = url;
					} else {
						alert('Error. Reload the web page.');
						$("#div_cargando").css("display", "none");
						$("#div_error").css("display", "inline");
						$('#btnSubmit').prop('disabled', false);
					}
				},
				error: function ( result ) {
					alert('Error. Reload the web page.');
					$("#div_cargando").css("display", "none");
					$("#div_error").css("display", "inline");
					$('#btnSubmit').prop('disabled', false);
				}
			});
		}
	});

	$("#btnState").click(function () {
		if ( $("#formState").valid() == true ) {
			$('#btnState').prop('disabled', true);

			$.ajax({
				type: "POST",
				url: base_url + "workorders/save_workorder_state",
				data: $("#formState").serialize(),
				dataType: "json",
				contentType: "application/x-www-form-urlencoded;charset=UTF-8",
				cache: false,
				success: function ( data ) {
					if ( data.status == "error" ) {
						$("#div_cargando").css("display", "none");
						$('#btnState').prop('disabled', false);
						$("#span_msj").html(data.mensaje);
						$("#div_msj").css("display", "inline");
						return false;
					}

					if ( data.status == "success" ) {
						$("#div_cargando").css("display", "none");
						$("#div_guardado").css("display", "inline");
						$('#btnState').prop('disabled', false);

						const url = base_url + "workorders/add_workorder/" + data.idWorkorder;
						window.location.href = url;
					} else {
						alert('Error. Reload the web page.');
						$("#div_cargando").css("display", "none");
						$("#div_error").css("display", "inline");
						$('#btnState').prop('disabled', false);
					}
				},
				error: function ( result ) {
					alert('Error. Reload the web page.');
					$("#div_cargando").css("display", "none");
					$("#div_error").css("display", "inline");
					$('#btnState').prop('disabled', false);
				}
			});
		}
	});

	$("#btnEmail").click(function () {
		if ( $("#form").valid() == true ) {
			$('#btnSubmit').prop('disabled', true);
			$('#btnEmail').prop('disabled', true);
			$("#div_guardado").css("display", "none");
			$("#div_error").css("display", "none");
			$("#div_msj").css("display", "none");
			$("#div_cargando").css("display", "inline");

			$.ajax({
				type: "POST",
				url: base_url + "workorders/save_workorder_and_send_email",
				data: $("#form").serialize(),
				dataType: "json",
				contentType: "application/x-www-form-urlencoded;charset=UTF-8",
				cache: false,
				success: function ( data ) {
					if ( data.status == "error" ) {
						$("#div_cargando").css("display", "none");
						$('#btnSubmit').prop('disabled', false);
						$('#btnEmail').prop('disabled', false);
						$("#span_msj").html(data.mensaje);
						$("#div_msj").css("display", "inline");
						return false;
					}

					if ( data.status == "success" ) {
						$("#div_cargando").css("display", "none");
						$("#div_guardado").css("display", "inline");
						$('#btnSubmit').prop('disabled', false);
						$('#btnEmail').prop('disabled', false);

						const url = base_url + "workorders/add_workorder/" + data.idWorkorder;
						window.location.href = url;
					} else {
						alert('Error. Reload the web page.');
						$("#div_cargando").css("display", "none");
						$("#div_error").css("display", "inline");
						$('#btnSubmit').prop('disabled', false);
						$('#btnEmail').prop('disabled', false);
					}
				},
				error: function ( result ) {
					alert('Error. Reload the web page.');
					$("#div_cargando").css("display", "none");
					$("#div_error").css("display", "inline");
					$('#btnSubmit').prop('disabled', false);
					$('#btnEmail').prop('disabled', false);
				}
			});
		}
	});

});

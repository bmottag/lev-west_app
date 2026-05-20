var firstLoad = true;

function previewInvoice(id){
	var url = base_url + "invoices/generaInvoicePDF/" + id;
	$("#iframePreview").attr("src", url);
	$("#modalPreview").modal("show");
}

$( document ).ready( function () {

	let originalInvoiceNumber = $("#number").val();

	$("#number").on("change", function(){

		let currentValue = $(this).val();

		if(currentValue != originalInvoiceNumber){

			let confirmChange = confirm(
				"⚠️ This is not the next suggested invoice number.\n\n" +
				"Recommended: " + originalInvoiceNumber + "\n\n" +
				"Are you sure you want to change it?"
			);

			if(!confirmChange){
				$(this).val(originalInvoiceNumber);
			}
		}

	});

	// limpiar iframe cuando se cierre el modal
	$('#modalPreview').on('hidden.bs.modal', function () {
		$("#iframePreview").attr("src", "");
	});

	function calculateBalance(){

		let total = parseFloat($("#invoice_total").val()) || 0;
		let paid = parseFloat($("#total_paid").val()) || 0;

		let balance = total - paid;

		if(balance < 0){
			balance = 0;
		}

		$("#balance_due").val(balance.toFixed(2));

		// actualizar maximo permitido del pago
		$("#amount").attr("max", balance.toFixed(2));

		// cambiar estilo segun estado
		if(balance == 0){
			$("#balance_due")
				.removeClass("balance-due")
				.addClass("balance-paid");
		}
		else{
			$("#balance_due")
				.removeClass("balance-paid")
				.addClass("balance-due");
		}
	}

	function calculateTotals(){

		let subtotal = 0;

		$(".table-mobile tbody tr").each(function(){

			let qty = parseFloat($(this).find(".quantity-field").val()) || 0;
			let rate = parseFloat($(this).find(".rate-field").val()) || 0;
			let markup = parseFloat($(this).find(".markup-field").val()) || 0;

			let total = qty * rate * (markup + 100) / 100;

			$(this).find(".total-field").val(total.toFixed(2));

			subtotal += total;

		});

		let gst = subtotal * 0.05;
		let total_invoice = subtotal + gst;

		$("#subtotal").val(subtotal.toFixed(2));
		$("#gst").val(gst.toFixed(2));
		$("#invoice_total").val(total_invoice.toFixed(2));

		calculateBalance();
	}

	$(document).on("input", ".quantity-field, .rate-field, .markup-field", function(){
		calculateTotals();
	});
			
	$( "#form" ).validate( {
		rules: {
			jobName:				{ required: true },
			date:					{ required: true },
			due_date:				{ required: true },
			number: 				{ required: true, maxlength:10 }
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
	
	$('#jobName').change(function () {
		var idJob = $('#jobName').val();

		// solo limpiar si NO es la primera carga
		if (!firstLoad) {
			$('#link_to').val(''); 
			
			$('#list_work_order').html('<option value="">Select...</option>');
			$('#selected_link_id').val('');
		}

		if (idJob > 0 || idJob != '') {			
			$.ajax({
				type: "POST",	
				url: base_url + "workorders/foremanInfo",	
				data: {'idJob': idJob},
				dataType: "json",
				contentType: "application/x-www-form-urlencoded;charset=UTF-8",
				cache: false,
				success: function(data){
					if( data.result )//true
					{	               
						$("#company").val(data.company_id);    
						$("#companyName").val(data.company_name);
						$("#companyEmail").val(data.company_email);
					}

				}
			});	
		}	
	});	

	$('#link_to').change(function () {
		var link_to = $('#link_to').val();
		var jobCode = $('#jobName').val();
		var endpoint = '';

		// solo limpiar si NO es la primera carga
		if (!firstLoad) {
			$('#list_work_order').html('<option value="">Select...</option>');
			$('#selected_link_id').val('');
		}

		if (link_to !== '') {
			if (jobCode) {
				// Definir endpoint según el valor
				if (link_to === 'wo') {
					endpoint = base_url + 'invoices/woList';
					$('#label_list').text('Select Work Order');
				} 
				else if (link_to === 'claim') {
					endpoint = base_url + 'invoices/claimList';
					$('#label_list').text('Select Claim');
				}

				if (endpoint !== '') {

					$('#list_work_order').html('<option value="">Loading...</option>');

					$.ajax({
						type: 'POST',
						url: endpoint,
						data: { jobCode: jobCode },
						cache: false,
						success: function (data)
						{
							$('#list_work_order').html(data);

							var selected = $('#selected_link_id').val();
							if(selected){
								$('#list_work_order').val(selected);
							}

							firstLoad = false;
						}
					});
					$("#div_list_work_order").css("display", "inline");
				}
			} else {
				$('#id_work_order').val(null);
				alert('Please select a job code');
			}
		}
	});
				
	$("#btnSubmit").click(function(){		
	
		if ($("#form").valid() == true){

			var year = $("#year").val();
			var number = $("#number").val();

			var fullNumber = year + "-" + number;

			$("#number").val(fullNumber);
		
				//Activa icono guardando
				$('#btnSubmit').attr('disabled','-1');
				$("#div_guardado").css("display", "none");
				$("#div_error").css("display", "none");
				$("#div_msj").css("display", "none");
				$("#div_cargando").css("display", "inline");

				$.ajax({
					type: "POST",	
					url: base_url + "invoices/save_invoice",	
					data: $("#form").serialize(),
					dataType: "json",
					contentType: "application/x-www-form-urlencoded;charset=UTF-8",
					cache: false,
					
					success: function(data){
                                            
						if( data.result == "error" )
						{
							//alert(data.mensaje);
							$("#div_cargando").css("display", "none");
							$('#btnSubmit').removeAttr('disabled');							
							
							$("#span_msj").html(data.mensaje);
							$("#div_msj").css("display", "inline");
							return false;
						
						} 

						
										
						if( data.result )//true
						{	                                                        
							$("#div_cargando").css("display", "none");
							$("#div_guardado").css("display", "inline");
							$('#btnSubmit').removeAttr('disabled');

							var url = base_url + "invoices/add_invoice/" + data.idInvoice;
							$(location).attr("href", url);
						}
						else
						{
							alert('Error. Reload the web page.');
							$("#div_cargando").css("display", "none");
							$("#div_error").css("display", "inline");
							$('#btnSubmit').removeAttr('disabled');
						}	
					},
					error: function(result) {
						alert('Error. Reload the web page.');
						$("#div_cargando").css("display", "none");
						$("#div_error").css("display", "inline");
						$('#btnSubmit').removeAttr('disabled');
					}
					
		
				});	
		
		}//if			
	});
	
	$("#btnEmail").click(function(){		
	
		if ($("#form").valid() == true){
		
				//Activa icono guardando
				$('#btnSubmit').attr('disabled','-1');
				$('#btnEmail').attr('disabled','-1');
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
					
					success: function(data){
                                            
						if( data.result == "error" )
						{
							//alert(data.mensaje);
							$("#div_cargando").css("display", "none");
							$('#btnSubmit').removeAttr('disabled');
							$('#btnEmail').removeAttr('disabled');								
							
							$("#span_msj").html(data.mensaje);
							$("#div_msj").css("display", "inline");
							return false;
						
						} 

						
										
						if( data.result )//true
						{	                                                        
							$("#div_cargando").css("display", "none");
							$("#div_guardado").css("display", "inline");
							$('#btnSubmit').removeAttr('disabled');
							$('#btnEmail').removeAttr('disabled');

							var url = base_url + "workorders/add_workorder/" + data.idWorkorder;
							$(location).attr("href", url);
						}
						else
						{
							alert('Error. Reload the web page.');
							$("#div_cargando").css("display", "none");
							$("#div_error").css("display", "inline");
							$('#btnSubmit').removeAttr('disabled');
							$('#btnEmail').removeAttr('disabled');
						}	
					},
					error: function(result) {
						alert('Error. Reload the web page.');
						$("#div_cargando").css("display", "none");
						$("#div_error").css("display", "inline");
						$('#btnSubmit').removeAttr('disabled');
						$('#btnEmail').removeAttr('disabled');
					}
					
		
				});	
		
		}//if			
	});

	// ---- DISPARAR AUTOMÁTICAMENTE EN EDICIÓN ----
	var link_to = $('#link_to').val();
	var jobCode = $('#jobName').val();

	if (link_to && jobCode) {
		setTimeout(function(){
			$('#link_to').trigger('change');
		}, 200);
	}

	calculateTotals();
	calculateBalance();

});
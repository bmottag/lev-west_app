$( document ).ready( function () {

	$("#btnSubmitCheckOut").click(function(){		
		
				//Activa icono guardando
				$('#btnSubmitCheckOut').prop('disabled', true);
				$("#div_error").hide();
				$("#div_load").show();
			
				$.ajax({
					type: "POST",	
					url: base_url + "external/save_checkout",	
					data: $("#formCheckout").serialize(),
					dataType: "json",
					cache: false,
					
					success: function(data){
                                            
						$("#div_load").hide();
						$('#btnSubmitCheckOut').prop('disabled', false);

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
						$('#btnSubmitCheckOut').prop('disabled', false);
					}
					
				});	
		
			
	});
});
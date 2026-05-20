$( document ).ready( function () {

    $('#type').change(function () {
		$("#loader").addClass("loader");
        $('#type option:selected').each(function () {
			var type = $('#type').val();
			var idAttachment = $('#hddId').val();
			loadEquipmentList(type,idAttachment);

        });
    });

	$(function() {
		$("#loader").addClass("loader");
		var type = $('#type').val();
		var idAttachment = $('#hddId').val();
		loadEquipmentList(type,idAttachment);
	})

	$("#contact").bloquearNumeros().maxlength(50);		
	$("#movilNumber").bloquearTexto().maxlength(10);
	
	$( "#form" ).validate( {
		rules: {
			attachment_number:				{ required: true, minlength: 3, maxlength:10 },
			attachment_description:			{ required: true, minlength: 3, maxlength:60 }
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
					url: base_url + "admin/save_attachments",	
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
							window.location.href = base_url + "admin/attachments/active";
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

/*
* Function to load Equipment List
*/
function loadEquipmentList(type,idAttachment) {
	if (type > 0 || type != '') {
		$.ajax ({
			type: 'POST',
			url: base_url + 'admin/equipmentList',
			data: {'type': type, idAttachment},
			cache: false,
			success: function (data)
			{
				$('#equipment').html(data);
			}
		});
		$("#div_equipment").css("display", "inline");
		$('#equipment').val("");
	} else {
		var data = '';
		$('#equipment').html(data);
	}
	$("#loader").removeClass("loader");
}
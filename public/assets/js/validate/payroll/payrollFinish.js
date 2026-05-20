$( document ).ready( function () {

	$( "#form" ).validate( {
		ignore: "input[type='text']:hidden",
		rules: {
			jobName: 			{ required: true },
			address: 			{ required: true }
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
			$("#btnSubmit").prop("disabled", true);
			var form = document.getElementById('form');
			form.submit();
		}
	});

	var programming = $('#programming').val();
	var job_start = $('#job_start').val();
	var jobName = $('#jobName').val();

	if (programming == ''){

		if (job_start != jobName) {
			// labelText = "How long were you in the last project: ";
			// $('#div_timeFirstJob label.control-label').text(labelText);
			$('#div_timeFirstJob').show();
		} else {
			$('#div_timeFirstJob').hide();
		}
	}

	$('#jobName').change(function() {
		var jobName = $(this).val();
		var job_programming = $('#job_programming').val();
		var job_start_name = $('#job_start_name').val();

		//var labelText = "How long were you in: ";

		if (!programming) {
			if (job_start != jobName) {

				// var selectedText = $(jobName).find("option:selected").text();
				// labelText = "How long were you in " + selectedText + ": ";
				$('#div_timeFirstJob').show();
			} else {
				$('#div_timeFirstJob').hide();
			}
		} else {
			if (job_start == job_programming && jobName != job_start) {
				// if (job_start_name) {
				// 	labelText = "How long were you in " + job_start_name + ": ";
				// }
				$('#div_timeFirstJob').show();
			} else {
            	$('#div_timeFirstJob').hide();
			}
		}

        //$('#div_timeFirstJob label.control-label').text(labelText);
    });

});
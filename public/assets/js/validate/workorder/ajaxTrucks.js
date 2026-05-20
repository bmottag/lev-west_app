/**
 * Trucks list by company
 * @author bmottag
 * @since  25/1/2017
 * @review  28/06/2023
 */

$(document).ready(function () {
	
    $('#type').change(function () {
        $('#type option:selected').each(function () {

			var type = $('#type').val();
			var data = '';
			$('#attachment').html(data);
			$("#div_attachment").css("display", "none");

			if (type > 0 || type != '') {
				
				if (type != 8) {
					$.ajax ({
						type: 'POST',
						url: base_url + 'workorders/truckList',
						data: {'type': type},
						cache: false,
						success: function (data)
						{
							$('#truck').html(data);
						}
					});
					
					if(type == 3) {
						$("#div_standby").css("display", "block");
					}else{
						$("#div_standby").css("display", "none");
					}
					
					$("#div_other").css("display", "none");
					$("#div_truck").css("display", "block");
					$('#otherEquipment').val("");
					$('#truck').val("");
				}else{
					$("#div_other").css("display", "block");
					$("#div_truck").css("display", "none");
					$('#otherEquipment').val("");
					$('#truck').val(5);
				}
				
			} else {
				var data = '';
				$('#truck').html(data);
			}

        });
    });

    $('#truck').change(function () {
        $('#type option:selected').each(function () {

			var equipmentId = $('#truck').val();

			if (equipmentId > 0 || equipmentId != '') 
			{
				$.ajax ({
					type: 'POST',
					url: base_url + 'workorders/attachmentList',
					data: {equipmentId},
					cache: false,
					success: function (data)
					{
						$('#attachment').html(data);
						if( data == "" )
						{
							$("#div_attachment").css("display", "none");
						} else {
							$("#div_attachment").css("display", "block");
						}
					}
				});
			} else {
				var data = '';
				$('#attachment').html(data);
				$("#div_attachment").css("display", "none");
			}

        });
    });
	
    $('#standby').change(function () {
        $('#standby option:selected').each(function () {

			var standby = $('#standby').val();

			if (standby > 0 || standby != '') {
				if (standby == 1) {
					$("#div_operated").css("display", "none");
					$('#operatedby').val("");
				}else{
					$("#div_operated").css("display", "inline");
				}
			}

        });
    });
    
});
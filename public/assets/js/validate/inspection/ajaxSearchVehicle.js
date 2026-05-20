/**
 * Vehicle information by vin number
 * @author bmottag
 * @since  14/4/2020
 */

$(document).ready(function () {
	   
    $('#btnVINNumber').click(function () {
		var vinNumber = $('#vinNumber').val();
		$("#div_vehicle").show();
		if (vinNumber.length > 4) {
			$.ajax ({
				type: 'POST',
				url: base_url + 'inspection/vehicleInfo',
				data: {'vinNumber': vinNumber},
				cache: false,
				success: function (data)
				{
					$('#div_vehicle').html(data);
				}
			});
		} else {				
			var data = "<p class='text-danger'>Enter at least 5 consecutive characters of the<strong> VIN NUMBER</strong></p>";
			$('#div_vehicle').html(data);
		}
    });    
});
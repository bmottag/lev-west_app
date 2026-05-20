/**
 * Excavations filds
 * @author bmottag
 * @since  8/8/2021
 */

$(document).ready(function () {

    $('#confined_space').change(function () {
        $('#confined_space option:selected').each(function () {
            var confined_space = $('#confined_space').val();

            if ((confined_space > 0 || confined_space != '') ) {
                $("#div_confined").hide();
                $('#idConfined').val("");
                if(confined_space==1){
                    $("#div_confined").show();
                }
            }
        });
    });
	
    $('#tested_daily').change(function () {
        $('#tested_daily option:selected').each(function () {
            var tested_daily = $('#tested_daily').val();

            if ((tested_daily > 0 || tested_daily != '') ) {
                $("#div_tested_daily_explanation").hide();
                $('#tested_daily_explanation').val("");
				if(tested_daily==1){
                    $("#div_tested_daily_explanation").show();
				}
            }
        });
    });

    $('#ventilation').change(function () {
        $('#ventilation option:selected').each(function () {
            var ventilation = $('#ventilation').val();

            if ((ventilation > 0 || ventilation != '') ) {
                $("#div_ventilation_explanation").hide();
                $('#ventilation_explanation').val("");
                if(ventilation==1){
                    $("#div_ventilation_explanation").show();
                }
            }
        });
    });
    

    
});
/**
 * Excavations filds
 * @author bmottag
 * @since  8/8/2021
 */

$(document).ready(function () {
	
    $('#sloping').change(function () {


            if ($('#sloping').prop('checked') ) {
                $("#div_sloping").show();
            }else{
                $('#type_a').prop('checked', false);
                $('#type_b').prop('checked', false);
                $('#type_c').prop('checked', false);
                $("#div_sloping").hide();
            }

    });
    
});
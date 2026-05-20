/**
 * Validaciones
 * @author bmottag
 * @since  4/05/2022
 */

$(document).ready(function () {
	
    $('#login_before').change(function () {
        $('#login_before option:selected').each(function () {
            var login_before = $('#login_before').val();
            if ((login_before > 0 || login_before != '') ) {
				
				$("#div_name").css("display", "none");
                $("#div_new_worker").css("display", "none");                
                $("#id_name").val("");
                $("#new_name").val("");
                $("#new_phone_number").val("");
				if(login_before==1){
					$("#div_name").css("display", "inline");
                    $("#div_new_worker").css("display", "none");
				}else{
                    $("#div_name").css("display", "none");
                    $("#div_new_worker").css("display", "inline");
                }
            }
        });
    });
  
});
/**
 * Link list by menu
 * @author bmottag
 * @since  1/4/2020
 */

$(document).ready(function () {
	   
    $('#id_menu').change(function () {
        var idMenu = $(this).val();
        if (idMenu && idMenu !== '') {

            $("#div_link").show();
            $.ajax({
                type: 'POST',
                url: base_url + 'enlaces/linkListInfo',
                data: { idMenu: idMenu },
                cache: false,
                success: function (data) {
                    let html = "<option value=''>Select...</option>";

                    data.forEach(function (item) {
                        html += `<option value="${item.id_link}">${item.link_name}</option>`;
                    });

                    $('#id_link').html(html);
                }
            });

        } else {
            $("#div_link").hide();
            $('#id_link').html('');
        }
    });
    
});
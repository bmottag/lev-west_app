/**
 * Employee list by contract type
 * @author bmottag
 * @since  11/9/2022
 */

$(document).ready(function () {

    $('#yearPeriod').change(function () {
        $('#yearPeriod option:selected').each(function () {
            var yearPeriod = $('#yearPeriod').val();
            if (yearPeriod > 0 || yearPeriod != '') {
                $.ajax ({
                    type: 'POST',
                    url: base_url + 'payroll/periodList',
                    data: {'identificador': yearPeriod},
                    cache: false,
                    success: function (data)
                    {
                        $('#period').html(data);
                    }
                });
            } else {
                var data = '';
                $('#period').html(data);
            }
        });
    });
	
    $('#contractType').change(function () {
        $('#contractType option:selected').each(function () {
            var contractType = $('#contractType').val();
            if (contractType > 0 || contractType != '') {
                $.ajax ({
                    type: 'POST',
                    url: base_url + 'payroll/employeeList',
                    data: {'identificador': contractType},
                    cache: false,
                    success: function (data)
                    {
                        $("#div_employee").css("display", "inline");
                        $('#employee').html(data);
                    }
                });
            } else {
                var data = '';
                $('#employee').html(data);
                $("#div_employee").css("display", "none");
            }
        });
    });
    
});
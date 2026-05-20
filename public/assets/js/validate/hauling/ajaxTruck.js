/**
 * Trucks�list by company
 * @author bmottag
 * @since  12/12/2016
 */

$(document).ready(function () {
    $("#div_list_work_order").css("display", "none");
    $("#div_work_order").css("display", "none");

    $('#CompanyType').change(function () {
        $('#CompanyType option:selected').each(function () {
            var CompanyType = $('#CompanyType').val();
            if ((CompanyType > 0 || CompanyType != '') ) {

                $("#div_plate").hide();
                $("#div_truck").show();
				if(CompanyType==2){
                    $("#div_plate").show();
                    $("#div_truck").hide();
				}

                $.ajax ({
                    type: 'POST',
                    url: base_url + 'hauling/companyList',
                    data: {'CompanyType': CompanyType},
                    cache: false,
                    success: function (data)
                    {
                        $('#company').html(data);
                    }
                });
            } else {
                var data = '';
                $('#company').html(data);
            }
        });
    });

    $('#company').change(function () {
        $('#company option:selected').each(function () {
            var company = $('#company').val();
            if (company > 0 || company != '-') {
                $.ajax ({
                    type: 'POST',
                    url: base_url + 'hauling/truckList',
                    data: {'identificador': company},
                    cache: false,
                    success: function (data)
                    {
                        $('#truck').html(data);
                    }
                });
            } else {
                var data = '';
                $('#truck').html(data);
            }
        });
    });

    $('#id_work_order').change(function () {
        $('#id_work_order option:selected').each(function () {
            var workOrder = $('#id_work_order').val();
            var jobCode = $('#fromSite').val();
            if (workOrder != '') {
                console.log(workOrder, jobCode, jobCode != '', 'workOrder');

				if(workOrder==2){
                    if (jobCode) {
                        $.ajax ({
                            type: 'POST',
                            url: base_url + 'hauling/woList',
                            data: {'jobCode': jobCode},
                            cache: false,
                            success: function (data)
                            {
                                console.log(data)
                                $('#list_work_order').html(data);
                            }
                        });
                        $("#div_list_work_order").show();
                    } else {
                        $('#id_work_order').val(null);
                        alert('Please select a job code');
                    }
                } else {
                    $("#div_list_work_order").hide();
                }
            } else {
                $("#div_list_work_order").hide();
            }
        });
    });

    $('#fromSite').change(function () {
        var jobCode = $('#fromSite').val();
        if (jobCode != '') {
            $.ajax ({
                type: 'POST',
                url: base_url + 'hauling/list_by_job_code',
                data: {'jobCode': jobCode},
                cache: false,
                success: function (data)
                {
                    console.log(data, data != 0, 'data');

                    if (data != 0) {
                        $("#div_work_order").hide();
                        $('#list_work_order').empty();
                        $('#list_work_order').append('<option value="'+data+'" select>Select...</option>');
                        $('#list_work_order').val(data).trigger('change');
                    } else {
                        $("#div_work_order").show();
                    }
                }
            });
        }
    });

});
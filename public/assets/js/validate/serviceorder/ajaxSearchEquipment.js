/**
 * Vehicle information by vin number
 * @author bmottag
 * @since  14/4/2020
 */

$(document).ready(function () {

	$(function() {
		var serviceOrderId = $('#hddSpecificIdServiceOrder').val();
		var equipmentId = $('#hddSpecificIdEquipment').val();
		var moduleView = $('#hddModuleView').val();
		if(serviceOrderId != ""){
			loadEquipmentDetail( equipmentId , 'tab_service_order_detail', serviceOrderId );
		}else if(equipmentId != ""){
			if(moduleView != "x"){
				loadEquipmentDetail( equipmentId, moduleView );
			}else{
				loadEquipmentDetail( equipmentId, 'tab_preventive_maintenance' );
			}
		}
	})
	   
    $('#btnVINNumber').click(function () {
		$("#loader").addClass("loader");
		var vinNumber = $('#vinNumber').val();
		$("#div_info_SO_main").css("display", "none");
		$("#div_info_list").css("display", "block");
		if (vinNumber.length > 4) {
			$.ajax ({
				type: 'POST',
				url: base_url + 'serviceorder/equipmentList',
				data: {'vinNumber': vinNumber, 'inspectionType': false, 'headerInspectionType': 'Search by VIN Number'},
				cache: false,
				success: function (data)
				{
					$('#div_info_list').html(data);
					$("#loader").removeClass("loader");
				}
			});
		} else {				
			var data = "<p class='text-danger'>Enter at least 5 consecutive characters of the<strong> VIN NUMBER.</strong></p>";
			$('#div_info_list').html(data);
		}
    });
	
    $('#btnSONumber').click(function () {
		$("#loader").addClass("loader");
		var soNumber = $('#soNumber').val();
		$("#div_info_SO_main").css("display", "none");
		$("#div_info_list").css("display", "block");
		if (soNumber != "" && soNumber != 0) {
			$.ajax ({
				type: 'POST',
				url: base_url + 'serviceorder/serviceOrderList',
				data: {'soNumber': soNumber, 'status': "x"},
				cache: false,
				success: function (data)
				{
					$('#div_info_list').html(data);
					$("#loader").removeClass("loader");
				}
			});
		} else {				
			var data = "<p class='text-danger'><strong> SERVICE ORDER NUMBER</strong> required.</p>";
			$('#div_info_list').html(data);
		}
    }); 

});

/*
* Function to load Equipment List
*/
function loadEquipmentList(inspectionType, headerInspectionType) {
	$("#loader").addClass("loader");
	$("#div_info_list").css("display", "block");
	$("#div_main_title").css("display", "block");
	$("#div_panel_main").css("display", "block");
	$("#div_info_SO_main").css("display", "none");
	$("#div_search").css("display", "none");
	$("#div_detail").css("display", "none");
	$.ajax ({
		type: 'POST',
		url: base_url + 'serviceorder/equipmentList',
		data: {'vinNumber': false, 'inspectionType': inspectionType, 'headerInspectionType': headerInspectionType},
		cache: false,
		success: function (data)
		{
			$('#div_info_list').html(data);
			$("#loader").removeClass("loader");
		}
	});
}

/*
* Function to load SO List
*/
function loadSOList(status) {
	$("#loader").addClass("loader");
	$("#div_info_list").css("display", "block");
	$("#div_info_SO_main").css("display", "none");
	$("#div_search").css("display", "none");
	$.ajax ({
		type: 'POST',
		url: base_url + 'serviceorder/serviceOrderList',
		data: {status},
		cache: false,
		success: function (data)
		{
			$('#div_info_list').html(data);
			$("#loader").removeClass("loader");
		}
	});
}

/*
* Function to load Equipment Detail
*/
function loadEquipmentDetail(equipmentId, tabview, serviceOrderId=false) {
	$("#div_detail").css("display", "block");
	$("#div_info_list").css("display", "none");
	$("#div_panel_main").css("display", "none");
	$("#div_main_title").css("display", "none");
    $("#loader").addClass("loader");
	$.ajax ({
		type: 'POST',
		url: base_url + 'serviceorder/equipmentDetail',
		data: {equipmentId, tabview, serviceOrderId},
		cache: false,
		success: function (data)
		{
			$("#loader").removeClass("loader");
			$('#div_detail').html(data);
		}
	});
}

/*
* Function to load Expenses
*/
function loadExpenses() {
	$("#loader").addClass("loader");
	$("#div_info_list").css("display", "block");
	$("#div_main_title").css("display", "block");
	$("#div_panel_main").css("display", "block");
	$("#div_info_SO_main").css("display", "none");
	$("#div_search").css("display", "none");
	$("#div_detail").css("display", "none");
	$.ajax ({
		type: 'POST',
		url: base_url + 'serviceorder/expenses',
		cache: false,
		success: function (data)
		{
			$('#div_info_list').html(data);
			$("#loader").removeClass("loader");
		}
	});
}

/*
* Function to load Expenses
*/
function loadExpensesByEquipment(equipmentId) {
	$("#loader").addClass("loader");
	$("#div_info_list").css("display", "block");
	$("#div_main_title").css("display", "block");
	$("#div_panel_main").css("display", "block");
	$("#div_info_SO_main").css("display", "none");
	$("#div_search").css("display", "none");
	$("#div_detail").css("display", "none");
	$.ajax ({
		type: 'POST',
		url: base_url + 'serviceorder/expensesByEquipment',
		data: {equipmentId},
		cache: false,
		success: function (data)
		{
			$('#div_info_list').html(data);
			$("#loader").removeClass("loader");
		}
	});
}
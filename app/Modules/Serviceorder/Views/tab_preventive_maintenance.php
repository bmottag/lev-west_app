<script>
$(function(){
	$(".btn-service-order").click(function () {
			var oID = $(this).attr("id");
            var idEquipment = $('#hddIdEquipment').val();
            $.ajax ({
                type: 'POST',
				url: base_url + 'serviceorder/cargarModalServiceOrder',
				data: { "idServiceOrder": "x", "idMaintenance": oID, "maintenanceType": "preventive", idEquipment },
                cache: false,
                success: function (data) {
                    $('#tablaDatosServiceOrder').html(data);
                }
            });
	});	

	$(".btn-preventive-maintenance").click(function () {
			var oID = $(this).attr("id");
            var idEquipment = $('#hddIdEquipment').val();
            $.ajax ({
                type: 'POST',
				url: base_url + 'serviceorder/cargarModalPreventiveMaintenance',
				data: { "idMaintenance": oID, idEquipment },
                cache: false,
                success: function (data) {
                    $('#tablaDatosMaintenace').html(data);
                }
            });
	});	
});
</script>

<?php
	//Disabled fields
	$deshabilitar = '';
	$userRol = session()->get("rol");
	if($userRol != ID_ROL_SUPER_ADMIN && $userRol != ID_ROL_SAFETY && $userRol != ID_ROL_MECHANIC ){
		$deshabilitar = 'disabled';
	}
?>

<div class="panel panel-violeta">
    <div class="panel-heading">
        <i class="fa fa-wrench"></i> <strong>Preventive Maintenance</strong>
        <div class="pull-right">
            <input type="hidden" id="hddIdEquipment" name="hddIdEquipment" value="<?php echo $vehicleInfo[0]['id_vehicle']; ?>"/>
            <button type="button" class="btn btn-violeta btn-xs btn-preventive-maintenance" data-toggle="modal" data-target="#modalMaintenance" id="x" <?php echo $deshabilitar; ?>>
                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add Preventive Maintenance
            </button>
        </div>
    </div>
    <div class="panel-body small">

        <?php if (session()->getFlashdata('retornoExito')): ?>
            <div class="alert alert-success">
                <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                <?= session()->getFlashdata('retornoExito') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('retornoError')): ?>
            <div class="alert alert-danger">
                <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                <?= session()->getFlashdata('retornoError') ?>
            </div>
        <?php endif; ?>
        
        <?php 										
            if(!$infoPreventiveMaintenance){ 
                echo '<div class="col-lg-12">
                        <p class="text-danger"><span class="glyphicon glyphicon-alert" aria-hidden="true"></span> There are no records in the system.</p>
                    </div>';
            } else {
        ?>
        <table width="100%" class="table table-striped table-bordered table-hover" id="dataPreventiveMaintenance">
            <thead>
                <tr>
                    <th class="text-center">Maintenance Type</th>
                    <th class="text-center">Description</th>
                    <th class="text-center">Next Hours/Kilometers maintenance </th>
                    <th class="text-center">Next date maintenance  </th>
                    <th class="text-center">Service Order</th>
                </tr>
            </thead>
            <tbody>							
            <?php
                foreach ($infoPreventiveMaintenance as $lista):
                    $nextHoursMaintenance = $lista['next_hours_maintenance'] == 0?"":number_format($lista['next_hours_maintenance']);
                    $nextDateMaintenance = $lista['next_date_maintenance'] == "0000-00-00"?"":date('F j, Y', strtotime($lista['next_date_maintenance']));
                    switch ($lista['maintenance_status']) {
                        case 1:
                            $valor = 'Active';
                            $clase = "text-success";
                            $buttonSO = '';
                            break;
                        case 2:
                            $valor = 'Inactive';
                            $clase = "text-danger";
                            $buttonSO = 'disabled';
                            break;
                    }
                    echo "<tr>";
                    echo "<td>" . $lista['maintenance_type'] . "</td>";
                    echo "<td>" . $lista['maintenance_description'] . "</td>";
                    echo "<td class='text-right'>" . $nextHoursMaintenance. "</td>";
                    echo "<td>" . $nextDateMaintenance . "</td>";
                    echo "<td class='text-center'>";
                    ?>
						<button type="button" class="btn btn-primary btn-xs btn-preventive-maintenance" data-toggle="modal" data-target="#modalMaintenance" id="<?php echo $lista['id_preventive_maintenance']; ?>" title="Edit" <?php echo $deshabilitar; ?> >
							Edit  <span class="glyphicon glyphicon-edit" aria-hidden="true"> </span>
						</button>

                        <button type="button" class="btn btn-violeta btn-xs btn-service-order" data-toggle="modal" data-target="#modalServiceOrder" id="<?php echo $lista['id_preventive_maintenance']; ?>" <?php echo $buttonSO; ?> >
                            Creat S.O. <span class="glyphicon glyphicon-briefcase" aria-hidden="true">
                        </button>
                    <?php
                     echo '<p class="' . $clase . '"><strong>' . $valor . '</strong></p>';
                    echo "</td>";
                    echo "</tr>";
                endforeach;
            ?>
            </tbody>
        </table>
    <?php } ?>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#dataPreventiveMaintenance').DataTable({
        responsive: true,
		"ordering": false,
		paging: false,
		"searching": false,
		"info": false
    });
});
</script>
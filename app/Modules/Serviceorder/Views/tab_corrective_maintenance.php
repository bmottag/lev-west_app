<script>
$(function(){
	$(".btn-service-order").click(function () {
			var oID = $(this).attr("id");
            var idEquipment = $('#hddIdEquipment').val();
            $.ajax ({
                type: 'POST',
				url: base_url + 'serviceorder/cargarModalServiceOrder',
				data: { "idServiceOrder": "x", "idMaintenance": oID, "maintenanceType": "corrective", idEquipment },
                cache: false,
                success: function (data) {
                    $('#tablaDatosServiceOrder').html(data);
                }
            });
	});	

	$(".btn-corrective-maintenance").click(function () {
			var oID = $(this).attr("id");
            var idEquipment = $('#hddIdEquipment').val();
            $.ajax ({
                type: 'POST',
				url: base_url + 'serviceorder/cargarModalCorrectiveMaintenance',
				data: { "idMaintenance": oID, idEquipment },
                cache: false,
                success: function (data) {
                    $('#tablaDatosMaintenace').html(data);
                }
            });
	});	
});
</script>

<div class="panel panel-violeta">
    <div class="panel-heading">
        <i class="fa fa-wrench"></i> <strong>Corrective Maintenance</strong>
        <div class="pull-right">
            <input type="hidden" id="hddIdEquipment" name="hddIdEquipment" value="<?php echo $vehicleInfo[0]['id_vehicle']; ?>"/>
            <button type="button" class="btn btn-violeta btn-xs btn-corrective-maintenance" data-toggle="modal" data-target="#modalMaintenance" id="x">
                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add Corrective Maintenance
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
            if(!$infoCorrectiveMaintenance){ 
                echo '<div class="col-lg-12">
                        <p class="text-danger"><span class="glyphicon glyphicon-alert" aria-hidden="true"></span> There are no records in the system.</p>
                    </div>';
            } else {
        ?>
        <table width="100%" class="table table-striped table-bordered table-hover" id="dataCorrectiveMaintenance">
            <thead>
                <tr>
                    <th class="text-center">Date Request</th>
                    <th class="text-center">Description of Failure or Damage</th>
                    <th class="text-center">Request By </th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Service Order</th>
                </tr>
            </thead>
            <tbody>							
            <?php
                foreach ($infoCorrectiveMaintenance as $lista):
                    echo "<tr>";
                    echo "<td>" . date('F j, Y - G:i:s', strtotime($lista['created_at'])) . "</td>";
                    echo "<td>" . $lista['description_failure'] . "</td>";
                    echo "<td>" . $lista['request_by'] . "</td>";
                    echo "<td class='text-center'>";
                    echo '<p class="text-' . $lista['status_style'] . '"><i class="fa ' . $lista['status_icon'] . ' fa-fw"></i><b>' . $lista['status_name'] . '</b></p>';
                    echo "</td>";
                    echo "<td class='text-center'>";
                        if($lista['maintenance_status'] != "pending"){
                            echo "-";
                        }else{
                    ?>
						<button type="button" class="btn btn-primary btn-xs btn-corrective-maintenance" data-toggle="modal" data-target="#modalMaintenance" id="<?php echo $lista['id_corrective_maintenance']; ?>" title="Edit" >
							Edit  <span class="glyphicon glyphicon-edit" aria-hidden="true"> </span>
						</button>

                        <button type="button" class="btn btn-violeta btn-xs btn-service-order" data-toggle="modal" data-target="#modalServiceOrder" id="<?php echo $lista['id_corrective_maintenance']; ?>">
                            Creat S.O. <span class="glyphicon glyphicon-briefcase" aria-hidden="true">
                        </button>
                    <?php
                        }
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
    $('#dataCorrectiveMaintenance').DataTable({
        responsive: true,
		"ordering": false,
		paging: false,
		"searching": false,
		"info": false
    });
});
</script>
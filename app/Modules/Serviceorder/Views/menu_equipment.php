<!-- IMAGEN DEL EQUIPO -->
<?php 
if($vehicleInfo[0]["photo"]){
    ?>
    <div class="form-group">
        <div class="row" align="center">
            <img src="<?php echo base_url($vehicleInfo[0]["photo"]); ?>" class="img-rounded" width="150" height="150" alt="Equipment Image" />
        </div>
    </div>
<?php } ?>
<!-- FIN IMAGEN DEL EQUIPO -->
<div class="form-group">
    <div class="row">
        <div class="col-lg-6">
            <b>Unit Number: </b><br><?php echo $vehicleInfo[0]['unit_number']; ?><br>
            <b>VIN Number: </b><br><?php echo  $vehicleInfo[0]['vin_number']; ?><br>
        </div>
        <div class="col-lg-6">
            <b>Make: </b><br><?php echo $vehicleInfo[0]['make']; ?><br>
            <b>Model: </b><br><?php echo  $vehicleInfo[0]['model']; ?><br>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <b>Description: </b><br><?php echo  $vehicleInfo[0]['description']; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <p class='text-danger'>
                <?php
                    $tipo = $vehicleInfo[0]['type_level_2'];
					//si es sweeper
					if($tipo == 15){
						echo "<b>Truck Engine Hours: </b>" . number_format($vehicleInfo[0]["hours"]);
						echo "<br><b>Sweeper Engine Hours: </b>" . number_format($vehicleInfo[0]["hours_2"]);
					//si es hydrovac
					}elseif($tipo == 16){
						echo "<b>Engine Hours: </b>" . number_format($vehicleInfo[0]["hours"]);
						echo "<br><b>Hydraulic Pump Hours: </b>" . number_format($vehicleInfo[0]["hours_2"]);		
						echo "<br><b>Blower Hours: </b>" . number_format($vehicleInfo[0]["hours_3"]);
					}else{
						echo "<b>Equipment Hours/Kilometers: </b>" . number_format($vehicleInfo[0]["hours"]);
					}
                ?>
			</p>
        </div>
    </div>
</div>

<?php
    $classInactivo = "btn btn-outline btn-default btn-block";
    $classActivo = "btn btn-violeta btn-block";
?>

<div class="list-group">
    <a class="<?php echo ($tabview=='tab_service_order' || $tabview=='tab_service_order_detail')?$classActivo:$classInactivo; ?>" onclick="loadEquipmentDetail( <?php echo $vehicleInfo[0]['id_vehicle']; ?>, 'tab_service_order' )" >
        <i class="fa fa-briefcase"></i> Service Order
    </a>
    <a class="<?php echo $tabview=='tab_corrective_maintenance'?$classActivo:$classInactivo; ?>" onclick="loadEquipmentDetail( <?php echo $vehicleInfo[0]['id_vehicle']; ?>, 'tab_corrective_maintenance' )" >
        <i class="fa fa-wrench"></i> Corrective Maintenance
    </a>
    <a class="<?php echo $tabview=='tab_preventive_maintenance'?$classActivo:$classInactivo; ?>" onclick="loadEquipmentDetail( <?php echo $vehicleInfo[0]['id_vehicle']; ?>, 'tab_preventive_maintenance' )" >
        <i class="fa fa-wrench"></i> Preventive Maintenance
    </a>
    <a class="<?php echo $tabview=='tab_inspections'?$classActivo:$classInactivo; ?>" onclick="loadEquipmentDetail( <?php echo $vehicleInfo[0]['id_vehicle']; ?>, 'tab_inspections' )" >
        <i class="fa fa-tasks"></i> Inspections
    </a>
    <a class="<?php echo $tabview=='tab_parts_by_store'?$classActivo:$classInactivo; ?>" onclick="loadEquipmentDetail( <?php echo $vehicleInfo[0]['id_vehicle']; ?>, 'tab_parts_by_store' )" >
        <i class="fa fa-wrench"></i> Parts by Store
    </a>
</div>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>
$(function(){ 
	$(".btn-outline").click(function () {	
			var oID = $(this).attr("id");
            $.ajax ({
                type: 'POST',
				url: base_url + 'admin/cargar-modal-vehicle',
                data: {'idVehicle': oID},
                cache: false,
                success: function (data) {
                    $('#tablaDatos').html(data);
                }
            });
	});	
});
</script>


<div id="page-wrapper">
	<br>
	
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
				
<?php
	//DESHABILITAR EDICION
	$session = session();
	$deshabilitar = 'disabled';
	$userRol = $session->rol;
	
	if($userRol == ID_ROL_SUPER_ADMIN || $userRol == ID_ROL_SAFETY){
		$deshabilitar = '';
	}
?>			
					
					<i class="fa fa-automobile"></i> SETTINGS - VEHICLE LIST - <?php echo $title; ?>
				</div>
				<div class="panel-body">
				
				<?php 
					if($companyType==1){
				?>
					<ul class="nav nav-pills">
						<li <?php if($vehicleType == 1){ echo "class='active'";} ?>><a href="<?php echo base_url("admin/vehicle/1/1"); ?>">Pickup Trucks</a>
						</li>
						<li <?php if($vehicleType == 2){ echo "class='active'";} ?>><a href="<?php echo base_url("admin/vehicle/1/2"); ?>">Contruction Equipment</a>
						</li>
						<li <?php if($vehicleType == 3){ echo "class='active'";} ?>><a href="<?php echo base_url("admin/vehicle/1/3"); ?>">Trucks</a>
						</li>
						<li <?php if($vehicleType == 4){ echo "class='active'";} ?>><a href="<?php echo base_url("admin/vehicle/1/4"); ?>">Special Equipment</a>
						</li>
						<li <?php if($vehicleType == 99){ echo "class='active'";} ?>><a href="<?php echo base_url("admin/vehicle/1/99"); ?>">Survey - Trailer</a>
						</li>
						<li <?php if($vehicleType == 5){ echo "class='active'";} ?>><a href="<?php echo base_url("admin/vehicle/1/5"); ?>">Small Equipment</a>
						</li>
						<li <?php if($vehicleState == 2){ echo "class='active'";} ?>><a href="<?php echo base_url("admin/vehicle/1/x/2"); ?>">Inactive Vehicles - Lev West</a>
						</li>
					</ul>
				<?php
					}else{
				?>	
					<ul class="nav nav-pills">
						<li <?php if($vehicleState == 1){ echo "class='active'";} ?>><a href="<?php echo base_url("admin/vehicle/2/x/1"); ?>">Active Rental Vehicles</a>
						</li>
						<li <?php if($vehicleState == 2){ echo "class='active'";} ?>><a href="<?php echo base_url("admin/vehicle/2/x/2"); ?>">Inactive Rental Vehicles</a>
						</li>
					</ul>
				<?php	
					}
				?>
				
<br>
				<?php if(!$deshabilitar){ ?>
					<button type="button" class="btn btn-outline btn-primary btn-block" data-toggle="modal" data-target="#modal" id="<?php echo $companyType . '-x'; ?>">
							<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add a Vehicle
					</button><br>
				<?php } ?>
    <?php
    // Mensaje de éxito
    if ($session->getFlashdata('retornoExito')): ?>
        <div class="row">
            <div class="col-lg-12">
                <div class="alert alert-success">
                    <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                    <?= $session->getFlashdata('retornoExito') ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Mensaje de error -->
    <?php if ($session->getFlashdata('retornoError')): ?>
        <div class="row">
            <div class="col-lg-12">
                <div class="alert alert-danger">
                    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                    <?= $session->getFlashdata('retornoError') ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
				<?php
					if($info){
						

if($companyType == 2){ //si es subcontractor 
	$labelFecha = "Arrival date";
	$labelCompany = "Rental Company";
}else{ //si es LEV WEST
	$labelFecha = "Manufacturer date";
	$labelCompany = "Company";
}
				?>				
					<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables">
						<thead>
							<tr>
								<th class="text-center"><?php echo $labelCompany; ?></th>
								<th class="text-center">Photo</th>
								<th class="text-center">Make</th>
								<th class="text-center">Model</th>
								<th class="text-center">Description</th>
								<th class="text-center">Unit Number</th>
								<th class="text-center">VIN Number</th>
								<th class="text-center">Hours/Kilometers</th>
								<th class="text-center"><?php echo $labelFecha; ?></th>
								<th class="text-center">Type</th>
							</tr>
						</thead>
						<tbody>							
						<?php
							foreach ($info as $lista):
							
									echo "<tr>";
									echo "<td class='text-center'>" . $lista['company_name'];
									
									if(!$deshabilitar){
						?>			<br>
									<button type="button" class="btn btn-outline btn-primary btn-xs" data-toggle="modal" data-target="#modal" id="<?php echo $companyType . '-' . $lista['id_vehicle']; ?>" >
										Edit <span class="glyphicon glyphicon-edit" aria-hidden="true">
									</button>
						<?php
									}
									echo "</td>";
									echo "<td class='text-center'>";
						//si hay una foto la muestro
						if($lista["photo"]){
						?>
							<img src="<?php echo base_url($lista["photo"]); ?>" class="img-rounded" width="42" height="42" />
						<?php } 
						
									if(!$deshabilitar){
						?>
									<br><a href="<?php echo base_url("admin/photo/" . $lista['id_vehicle']); ?>" class="btn btn-primary btn-xs">Photo</a>
						<?php
									}
									echo "</td>";

									echo "<td>" . $lista['make'] . "</td>";
									echo "<td>" . $lista['model'] . "</td>";
									echo "<td>";
									echo $lista['description'];
									if($lista['so_blocked'] == 2){
										echo '<p class="text-danger"><i class="fa fa-flag fa-fw"></i><b> Blocked by SO</b></p>';
									}
									echo "</td>";
									echo "<td class='text-center'><p class='text-danger'><strong>" . $lista['unit_number'] . "</strong></p></td>";
									echo "<td class='text-center'><p class='text-danger'><strong>" . $lista['vin_number'] . "</strong></p></td>";									
									echo "<td class='text-right'><p><strong>" . number_format($lista["hours"]) . "</strong>";
									echo "</p></td>";

									echo "<td class='text-center'>" . $lista['manufacturer_date'] . "</td>";
									echo "<td class='text-center'>";
									switch ($lista['type_level_1']) {
										case 1:
											$type = 'Fleet';
											break;
										case 2:
											$type = 'Rental';
											break;
										case 99:
											$type = 'Other';
											break;
									}
									echo $type . " - " . $lista['type_2'] . "</td>";
									echo "</tr>";

							endforeach;
						?>
						</tbody>
					</table>
				<?php } ?>
				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<!-- /.row -->
</div>
<!-- /#page-wrapper -->
		
				
<!--INICIO Modal para adicionar HAZARDS -->
<div class="modal fade text-center" id="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">    
	<div class="modal-dialog" role="document">
		<div class="modal-content" id="tablaDatos">

		</div>
	</div>
</div>                       
<!--FIN Modal para adicionar HAZARDS -->

<!-- Tables -->
<script>
$(document).ready(function() {
	$('#dataTables').DataTable({
		responsive: true,
		"pageLength": 25
	});
});
</script>
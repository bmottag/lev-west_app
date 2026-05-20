<script>
$(function(){ 
					
	$(".btn-purpura").click(function () {	
			var oID = $(this).attr("id");
            $.ajax ({
                type: 'POST',
				url: base_url + 'admin/cargarModalOilChange',
                data: {'idVehicle': oID},
                cache: false,
                success: function (data) {
                    $('#tablaOilChange').html(data);
                }
            });
	});

});
</script>

<div id="page-wrapper">
	<br>
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h4 class="list-group-item-heading">
					<i class="fa fa-gear fa-fw"></i> VEHICLE
					</h4>
				</div>
			</div>
		</div>
		<!-- /.col-lg-12 -->				
	</div>
	
	<!-- /.row -->
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<a class="btn btn-success" href=" <?php echo base_url().'admin/vehicle/'.$vehicleInfo[0]["type_level_1"]; ?> "><span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Go back </a> 
					<i class="fa fa-automobile"></i> VEHICLE INSPECTIONS<!--NEXT OIL CHANGE -->
				</div>
				<div class="panel-body">
					<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables">
						<thead>
							<tr>
								<th>Photo</th>
								<th>Make</th>
								<th>Model</th>
								<th>Description</th>
								<th>Unit Number</th>
								<th>VIN Number</th>
								<th>Hours/Kilometers</th>
							</tr>
						</thead>
						<tbody>							
						<?php
								echo "<tr>";
								echo "<td class='text-center'>";
						//si hay una foto la muestro
						if($vehicleInfo[0]["photo"]){
						?>
							<img src="<?php echo base_url($vehicleInfo[0]["photo"]); ?>" class="img-rounded" width="32" height="32" />
						<?php } 
								echo "</td>";
								echo "<td class='text-center'>" . $vehicleInfo[0]["make"] . "</td>";
								echo "<td class='text-center'>" . $vehicleInfo[0]["model"] . "</td>";
								echo "<td>" . $vehicleInfo[0]["description"] . "</td>";
								echo "<td class='text-center'>" . $vehicleInfo[0]["unit_number"] . "</td>";
								echo "<td class='text-center'>" . $vehicleInfo[0]["vin_number"] . "</td>";
								
								$diferencia = $vehicleInfo[0]["oil_change"] - $vehicleInfo[0]["hours"];
								$class = "";
								if($diferencia <= 50){
									$class = "danger";
								}

								$tipo = $vehicleInfo[0]['type_level_2'];
								
								//si es hydrovac o sweeper
								if($tipo == 15 || $tipo == 16){
								
									$diferencia2 = $vehicleInfo[0]["oil_change_2"] - $vehicleInfo[0]["hours_2"];
									if($diferencia2 <= 50){
										$class = "danger";
									}
									
									//si es hydrovac
									if($tipo == 16){
										$diferencia3 = $vehicleInfo[0]["oil_change_3"] - $vehicleInfo[0]["hours_3"];
										if($diferencia3 <= 50){
											$class = "danger";
										}
									}
								}
								
								echo "<td class='text-right " . $class . "'><p class='text-" . $class . "'><strong>"; 
								
								//si es sweeper
								if($tipo == 15){
									echo "Truck engine current hours: " . number_format($vehicleInfo[0]["hours"]);
									echo "<br>Sweeper engine current hours: " . number_format($vehicleInfo[0]["hours_2"]);
								//si es hydrovac
								}elseif($tipo == 16){
									echo "Engine current hours: " . number_format($vehicleInfo[0]["hours"]);
									echo "<br>Hydraulic pump current hours: " . number_format($vehicleInfo[0]["hours_2"]);
									echo "<br>Blower current hours: " . number_format($vehicleInfo[0]["hours_3"]);
								}else{
									echo number_format($vehicleInfo[0]["hours"]);
								}
								
								echo "</strong></p></td>";
								
/**
SE ELIMINA ESTA OPCION
								echo "<td class='text-right " . $class . "'><p class='text-" . $class . "'><strong>"; 
								
								//si es sweeper
								if($tipo == 15){
									echo number_format($vehicleInfo[0]["oil_change"]);
									echo "<br>" . number_format($vehicleInfo[0]["oil_change_2"]);
								//si es hydrovac
								}elseif($tipo == 16){
									echo number_format($vehicleInfo[0]["oil_change"]);
									echo "<br>" . number_format($vehicleInfo[0]["oil_change_2"]);
									echo "<br>" . number_format($vehicleInfo[0]["oil_change_3"]);
								}else{
									echo number_format($vehicleInfo[0]["oil_change"]);
								}
								
								echo "</strong></p></td>";
								
*/
								
								echo "</tr>";
						?>
						</tbody>
					</table>

<!--
					
					<button type="button" class="btn btn-purpura btn-block" data-toggle="modal" data-target="#modalOilChange" id="<?php echo $idVehicle; ?>">
							<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add a Next Oil Change
					</button><br>
					
-->

<?php
$retornoExito = $this->session->flashdata('retornoExito');
if ($retornoExito) {
    ?>
	<div class="col-lg-12">	
		<div class="alert alert-success ">
			<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
			<?php echo $retornoExito ?>		
		</div>
	</div>
    <?php
}

$retornoError = $this->session->flashdata('retornoError');
if ($retornoError) {
    ?>
	<div class="col-lg-12">	
		<div class="alert alert-danger ">
			<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
			<?php echo $retornoError ?>
		</div>
	</div>
    <?php
}
?> 

				<?php
					if($info){
				?>				
					<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables">
						<thead>
							<tr>
								<th class="text-center">Date of Issue</th>
								<th class="text-center">Employee</th>
								<th class="text-center">Hours/Kilometers</th>
								<!--
								<th class="text-center">Next Oil Change</th>
								-->
								<th class="text-center">State</th>
							</tr>
						</thead>
						<tbody>							
						<?php
							foreach ($info as $lista):
									echo "<tr>";
									echo "<td class='text-center'>" . $lista['date_issue'] . "</td>";
									echo "<td class='text-center'>" . $lista['name'] . "</td>";
									
									echo "<td class='text-right'>";

									//si es sweeper
									if($tipo == 15){
										echo "Truck engine current hours: " . number_format($lista["current_hours"]);
										echo "<br>Sweeper engine current hours: " . number_format($lista["current_hours_2"]);
									//si es hydrovac
									}elseif($tipo == 16){
										echo "Engine current hours: " . number_format($lista["current_hours"]);
										echo "<br>Hydraulic pump current hours: " . number_format($lista["current_hours_2"]);
										echo "<br>Blower current hours: " . number_format($lista["current_hours_3"]);
									}else{
										echo number_format($lista["current_hours"]);
									}
									
									echo "</td>";
									
/**
	ESTO SE ELIMINO								
									echo "<td class='text-right'>";
																		
									//si es sweeper
									if($tipo == 15){
										echo number_format($lista["next_oil_change"]);
										echo "<br>" . number_format($lista["next_oil_change_2"]);
									//si es hydrovac
									}elseif($tipo == 16){
										echo number_format($lista["next_oil_change"]);
										echo "<br>" . number_format($lista["next_oil_change_2"]);
										echo "<br>" . number_format($lista["next_oil_change_3"]);
									}else{
										echo number_format($lista["next_oil_change"]);
									}
									
									echo "</td>";
*/									

									echo "<td class='text-center'>";
									switch ($lista['state']) {
										case 0:
											echo "First Record";
											break;
										case 1:
											echo "Inspection";
											break;
										case 2:
											echo "Oil Change";
											break;
									}
									
									echo "</td>";
									
									
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

<!--INICIO Modal OIL CHANGE -->
<div class="modal fade text-center" id="modalOilChange" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">    
	<div class="modal-dialog" role="document">
		<div class="modal-content" id="tablaOilChange">

		</div>
	</div>
</div>                       
<!--FIN Modal para OIL CHANGE  -->

    <!-- Tables -->
    <script>
    $(document).ready(function() {
        $('#dataTables').DataTable({
            responsive: true,
			 "ordering": false,
			 paging: false,
			"searching": false,
			"info": false
        });
    });
    </script>
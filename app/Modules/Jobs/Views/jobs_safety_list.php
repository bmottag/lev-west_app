<div id="page-wrapper">
	<br>
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-info">
				<div class="panel-heading">
					<a class="btn btn-info btn-xs" href=" <?php echo base_url($dashboardURL); ?> "><span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Go to Dashboard </a> 
					<i class="fa fa-life-saver"></i> <b>SAFETY</b>
				</div>
				<div class="panel-body">

				<?php
					if($info){
				?>				
					<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables">
						<thead>
							<tr>
								<th class="text-left">Job Code/Name</th>
								<th class="text-center">Links</th>
							</tr>
						</thead>
						<tbody>							
						<?php
							foreach ($info as $lista):
								echo "<tr>";
								echo "<td><h4>" . $lista['job_description'] . "</h4></td>";
								echo "<td class='text-center'>";
								
								$userRol = session()->rol;
						?>
									<a class='btn btn-warning btn-xs' href='<?php echo base_url('jobs/tool_box/' . $lista['id_job']) ?>'>
										IHSR <span class="fa fa-cube" aria-hidden="true">
									</a>									
						
						<?php
								if($userRol != ID_ROL_BASIC){ //Para BASIC no se muestra el enlace
						?>
									<a class='btn btn-success primary btn-xs' href='<?php echo base_url('jobs/erp/' . $lista['id_job']) ?>'>
										ERP <span class="fa fa-fire-extinguisher" aria-hidden="true">
									</a>
						<?php
								}
						?>						
									
						<?php
								if($userRol != ID_ROL_SUPERVISOR && $userRol != ID_ROL_BASIC && $userRol != ID_ROL_MECHANIC){ //Para ROL SUPERVISOR Y BASIC no se muestra el enlace
						?>
									<a class='btn btn-danger btn-xs' href='<?php echo base_url('jobs/hazards/' . $lista['id_job']) ?>'>
										JHA <span class="fa fa-life-saver" aria-hidden="true">
									</a>
						<?php
								}
						?>
									<a class='btn btn-default btn-xs' href='<?php echo base_url('jobs/safety/' . $lista['id_job']) ?>'>
										FLHA <span class="fa fa-life-saver" aria-hidden="true">
									</a>
						<?php
								if($userRol != ID_ROL_BASIC){ //Para BASIC no se muestra el enlace
						?>
									<a class='btn btn-info btn-xs' href='<?php echo base_url('jobs/jso/' . $lista['id_job']) ?>'>
										JSO <span class="fa fa-bullhorn" aria-hidden="true">
									</a>
									<a class='btn btn-purpura btn-xs' href='<?php echo base_url('more/environmental/' . $lista['id_job']) ?>'>
										ESI <span class="glyphicon glyphicon-screenshot" aria-hidden="true">
									</a>
						<?php
								}
						?>
									<a class='btn btn-primary btn-xs' href='<?php echo base_url('jobs/locates/' . $lista['id_job']) ?>'>
										Locates <span class="fa fa-image" aria-hidden="true">
									</a>
									<a class='btn btn-warning btn-xs' href='<?php echo base_url('more/confined/' . $lista['id_job']) ?>'>
										CSEP <span class="fa fa-database" aria-hidden="true">
									</a>
											
							<!--
									<a class='btn btn-success btn-xs' href='<?php echo base_url('more/task_control/' . $lista['id_job']) ?>'>
										COVID <span class="fa fa-bug" aria-hidden="true">
									</a>
							-->
						<?php
								if($userRol != ID_ROL_BASIC){ //Para BASIC no se muestra el enlace
						?>
									<a class='btn btn-danger btn-xs' href='<?php echo base_url('jobs/excavation/' . $lista['id_job']) ?>'>
										ETP <span class="fa fa-pied-piper-alt" aria-hidden="true">
									</a>
						<?php
								}
						?>

									<a class='btn btn-dark btn-xs' href='<?php echo base_url('jobs/fire_watch/' . $lista['id_job']) ?>'>
										Fire Watch <span class="fa fa-fire" aria-hidden="true">
									</a>
									<a class='btn btn-violeta btn-xs' href='<?php echo base_url('jobs/bitacora/' . $lista['id_job']) ?>'>
										Bitacora <span class="fa fa-umbrella" aria-hidden="true">
									</a>

									<a class='btn btn-success btn-xs' href='<?php echo base_url('incidences/near_miss/' . $lista['id_job']) ?>'>
										Near Miss Report <span class="fa fa-ambulance" aria-hidden="true">
									</a>
									<a class='btn btn-danger btn-xs' href='<?php echo base_url('incidences/incident/' . $lista['id_job']) ?>'>
										Incident/Accident Report  <span class="fa fa-ambulance" aria-hidden="true">
									</a>
									
						<?php
									echo "</td>";
							endforeach;
						?>
						</tbody>
					</table>
				<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Tables -->
<script>
$(document).ready(function() {
	$('#dataTables').DataTable({
		responsive: true,
		"pageLength": 100
	});
});
</script>
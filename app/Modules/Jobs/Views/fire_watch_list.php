<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>
$(function(){ 
	$(".btn-setup").click(function () {
			var idJob = $('#hddIdJob').val();
			var oID = $(this).attr("id");
            $.ajax ({
                type: 'POST',
				url: base_url + 'jobs/cargarModalFireWatchSetup',
				data: {"idJob": idJob, 'metodo': oID },
                cache: false,
                success: function (data) {
                    $('#tablaSetup').html(data);
                }
            });
	});	

	$(".btn-firewatch").click(function () {
			var idJob = $('#hddIdJob').val();	
			var oID = $(this).attr("id");
            $.ajax ({
                type: 'POST',
				url: base_url + 'jobs/cargarModalFireWatch',
				data: {"idJob": idJob, 'idFireWatch': oID },
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
					<a class="btn btn-primary btn-xs" href=" <?php echo base_url().'jobs'; ?> "><span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Go back </a> 
					<i class="fa fa-fire"></i> <strong>FIRE WATCH</strong>
				</div>
				<div class="panel-body">
				
					<div class="alert alert-info">
						<span class="fa fa-briefcase" aria-hidden="true"></span>
						<strong>Job Code/Name: </strong><?php echo $jobInfo[0]['job_description']; ?><br>
						<?php
							if($infoFireWatchSetup){
								$metodo = "update";
							}else{
								$metodo = "create";
							}
						?>
						<button type="button" class="btn btn-primary btn-setup" data-toggle="modal" data-target="#modalSetup" id="<?php echo $metodo; ?>" >
							<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Set Fire Watch Information
						</button>
					</div>		

					<!-- campo para enviar el ID de SAFETY al MODAL -->
					<input type="hidden" id="hddIdJob" name="hddIdJob" value="<?php echo $jobInfo[0]['id_job']; ?>"/>

				<?php
					if(!$infoFireWatchSetup){
						echo '<div class="col-lg-12">
								<p class="text-danger"><span class="glyphicon glyphicon-alert" aria-hidden="true"></span> Set the Fire Watch information first to continue.</p>
							</div>';
					}else{
				?>
					<div class="row">
						<div class="col-lg-12">
							<h3><strong>Facility/Building Address: </strong><?php echo $infoFireWatchSetup[0]["building_address"]; ?></h3>
						</div>
						<div class="col-lg-6">
<?php
$mobile = $infoFireWatchSetup[0]["super_number"];
// Separa en grupos de tres 
$count = strlen($mobile); 
	
$num_tlf1 = substr($mobile, 0, 3); 
$num_tlf2 = substr($mobile, 3, 3); 
$num_tlf3 = substr($mobile, 6, 2); 
$num_tlf4 = substr($mobile, -2); 

if($count == 10){
	$resultado = "$num_tlf1 $num_tlf2 $num_tlf3 $num_tlf4";  
}else{
	
	$resultado = chunk_split($mobile,3," "); 
}

?>

							<strong>Supervisor’s Name: </strong><?php echo $infoFireWatchSetup[0]["supervisor"]; ?> <br>
							<strong> Supervisor’s Contact Number: </strong><?php echo $resultado; ?> <br>
							<strong>Areas/Zones Requiring Fire Watch Patrols: </strong><br>
							<?php echo $infoFireWatchSetup[0]["areas"]; ?>
						</div>
						<div class="col-lg-6">
							<strong>System(s) Out of Service: </strong><?php echo $infoFireWatchSetup[0]["date_out"]; ?><br>
							<strong>System(s) Restored Online: </strong><?php echo $infoFireWatchSetup[0]['date_restored'] == "0000-00-00 00:00:00"?"":$infoFireWatchSetup[0]['date_restored']; ?><br>
							<strong>Systems Shutdown: </strong><br>
							<?php 
								echo $infoFireWatchSetup[0]['fire_alarm']?"Fire Alarm System<br>":"";
								echo $infoFireWatchSetup[0]['fire_sprinkler']?"Fire Sprinkler System<br>":"";
								echo $infoFireWatchSetup[0]['standpipe']?"Standpipe System<br>":"";
								echo $infoFireWatchSetup[0]['fire_pump']?"Fire Pump System<br>":"";
								echo $infoFireWatchSetup[0]['fire_suppression']?"Special Fire Suppression System<br>":"";
								echo $infoFireWatchSetup[0]['other']?$infoFireWatchSetup[0]['other']:"";
							?>
						</div>
					</div>

					<br>
					<button type="button" class="btn btn-primary btn-block btn-firewatch" data-toggle="modal" data-target="#modal" id="x">
							<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add a Fire Watch
					</button><br>
					
					<br>
				<?php
					if($information){
				?>
					<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables">
						<thead>
							<tr>
								<th>Conducted by</th>
								<th>Fire Watch Commenced</th>
								<th>Supervisor</th>
								<th>Facility/Building Address</th>
								<th>System(s) Out of Service</th>
								<th>Systems(s) Restored Online </th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>							
						<?php
							foreach ($information as $lista):
									$dateRestored = $lista['date_restored'] == "0000-00-00 00:00:00"?"":$lista['date_restored'];
									echo "<tr>";
									echo "<td>" . $lista['conductedby'] . "</td>";
									echo "<td>" . $lista['date_commenced'] . "</td>";
									echo "<td>" . $lista['supervisor'] . "</td>";
									echo "<td>" . $lista['building_address'] . "</td>";
									echo "<td>" . $lista['date_out'] . "</td>";
									echo "<td>" . $dateRestored. "</td>";
									echo "<td class='text-center'>";									
						?>					
						
						<!--
									<button type="button" class="btn btn-primary btn-outline btn-xs btn-firewatch" data-toggle="modal" data-target="#modal" id="<?php echo $lista['id_job_fire_watch']; ?>" title="Edit" >
										<i class='fa fa-pencil'></i>
									</button>
						-->
									<a class='btn btn-primary btn-xs' href='<?php echo base_url('jobs/fire_watch_checkin/' . $lista['id_job_fire_watch'] ) ?>' title="Log Sheet">
										Log Sheet <span class="glyphicon glyphicon-edit" aria-hidden="true">
									</a>

									<a class='btn btn-primary btn-xs' href='<?php echo base_url('jobs/generaFIREWATCHPDF/' . $lista['id_job_fire_watch'] ) ?>' title="Download Fire Watch Record" target="_blank">
										<span class="fa fa-cloud-download" aria-hidden="true">
									</a>
						<!-- REMOVE THIS OPTION SINCE SEPTEMBER 11 - 2024, Requested by Dennise
									<a class='btn btn-primary btn-xs' href='<?php echo base_url('jobs/generaFIREWATCHLOGPDF/' . $lista['id_job_fire_watch'] ) ?>' title="Download Fire Watch Log Sheet" target="_blank">
										<span class="fa fa-cloud-download" aria-hidden="true">
									</a>
						-->


						<?php
									echo "</td>";
									echo "</tr>";
							endforeach;
						?>
						</tbody>
					</table>
				<?php 
						} 
					}
				?>

				</div>

			</div>
		</div>
	</div>
</div>

<!--INICIO Modal -->
<div class="modal fade text-center" id="modalSetup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">    
	<div class="modal-dialog" role="document">
		<div class="modal-content" id="tablaSetup">

		</div>
	</div>
</div>                       
<!--FIN Modal -->

<!--INICIO Modal -->
<div class="modal fade text-center" id="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">    
	<div class="modal-dialog" role="document">
		<div class="modal-content" id="tablaDatos">

		</div>
	</div>
</div>                       
<!--FIN Modal -->

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
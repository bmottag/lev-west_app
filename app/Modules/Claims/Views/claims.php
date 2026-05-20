<script type="text/javascript" src="<?php echo base_url("assets/js/validate/claims/search.js"); ?>"></script>
<script>
$(function(){ 
	$(".btn-violeta").click(function () {	
			var oID = $(this).attr("id");
            $.ajax ({
                type: 'POST',
				url: base_url + 'claims/cargarModalClaim',
                data: {'idJob': oID},
                cache: false,
                success: function (data) {
                    $('#tablaDatos').html(data);
                }
            });
	});	
});

	$(document).ready(function() {
		$('#modal').on('shown.bs.modal', function() {
			if ($.fn.select2 && $('#id_job').hasClass("select2-hidden-accessible")) {
				$('#id_job').select2('destroy');
			}
			$('#id_job').select2({
				dropdownParent: $('#modal')
			});
		});
	});
</script>

<div id="page-wrapper">
	<br>
	
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-violeta">
				<div class="panel-heading">
					<a class="btn btn-violeta btn-xs" href=" <?php echo base_url('admin/job/1'); ?> "><span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Go back </a> 
					<i class="fa fa-bomb"></i> <strong><?php echo $tituloListado; ?></strong>
				</div>
				<div class="panel-body">

					<div class="alert alert-violeta">
						<span class="fa fa-briefcase" aria-hidden="true"></span>
						<strong>Job Code/Name: </strong><?php echo $jobInfo[0]['job_description']; ?>
					</div>	
					
					<button type="button" class="btn btn-violeta btn-block" data-toggle="modal" data-target="#modal" id="<?php echo $jobInfo[0]['id_job']; ?>">
							<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add a Claim
					</button>					
					<br>
				<?php 										
					if(!$claimsInfo){ 
						echo '<div class="col-lg-12">
								<p class="text-danger"><span class="glyphicon glyphicon-alert" aria-hidden="true"></span> There are no records in the system.</p>
							</div>';
					}else{
				?>
					<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables">
						<thead>
							<tr>
								<th class='text-center'>Claim #</th>
								<th class='text-center'>Ventures Code</th>
								<th class='text-center'>User</th>
								<th class='text-center'>Date of Issue</th>
								<th class='text-center'>More information</th>
							</tr>
						</thead>
						<tbody>							
						<?php
							foreach ($claimsInfo as $lista):
							
									switch ($lista['current_status_claim']) {
											case 1:
													$valor = 'New Claim';
													$clase = "text-violeta";
													$icono = "fa-flag";
													break;
											case 2:
													$valor = 'Send to client';
													$clase = "text-success";
													$icono = "fa-share";
													break;
											case 3:
													$valor = 'Partial Payment';
													$clase = "text-primary";
													$icono = "fa-star-half-empty";
													break;
											case 4:
													$valor = 'Hold Back';
													$clase = "text-warning";
													$icono = "fa-bullhorn";
													break;
											case 5:
													$valor = 'Short Payment';
													$clase = "text-warning";
													$icono = "fa-thumbs-o-down";
													break;
											case 6:
													$valor = 'Final Payment';
													$clase = "text-danger";
													$icono = "fa-bomb";
													break;
									}
							
									echo "<tr>";
									echo "<td class='text-center'>";
									echo "<a href='" . base_url('claims/upload_apu/' . $lista['id_claim']) . "'>" . $lista['claim_number'] . "</a>";
									echo '<p class="' . $clase . '"><i class="fa ' . $icono . ' fa-fw"></i>' . $valor . '</p>';
									echo "<a href='" . base_url('claims/upload_apu/' . $lista['id_claim']) . "' class='btn btn-success btn-xs' title='View'>Review Claim</a>";
									echo "</td>";
									echo "<td>" . $lista['job_description'] . "</td>";
									echo "<td>" . $lista['name'] . "</td>";
									echo "<td class='text-center'>" . $lista['date_issue_claim'] . "</td>";
									echo '<td>';
									echo '<strong>Observation:</strong><br>' . $lista['observation_claim'];
									echo '<p class="text-info"><strong>Additional information last message:</strong><br>' . $lista['last_message_claim'] . '</p>';
									echo '</td>';
									echo "</tr>";
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

<!--INICIO Modal para adicionar CLAIMS -->
<div class="modal fade text-center" id="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">    
	<div class="modal-dialog" role="document">
		<div class="modal-content" id="tablaDatos">

		</div>
	</div>
</div>                       
<!--FIN Modal para adicionar CLAIMS -->

<script>
$(document).ready(function() {
    $('#dataTables').DataTable({
        responsive: true,
		 "ordering": false,
		 paging: false,
		"searching": false,
		"info": false
    });

	$('.js-example-basic-single').select2({
		width: '100%'
	});
});
</script>
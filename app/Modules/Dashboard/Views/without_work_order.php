<div id="page-wrapper">
	<br>

	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-danger">
				<div class="panel-heading">
					<a class="btn btn-danger btn-xs" href=" <?php echo base_url($dashboardURL); ?> "><span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Go back </a>
					<i class="fa fa-wrench fa-fw"></i> <strong>LIST OF HOURS WITHOUT WO </strong>
				</div>
				<div class="panel-body">
					<?php
					if ($infoTask) {
					?>
						<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables">
							<thead>
								<tr>
									<th class="text-center">Actions</th>
									<th class="text-center">Employee</th>
									<th class="text-center">Job Code/Name</th>
									<th class="text-center">Hours</th>
									<th class="text-center">Date</th>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach ($infoTask as $task):
									echo "<tr>";
									echo "<td class='text-center'><button type='button' class='btn btn-danger btn-sm' data-toggle='modal' id='btnAssign_" . $task["id_task"] . "'>Assign WO</button></td>";
									echo "<td class='text-center'><p class='text-danger'><strong>" . $task["first_name"] . ' ' . $task["last_name"] . "</strong></p></td>";
									echo "<td class='text-center'><p class='text-danger'><strong>" . $task["job_description"] . "</strong></p></td>";
									echo "<td class='text-center'><p class='text-danger'><strong>" . $task["hours_end_project"] . "</strong></p></td>";
									echo "<td class='text-center'><p class='text-danger'><strong>" . date('Y-m-d', strtotime($task["finish"])) . "</strong></p></td>";
									echo "</tr>";
								endforeach;
								?>
							</tbody>
						</table>
					<?php } else { ?>
						<div class="alert alert-danger">
							No free hours
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>

<!--INICIO Modal para adicionar WORKER -->
<div class="modal fade text-center" id="modalWorker" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content" id="tablaDatos">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="exampleModalLabel">ADD WORKER</h4>
			</div>
			<div class="modal-body">
				<h5 id="modalMessage"></h5> <!-- Mensaje que se mostrará -->
			</div>
			<div class="modal-footer">
				<button type="button" id="btnModalAssign" class="btn btn-primary">Asignar</button> <!-- Botón para asignar -->
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
</div>
<!--FIN Modal para adicionar WORKER -->


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

	$('[id^=btnAssign_]').click(function() {
		var taskId = $(this).attr('id').split('_')[1];
		$.ajax({
			type: 'POST',
			url: base_url + 'dashboard/modalListWo',
			data: {
				'taskId': taskId
			},
			success: function(response) {

				if (response) {
					$('#modalMessage').text('Asignar las horas a la WO ' + response.id_workorder);
					$('#btnModalAssign').show();
				} else {
					$('#modalMessage').text('Sin WO para la fecha');
					$('#btnModalAssign').hide();
				}
				$('#modalWorker').modal('show');
			},
			error: function() {
				alert('Error al cargar los datos.');
			}
		});
	});

	// Manejo del botón "Asignar"
	$('#btnModalAssign').click(function() {
		var messageText = $('#modalMessage').text();
		var woID = messageText.split('WO ')[1];
		var taskId = $('[id^=btnAssign_]:visible').attr('id').split('_')[1];

		$.ajax({
			type: 'POST',
			url: base_url + 'dashboard/assignHoursWo',
			data: {
				'taskId': taskId,
				'woID': woID
			},
			success: function(response) {
				$('#modalWorker').modal('hide');
				window.location.reload();
			},
			error: function(xhr, status, error) {
				console.error(error);
				alert('Error al asignar horas: ' + xhr.status + ' - ' + xhr.statusText);
			}
		});
	});
</script>
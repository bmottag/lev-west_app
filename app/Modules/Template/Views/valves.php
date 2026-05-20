<script>
$(function(){ 
	$(".btn-success").click(function () {	
			var oID = $(this).attr("id");
            $.ajax ({
                type: 'POST',
				url: base_url + '/template/cargarModalValve',
                data: {'idValve': oID},
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
					<i class="fa fa-stumbleupon"></i> <B>WATER MAIN VALVE EXERSIZE</B>
				</div>
				<div class="panel-body">
					<button type="button" class="btn btn-success btn-block" data-toggle="modal" data-target="#modal" id="x">
							<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add a Valve
					</button><br>
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
					if($info){
				?>	
					<div class="row">	
						<div class="col-sm-12">
							<a class='btn btn-danger' href='<?php echo base_url('report/valvesReport/'); ?>' >
								<span class="fa fa-file-excel-o" aria-hidden="true"></span> Valves Report
							</a>
						</div>
					</div>
				<br>
					<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables">
						<thead>
							<tr>
								<th class="text-center">Date Issue</th>
								<th class="text-center">Valve #'s</th>
								<th class="text-center"># of Turns</th>
								<th class="text-center">Position that valve was found</th>
								<th class="text-center">Condition</th>
								<th class="text-center">The direction of the turn for operation</th>
								<th class="text-center">Rewarks</th>
							</tr>
						</thead>
						<tbody>							
						<?php
							foreach ($info as $lista):
									echo "<tr>";
									echo "<td class='text-center'>" . $lista['date_issue'];
									?>
									<br>
												<button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#modal" id="<?php echo $lista['id_valve']; ?>" >
													Edit <span class="glyphicon glyphicon-edit" aria-hidden="true">
												</button>
									<?php
									echo "</td>";
									echo "<td>" . $lista['valve_number'] . "</td>";
									echo "<td>" . $lista['number_of_turns'] . "</td>";
									echo "<td>" . $lista['position'] . "</td>";
									echo "<td>" . $lista['status'] . "</td>";
									echo "<td>" . $lista['direction'] . "</td>";
									echo "<td>" . $lista['rewarks'] . "</td>";
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
		"pageLength": 50,
		"order": [[ 0, "desc" ]]
	});
});
</script>
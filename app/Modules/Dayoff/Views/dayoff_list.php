<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>
$(function(){ 
	$(".btn-success").click(function () {	
            $.ajax ({
                type: 'POST',
				url: base_url + '/dayoff/cargar-modal',
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
			<div class="panel panel-success">
				<div class="panel-heading">
					<i class="fa fa-clock-o"></i> <b>DAY OFF LIST</b>
				</div>
				<div class="panel-body">
					<button type="button" class="btn btn-success btn-block" data-toggle="modal" data-target="#modal">
							<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Ask for a Day Off
					</button><br>
				<?php
					if($dayoffList){
				?>				
					<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables">
						<thead>
							<tr>
								<th class="text-center">Date of issue</th>
								<th class="text-center">Type</th>
								<th class="text-center">Date of day off</th>
								<th class="text-center">Observation</th>
								<th class="text-center">Status</th>
								<th class="text-center">Admin Observation</th>
							</tr>
						</thead>
						<tbody>							
						<?php
							foreach ($dayoffList as $lista):
									echo "<tr>";
									echo "<td class='text-center'>" . $lista['date_issue'] . "</td>";
									echo "<td class='text-center'>";
									switch ($lista['id_type_dayoff']) {
											case 1:
												echo 'Family/medical appointment';
												break;
											case 2:
												echo 'Regular';
												break;
									}
									echo "</td>";
									echo "<td class='text-center'>" . $lista['date_dayoff'] . "</td>";
									echo "<td>" . $lista['observation'] . "</td>";
									echo "<td class='text-center'>";
									switch ($lista['state']) {
											case 1:
													$valor = 'New Request';
													$clase = "text-primary";
													break;
											case 2:
													$valor = 'Approved';
													$clase = "text-success";
													break;
											case 3:
													$valor = 'Denied';
													$clase = "text-danger";
													break;
									}
									echo '<p class="' . $clase . '"><strong>' . $valor . '</strong></p>';
									echo "</td>";
									echo "<td>" . $lista['admin_observation'] . "</td>";
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
		"ordering": false
	});
});
</script>
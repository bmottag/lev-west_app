<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>
$(function(){ 
	
	$(".btn-success").click(function () {	
			var oID = $(this).attr("id");
            $.ajax ({
                type: 'POST',
				url: base_url + '/dayoff/cargar-modal-approved',
                data: {'idDayoff': oID},
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
			<div class="panel panel-default">
				<div class="panel-heading">
					<i class="fa <?php echo $icon ?>"></i> <b>DAY OFF LIST - <?php echo $tittle; ?></b>
				</div>
				<div class="panel-body">
					<div class="alert alert-info">
						<strong>Note:</strong> 
						<?php 
							switch ($state) 
							{
								case 1:
									echo 'You have to approve or deny the following request';
									break;
								case 2:
									echo 'List of approved dayoff request';
									break;
								case 3:
									echo 'List of denied dayoff request';
									break;
							}
						?>
					</div>
					
    <?php
	$session = session();
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
					if($dayoffList){
				?>
						<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables">
							<thead>
								<tr>
									<th class="text-center">Employee</th>
									<th class="text-center">Date of issue</th>
									<th class="text-center">Update Status</th>
									<th class="text-center">Type</th>
									<th class="text-center">Date of day off</th>
									<th class="text-center">Observation</th>
									<th class="text-center">Actual Status</th>
									
								</tr>
							</thead>
							<tbody>							
							<?php
								foreach ($dayoffList as $lista):
									echo "<tr>";
										echo "<td class='text-center'>" . $lista['name'] . "</td>";
										echo "<td class='text-center'>" . $lista['date_issue'] . "</td>";
										echo "<td class='text-center'>";
									
										$deshabilitar = "";
										if($lista['state'] != 1 ){ $deshabilitar = "disabled";}
							?>
									<button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal" id="<?php echo $lista['id_dayoff']; ?>" <?php echo $deshabilitar;  ?> title="Update Status" >
										<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
									</button>
							<?php
										echo "</td>";
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
		
				
<!--INICIO Modal para aprobar/negar DAY OFF -->
<div class="modal fade text-center" id="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">    
	<div class="modal-dialog" role="document">
		<div class="modal-content" id="tablaDatos">

		</div>
	</div>
</div>                       
<!--FIN Modal para adicionar aprobar/negar DAY OFF -->

<!-- Tables -->
<script>
$(document).ready(function() {
	$('#dataTables').DataTable({
		responsive: true,
		"pageLength": 25
	});
});
</script>
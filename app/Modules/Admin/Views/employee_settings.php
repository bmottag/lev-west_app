<div id="page-wrapper">
	<br>
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h4 class="list-group-item-heading">
					<a class="btn btn-primary btn-xs" href=" <?php echo base_url($dashboardURL); ?> "><span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Go to Dashboard </a> 
					<i class="fa fa-gear fa-fw"></i> <b>SETTINGS - EMPLOYEE SETTINGS</b>
					</h4>
				</div>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<i class="fa fa-flag-o"></i> <b>EMPLOYEE SETTINGS</b>
				</div>
				<div class="panel-body">

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

<form  name="employee_rate" id="employee_rate" method="post" action="<?php echo base_url("admin/update_employee_rate"); ?>">
				
					<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables">
						<thead>
							<tr>
								<th class="text-right" colspan="7">
								<button type="submit" class="btn btn-primary" id="btnSubmit2" name="btnSubmit2" >
									Update Employee Information <span class="glyphicon glyphicon-edit" aria-hidden="true">
								</button>
								</th>
							</tr>
							<tr>
								<th class="text-center">ID</th>
								<th class="text-center">Name</th>
								<th class="text-center">Employee Information</th>
								<th class="text-center">Employee Hour Rate</th>
								<th class="text-center">Employee Type</th>
								<th class="text-center">Is it a Subcontractor?</th>
								<th class="text-center">Is it using bank time?</th>
							</tr>
						</thead>
						<tbody>							
						<?php
							foreach ($info as $lista):
								switch ($lista['state']) {
									case 0:
											$valor = 'New User';
											$clase = "text-primary";
											break;
									case 1:
										$valor = 'Active';
										$clase = "text-success";
										break;
									case 2:
										$valor = 'Inactive';
										$clase = "text-danger";
										break;
								}
								echo "<tr>";
								echo "<td class='text-center'>" . $lista['id_user'] . "</td>";
								echo "<td class='text-center'><b>" . $lista['first_name'] . ' ' . $lista['last_name'] . "</b>";

								echo '<p class="' . $lista['estilos'] . '"><strong>Rol: ' . $lista['rol_name'] . '</strong></p>';
								echo '<p class="' . $clase . '"><strong>' . $valor . '</strong></p>';					
								echo "</td>";

$movil = $lista["movil"];
// Separa en grupos de tres 
$count = strlen($movil); 
	
$num_tlf1 = substr($movil, 0, 3); 
$num_tlf2 = substr($movil, 3, 3); 
$num_tlf3 = substr($movil, 6, 2); 
$num_tlf4 = substr($movil, -2); 

if($count == 10){
	$resultado = "$num_tlf1 $num_tlf2 $num_tlf3 $num_tlf4";  
}else{
	
	$resultado = chunk_split($movil,3," "); 
}
								
							
								echo "<td><small>";
								echo "<b>Movil: </b>" . $resultado . "<br>";
								echo "<b>Email: </b>" . $lista['email']  . "<br>";
								echo "<b>DOB: </b>" . $lista['birthdate']  . "<br>";
								echo "<b>SIN: </b>" . chunk_split($lista['social_insurance'],3," ") . "<br>";
								echo "<b>Health number: </b>" . chunk_split($lista['health_number'],3," ") . "<br>";
								echo "<b>Address: </b>" . $lista['address'] . "<br>";
								echo "</small></td>";
									
								$unitPrice = $lista['employee_rate'];
								$unitPrice = $unitPrice?$unitPrice:0;
								echo "<td class='text-right'>";
					?>
					<input type="hidden" name="form[id][]" value="<?php echo $lista['id_user']; ?>"/>
					$ <input type="text" name="form[employee_rate][]" class="form-control" placeholder="Employee Hour Rate" value="<?php echo $unitPrice; ?>" >
					<?php
								echo "</td>";
								echo "<td class='text-center'>";
					?>
								<select name="form[type][]" class="form-control" >
									<option value=''>Select...</option>
									<option value=1 <?php if($lista["employee_type"] == 1) { echo "selected"; }  ?>>Field</option>
									<option value=2 <?php if($lista["employee_type"] == 2) { echo "selected"; }  ?>>Admin</option>
								</select>
						<?php
								echo "</td>";
								echo "<td class='text-center'>";
						?>
								<select name="form[employee_subcontractor][]" class="form-control" >
									<option value=''>Select...</option>
									<option value=1 <?php if($lista["employee_subcontractor"] == 1) { echo "selected"; }  ?>>Yes</option>
									<option value=2 <?php if($lista["employee_subcontractor"] == 2) { echo "selected"; }  ?>>No</option>
								</select>
						<?php
								echo "</td>";
								echo "<td class='text-center'>";
						?>
								<select name="form[bank_time][]" class="form-control" >
									<option value=''>Select...</option>
									<option value=1 <?php if($lista["bank_time"] == 1) { echo "selected"; }  ?>>Yes</option>
									<option value=2 <?php if($lista["bank_time"] == 2) { echo "selected"; }  ?>>No</option>
								</select>
						<?php
								if($lista["bank_time"] == 1){
						?>		

						<?php
								}
								echo "</td>";
								echo "</tr>";
							endforeach;
						?>
						</tbody>
					</table>
					
</form>
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
		"order": [[ 1, "asc" ]],
		"pageLength": 100,
		"columnDefs": [
		  { "orderable": false, "targets": 2 },
		  { "orderable": false, "targets": 3 },
		  { "orderable": false, "targets": 4 },
		  { "orderable": false, "targets": 5 },
		  { "orderable": false, "targets": 6 },
		]
	});
});
</script>
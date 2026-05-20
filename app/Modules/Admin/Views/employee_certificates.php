<script type="text/javascript" src="<?php echo base_url("assets/js/validate/admin/employee_certificate.js"); ?>"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>
$(function(){ 
	$(".btn-success").click(function () {	
			var oID = $(this).attr("id");
            $.ajax ({
                type: 'POST',
				url: base_url + 'admin/cargar-modal-user-certificate',
                data: {'idEmployee': oID},
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
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h4 class="list-group-item-heading">
					<i class="fa fa-gear fa-fw"></i> SETTINGS - EMPLOYEE
					</h4>
				</div>
			</div>
		</div>
		<!-- /.col-lg-12 -->				
	</div>

	<!-- /.row -->
	<div class="row">
		<div class="col-lg-8">
			<div class="panel panel-default">
				<div class="panel-heading">
					<a class="btn btn-default btn-xs" href="<?php echo base_url('admin/employee/1'); ?> "><span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Go back </a> 
					<i class="fa fa-link"></i> <b><?php echo $UserInfo[0]['first_name'] . ' ' . $UserInfo[0]['last_name']; ?></b> - CERTIFICATES
				</div>
				<div class="panel-body">

					<button type="button" class="btn btn-success btn-block" data-toggle="modal" data-target="#modal" id="<?php echo $UserInfo[0]["id_user"]; ?>">
							<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add Certificate
					</button><br>
				
<?php
$session = session();
// Mensaje de éxito
if ($session->getFlashdata('retornoExito')): ?>
	<div class="row">
		<div class="col-lg-12">
			<div class="alert alert-success">
				<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
				<?php echo $session->getFlashdata('retornoExito') ?>
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
				<?php echo $session->getFlashdata('retornoError') ?>
			</div>
		</div>
	</div>
<?php endif; ?>

				<?php 										
					if(!$info){ 
						echo '<div class="col-lg-12">
								<p class="text-danger"><span class="glyphicon glyphicon-alert" aria-hidden="true"></span> No data was found.</p>
							</div>';
					}else{
				?>		

					<div class="row">
						<div class="col-lg-12">
							<div class="alert alert-danger">
								<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> 
								<small>If the record is red, it is because the <b>Certificate has expired.</b></small>
							</div>		
						</div>
						<div class="col-lg-12">
							<div class="alert alert-warning">
								<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
								<small>If the record is yellow, it is because the <b>Certificate has less than 90 days to expire.</b></small>
							</div>		
						</div>
					</div>					

					<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables">
						<thead>
							<tr>
								<th>Certificate</th>
								<th>Expires?</th>
								<th class="text-center">Date Throught <small>(YYYY-MM-DD)</small></th>
								<th class="text-center">Links </th>
							</tr>
						</thead>
						<tbody>							
						<?php
							$filtroFecha = strtotime(date('Y-m-d'));
							foreach ($info as $lista):

									//semaforo de acuerdo a fecha de vencimiento
									$fechaVencimiento = strtotime($lista['date_through']);
									$diferencia = $fechaVencimiento - $filtroFecha;
									//8035200 --> equivalen a 90 dias
									//si la diferencia es mayor a 90 dias no hay problema
									$class = '';
									$date = "";
									if($lista["expires"] != 2){
										$date = $lista['date_through'];
										if($diferencia > 8035200){
											$class = '';
										}elseif($diferencia <= 8035200 && $diferencia >= 0){
											//si la diferencia es entre 0 y 30 dias, entonces se va a vencer pronto
											$class = 'warning text-warning';
										}else{
											//si la diferencia es menor que 0 entonces esta vencida
											$class = 'danger text-danger';
										}
									}
									echo "<tr class='" . $class . "'>";
									echo "<td><b>" . $lista['certificate'] . "</b></td>";
								?>


<form  name="form_<?php echo $lista['id_user_certificate']; ?>" id="form_<?php echo $lista['id_user_certificate']?>" method="post" action="<?php echo base_url("admin/update_user_certificate"); ?>">
<input type="hidden" id="hddidEmployeeCertificate" name="hddidEmployeeCertificate" value="<?php echo  $lista['id_user_certificate']; ?>"/>
									<td class='text-center'>
										<select name="expiresUpdate" id="expiresUpdate" class="form-control" required>
											<option value=''>Select...</option>
											<option value=1 <?php if($lista["expires"] == 1) { echo "selected"; }  ?>>Yes</option>
											<option value=2 <?php if($lista["expires"] == 2) { echo "selected"; }  ?>>No</option>
										</select>
									</td>

									<td class='text-center'>
										<input type="date" class="form-control" id="dateThroughUpdate" name="dateThroughUpdate" value="<?php echo $date; ?>" placeholder="YYYY-MM-DD" />
									</td>
									<td class='text-center'>
										<button type="submit" id="btnUpdate" name="btnUpdate" class="btn btn-info btn-xs" title="Update">
											 <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true">
										</button> 

										<button type="button" id="<?php echo $lista['id_user_certificate']; ?>" class='btn btn-danger btn-xs' title="Delete">
												<i class="fa fa-trash-o"></i>
										</button>
									</td>
									</form>	
								<?php
									echo "</tr>";
							endforeach;
						?>
						</tbody>
					</table>
				<?php } ?>
				
				</div>
			</div>
		</div>
	
		<div class="col-lg-4">
			<div class="panel panel-info">
				<div class="panel-heading">
					<i class="fa fa-user"></i> <b><?php echo $UserInfo[0]['first_name'] . ' ' . $UserInfo[0]['last_name']; ?></b> - Information
				</div>
				<div class="panel-body">
				
					<?php if($UserInfo[0]["photo"]){ ?>
						<div class="form-group">
							<div class="row" align="center">
								<img src="<?php echo base_url($UserInfo[0]["photo"]); ?>" class="img-rounded" alt="User Photo" />
							</div>
						</div>
					<?php }

$movil = $UserInfo[0]["movil"];
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
					 ?>
				
					<strong>Name: </strong><?php echo $UserInfo[0]['first_name'] . ' ' . $UserInfo[0]['last_name']; ?><br>
					<strong>User: </strong><?php echo $UserInfo[0]['log_user']; ?><br>
					<strong>Movil: </strong><?php echo $resultado; ?><br>
					<strong>Email: </strong><?php echo $UserInfo[0]['email']; ?><br>
					<strong>DOB: </strong><?php echo $UserInfo[0]['birthdate']; ?><br>
					<strong>SIN: </strong><?php echo chunk_split($UserInfo[0]['social_insurance'],3," ") ; ?><br>
					<strong>Health number: </strong><?php echo chunk_split($UserInfo[0]['health_number'],3," ") ; ?><br>
					<strong>Address: </strong><?php echo $UserInfo[0]['address']; ?>
				</div>
			</div>
		</div>
			
	</div>
	
</div>
<!-- /#page-wrapper -->

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
		"searching": false
	});
});
</script>
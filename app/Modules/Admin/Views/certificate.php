<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>
$(function(){ 
	$(".btn-outline").click(function () {	
			var oID = $(this).attr("id");
            $.ajax ({
                type: 'POST',
				url: base_url + 'admin/cargar-modal-certificate',
                data: {'idCertificate': oID},
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
					<i class="fa fa-link"></i> SETTINGS - CERTIFICATE LIST
				</div>
				<div class="panel-body">
<?php
	//DESHABILITAR EDICION
	$deshabilitar = '';
	$session = session();
	$userRol = $session->rol;
	//SOLO SE HABILITA EL BOTON DE CERTIFICADOS PARA EL USUARIO SUPER ADMINISTRADOR Y SAFETY 
?>
				<?php if($userRol == 99 || $userRol == 4){ ?>
					<button type="button" class="btn btn-outline btn-primary btn-block" data-toggle="modal" data-target="#modal" id="x">
							<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add Certificate
					</button><br>
				<?php } ?>
    <?php
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

					<form name="formCheckin" id="formCheckin" method="post">
						<div class="panel panel-default">
							<div class="panel-footer">
								<div class="row">
									<div class="col-lg-4">
										<div class="form-group input-group-sm">	
											<label class="control-label" for="idCertificate">Certificate:</label>								
											<select name="idCertificate" id="idCertificate" class="form-control" >
												<option value="">Seleccione...</option>
												<?php for ($i = 0; $i < count($certificateList); $i++) { ?>
													<option value="<?php echo $certificateList[$i]["id_certificate"]; ?>" <?php if($_POST && $_POST["idCertificate"] == $certificateList[$i]["id_certificate"]) { echo "selected"; }  ?>><?php echo $certificateList[$i]["certificate"]; ?></option>
												<?php } ?>
											</select>
										</div>
									</div>

<script>
	$( function() {
		$( "#date" ).datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: 'yy-mm-dd'
		});
	});
</script>
									<div class="col-lg-2">
										<div class="form-group input-group-sm">	
											<label class="control-label" for="date">Date Throught <small>(YYYY-MM-DD)</small>: </label>								
											<input type="text" class="form-control" id="date" name="date" value="<?php if($_POST && $_POST["date"]) { echo $_POST["date"]; }  ?>" placeholder="Date Throught" />
										</div>
									</div>

									<div class="col-lg-4">
										<div class="form-group"><br>
											<button type="submit" id="btnSearch" name="btnSearch" class="btn btn-primary btn-sm" >
												Search <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
											</button> 
										</div>
									</div>
								</div>
							</div>
						</div>
					</form>

				<?php
					if($info){
				?>

					<table class="table table-hover" id="dataTables">
						<thead>
							<tr>
								<th >Certificate</th>
								<th >Description</th>
								<th >Employees</th>
								<?php if($userRol == 99 || $userRol == 4){ ?>
								<th class="text-center">Edit</th>
								<?php } ?>
							</tr>
						</thead>
						<tbody>							
						<?php
							foreach ($info as $lista):
								echo "<tr>";
								echo "<td><small>{$lista['certificate']}</small></td>";
								echo "<td><small>{$lista['certificate_description']}</small></td>";
								echo "<td>";
								if (!empty($lista['employees'])) {
									echo "<ol><small>";
									foreach ($lista['employees'] as $emp):
										echo "<li>{$emp['first_name']} {$emp['last_name']} - <b>{$emp['date_through']}</b></li>";
									endforeach;
									echo "</small></ol>";
								}

								echo "</td>";
								if($userRol == 99 || $userRol == 4){
									echo "<td class='text-center'>";
							?>
									<button type="button" class="btn btn-outline btn-primary btn-xs" data-toggle="modal" data-target="#modal" id="<?php echo $lista['id_certificate']; ?>" >
										<span class="glyphicon glyphicon-edit" aria-hidden="true">
									</button>
							<?php
									echo "</td>";
								}
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
		"pageLength": 100
	});
});
</script>
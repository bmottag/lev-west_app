<script type="text/javascript" src="<?php echo base_url("assets/js/validate/external/checkin.js?v=1.0.0"); ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/js/validate/external/ajaxCheckin.js"); ?>"></script>

<script>
$(function(){ 
	$(".btn-danger").click(function () {	
			var oID = $(this).attr("id");
            $.ajax ({
                type: 'POST',
				url: base_url + 'external/cargar_modal_checkout',
                data: {'idCheckin': oID},
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

<?php if(!$idCheckin){ ?>
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<i class="fa fa-tasks"></i><strong> SIGN-IN </strong>
				</div>
				<div class="panel-body">
				<form  name="form" id="form" method="post" >
					<input type="hidden" id="idProject" name="idProject" value="<?php echo $jobInfo[0]["id_job"]; ?>" >
					<div class="row">
						<div class="col-lg-12">
							<div class="alert alert-info ">
								<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
									Welcome to <b>VCI</b> Sign-In form. 
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-lg-12">
							<h3><strong>Date: </strong><?php echo date('Y-m-d'); ?><br>
							<small><?php echo $jobInfo[0]["job_description"]; ?></small></h3>
						</div>
					</div>
					<br>
					<div class="row">
						<div class="col-lg-3">
							<div class="form-group">									
								<label class="control-label" for="login_before">Have you Sign-In before? <small class="text-primary"> </small></label>
								<select name="login_before" id="login_before" class="form-control" required>
									<option value=''>Seleccione...</option>
									<option value=1 >Yes</option>
									<option value=2 >No</option>
								</select>
							</div>
						</div>

						<div class="col-lg-4">
							<div class="form-group" id="div_name" style="display:none">
								<label class="control-label" for="activo">Select your name: <small class="text-primary"> </small></label>			
								<select name="id_name" id="id_name" class="form-control">
									<option value="">Seleccione...</option>
									<?php
										if($workers){
											for ($i = 0; $i < count($workers); $i++) { ?>
											<option value="<?php echo $workers[$i]["id_worker"]; ?>" ><?php echo $workers[$i]["worker_name"]; ?></option>	
									<?php }} ?>
								</select>
							</div>
						</div>
					</div>
					<div class="row" id="div_new_worker" style="display:none">
						<div class="col-lg-6">
							<div class="form-group">									
								<label class="control-label" for="new_name">Full Name:<small class="text-primary"> </small></label>
								<input type="text" id="new_name" name="new_name" class="form-control" placeholder="Full Name" >
							</div>
						</div>
						<div class="col-lg-6">
							<div class="form-group">									
								<label class="control-label" for="new_phone_number">Phone Number<small class="text-primary"> </small></label>
								<input type="number" id="new_phone_number" name="new_phone_number" class="form-control" placeholder="Phone Number" >
							</div>
						</div>
					</div>

					<div class="form-group">
						<div class="row" align="center">
							<div style="width:50%;" align="center">
								<button type="button" id="btnSubmit" name="btnSubmit" class="btn btn-primary" >
									Sign-In <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true">
								</button> 
							</div>
						</div>
					</div>
										
					<div class="form-group">
						<div class="row" align="center">
							<div style="width:80%;" align="center">
								<div id="div_load" style="display:none">		
									<div class="progress progress-striped active">
										<div class="progress-bar" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 45%">
											<span class="sr-only">45% completado</span>
										</div>
									</div>
								</div>
								<div id="div_error" style="display:none">			
									<div class="alert alert-danger"><span class="glyphicon glyphicon-remove" id="span_msj">&nbsp;</span></div>
								</div>
							</div>
						</div>
					</div>	
				</form>	
				</div>
			</div>
		</div>
	</div>
<?php } ?>

	<div class="row">
		<div class="col-lg-12">				
			<div class="panel panel-primary">
				<div class="panel-heading">
					People Working Today - <b><?php echo date('Y-m-d'); ?></b>
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
					if($checkinList){
				?>		
					<table width="100%" class="table table-hover" id="dataTables">
						<thead>
							<tr>
								<th class="text-center">Date</th>
								<th >Worker</th>
								<th class="text-center">Phone Number</th>
								<th class="text-center">Sign-In</th>
								<th class="text-center">Sign-Out</th>
							</tr>
						</thead>
						<tbody>							
						<?php
							foreach ($checkinList as $lista):
								$checkOut = $lista['checkout_time'];
								$flag = false;
								if($lista['checkout_time']=="0000-00-00 00:00:00"){
									$checkOut = "<p class='text-danger'><i class='fa fa-refresh fa-fw'></i><b>Still working</b><p>";
									$flag = true;
								}
								
								echo "<tr>";
								echo "<td class='text-center'>" . date('M d, Y', strtotime($lista['checkin_date']));
								if($flag){
						?>
								<br>
								<button type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#modal" id="<?php echo $lista['id_checkin']; ?>" >
									Sign-Out <span class="glyphicon glyphicon-edit" aria-hidden="true">
								</button>
						<?php
								}
								echo "</td>";
								echo "<td>" . $lista['worker_name'] . "</td>";
								echo "<td class='text-center'>" . $lista['worker_movil'] . "</td>";
								echo "<td class='text-center'>" . $lista['checkin_time'] . "</td>";
								echo "<td class='text-center'>" . $checkOut . "</td>";
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
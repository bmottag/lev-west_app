<div id="page-wrapper">
	<br>
	
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<a class="btn btn-primary btn-xs" href=" <?php echo base_url().'jobs'; ?> "><span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Go back </a> 
					<i class="fa fa-image"></i> <strong>LOCATES</strong>
				</div>
				<div class="panel-body">

					<div class="alert alert-info">
						<h2>
							<span class="fa fa-briefcase" aria-hidden="true"></span>
							<strong>Job Code/Name: </strong><?php echo $jobInfo[0]['job_description']; ?>
						</h2>
					</div>
				
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

					<form  name="form_map" id="form_map" class="form-horizontal" method="post" enctype="multipart/form-data" action="<?php echo base_url("jobs/do_upload_locates"); ?>">
						<input type="hidden" id="hddIdJob" name="hddIdJob" value="<?php echo $jobInfo[0]["id_job"]; ?>"/>
						
						<div class="form-group">
							<label class="col-sm-4 control-label" for="description">Description: *</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" id="description" name="description" required />
							</div>
						</div>
						
						<div class="col-lg-6">				
							<div class="form-group">					
								<label class="col-sm-5 control-label" for="hddTask">Attach locate</label>
								<div class="col-sm-5">
									<input type="file" name="userfile" class="form-control" required>
								</div>
							</div>
						</div>
							
						<div class="col-lg-6">				
							<div class="form-group">
								<div class="row" align="center">
									<div style="width:50%;" align="center">
										<button type="submit" id="btnSubmit" name="btnSubmit" class='btn btn-primary'>
												Save <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true">
										</button>
									</div>
								</div>
							</div>
						</div>
					</form>

					
				<div class="col-lg-12">
					<div class="alert alert-danger">
							<strong>Note :</strong><br>
							Allowed format: gif - jpg - png - pdf<br>
							Maximum size: 3000 KB<br>
							Maximum width: 2024 pixels<br>
							Maximum height: 2008 pixels<br>
							
					</div>
				</div>

					<?php 
						if($locates){
					?>
					<table class="table table-bordered table-striped table-hover table-condensed">
						<tr class="dafault">
							<td><p class="text-center"><strong>Description</strong></p></td>
							<td><p class="text-center"><strong>Locates</strong></p></td>
							<td><p class="text-center"><strong>Delete</strong></p></td>
						</tr>
						<?php
							foreach ($locates as $data):
								echo "<tr>";					
								echo "<td ><small>" . $data['locates_description'] . "</small></td>";
								echo "<td class='text-center'><center>";

						?>
						<a href="<?php echo base_url( $data['locates_photo']) ?>" target="_blank">Locates</a>
						<?php 

								echo "</center></td>";
								echo "<td class='text-center'><small>";
						?>
							<center>
							<a class='btn btn-danger btn-sm' href='<?php echo base_url('jobs/deleteJobLocate/' . $data['id_job_locates'] . '/' . $data['fk_id_job']) ?>' id="btn-delete">
									<span class="glyphicon glyphicon-remove" aria-hidden="true"> </span>  Delete
							</a>
							</center>
						<?php
								echo "</small></td>";                     
								echo "</tr>";
							endforeach;
						?>
					</table>
					<?php } ?>
					
				</div>
			</div>
		</div>
	</div>
</div>
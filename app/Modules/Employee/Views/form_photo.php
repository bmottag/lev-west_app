<div id="page-wrapper">
	<br>
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h4 class="list-group-item-heading">
					<i class="fa fa-user fa-fw"></i> USER PROFILE
					</h4>
				</div>
			</div>
		</div>		
	</div>

	<div class="row">
		<div class="col-lg-4">
			<div class="panel panel-info">
				<div class="panel-heading">
					<i class="fa fa-user"></i> <strong><?php echo session()->get('name'); ?></strong>
				</div>
				<div class="panel-body">
				
					<?php if($UserInfo[0]["photo"]){ ?>
						<div class="form-group">
							<div class="row" align="center">
								<img src="<?php echo base_url($UserInfo[0]["photo"]); ?>" class="img-rounded" alt="Employee Photo" />
							</div>
						</div>
					<?php } ?>
				
					<strong>Name: </strong><?php echo session()->get('name'); ?><br>
					<strong>DOB: </strong><?php echo $UserInfo[0]["birthdate"]; ?><br>
					<?php
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
					<strong>Mobile number: </strong><?php echo $resultado; ?><br>
					<strong>Email: </strong><?php echo $UserInfo[0]["email"]; ?><br>
					<strong>Address: </strong><?php echo $UserInfo[0]["address"]; ?><br>
					<strong>Postal code: </strong><?php echo $UserInfo[0]["postal_code"]; ?>
				</div>
			</div>
		</div>

		<div class="col-lg-4">
			<div class="panel panel-info">
				<div class="panel-heading">
					<i class="fa fa-image"></i> <b>Upload your photo</b>
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
					<form name="form" id="form" method="post" enctype="multipart/form-data" action="<?= base_url('employee/do_upload') ?>">
						<div class="form-group">
							<label class="col-sm-12 control-label" for="hddTask">Photo:</label>
							<div class="col-sm-12">
								<input type="file" name="userfile" class="form-control" accept="image/png, image/jpeg, image/gif" required>
							</div>
						</div>
						<div class="form-group" style="margin-top: 15px;">
							<div class="row">
								<div class="col-sm-12 text-center">                            
									<button type="submit" id="btnSubmit" name="btnSubmit" class="btn btn-info">
										Submit <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span>
									</button> 
								</div>
							</div>
						</div>
						
						<div class="alert alert-info">
							<strong>Note :</strong><br>
							Allowed format: gif - jpg - png<br>
							Maximum size: 3000 KB<br>
							Maximum width: 2024 pixels<br>
							Maximum height: 2008 pixels<br>
						</div>
					</form>
				</div>
			</div>
		</div>

		<div class="col-lg-4">
			<div class="panel panel-primary">
	            <div class="panel-heading">
	                <i class="fa fa-edit fa-fw"></i> Signature
	            </div>

                <div class="panel-body">	
					
					<?php if (session()->getFlashdata('success')): ?>
						<div class="alert alert-success">
							<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
							<?= session()->getFlashdata('success') ?>
						</div>
					<?php endif; ?>

					<?php if (session()->getFlashdata('error')): ?>
						<div class="alert alert-danger">
							<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
							<?= session()->getFlashdata('error') ?>
						</div>
					<?php endif; ?>

					<?= view('App\Views\template\signature_component', [
						'imageUrl'   => $UserInfo[0]['user_signature'] ?? null,
						'formAction' => base_url('employee/save_signature'),
						'hiddenName' => 'image',
						'height'     => 200,
						'showAlert' 	 => true
					]) ?>

				</div>
			</div>
		</div>

	</div>
</div>
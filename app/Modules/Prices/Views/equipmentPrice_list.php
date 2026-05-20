<script type="text/javascript" src="<?php echo base_url("assets/js/validate/prices/equipmentUnitPrice.js?v=1.0.0"); ?>"></script>

<div id="page-wrapper">
	<br>
	
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-purpura">
				<div class="panel-heading">
					<a class="btn btn-purpura btn-xs" href=" <?php echo base_url().'admin/job/1'; ?> "><span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Go back </a> 
					<i class="fa fa-flag"></i> <strong>JOB - EQUIPMENT UNIT PRICE</strong>
				</div>
				<div class="panel-body">
	
					<div class="row">
						<div class="col-lg-12">	
							<div class="alert alert-danger">
								<h2>
									<span class="fa fa-briefcase" aria-hidden="true"></span>
									<strong>Job Code/Name: </strong><?php echo $jobInfo[0]['job_description']; ?>
								</h2>

								<br><br>
								<span class="glyphicon glyphicon-alert" aria-hidden="true"></span>
								Load Equipment Unit Price for this <strong>Job Code</strong> from the following button.
								<?php $idJob = $jobInfo[0]['id_job']; ?>
								<button type="button" id="<?php echo $idJob; ?>" class='btn btn-danger btn-xs' title="Load">
										Load Data <i class="fa fa-upload"></i>
								</button>
							</div>
						</div>				
					</div>
				</div>
			</div>
		</div>		
	</div>
					
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-danger">
				<div class="panel-heading">
					<i class="fa fa-flag"></i> <strong>Unit Hour Price</strong> list by Equipment for this <strong>Job Code/Name</strong>.
				</div>
				<div class="panel-body">
				
					<ul class="nav nav-pills">
						<li <?php if($companyType == 1){ echo "class='active'";} ?>><a href="<?php echo base_url("prices/equipmentUnitPrice/$idJob/1"); ?>">Lev West EQUIPMENT</a>
						</li>
						<li <?php if($companyType == 2){ echo "class='active'";} ?>><a href="<?php echo base_url("prices/equipmentUnitPrice/$idJob/2"); ?>">RENTAL EQUIPMENT</a>
						</li>
					</ul>
					<br>
					<?php 
						if(!$equipmentUnitPrice){
					?>
					<div class="row">
						<div class="col-lg-12">	
							<div class="alert alert-danger">
								<span class="glyphicon glyphicon-alert" aria-hidden="true"></span>
								There are no prices for this project, load the general prices from the <strong>Load Data</strong> button.
							</div>
						</div>				
					</div>				
					<?php
						}
					?>
				
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
					<!--INICIO -->								
					<?php 
						if($equipmentUnitPrice){
					?>
					
					<form  name="employee_type_prices" id="employee_type_prices" method="post" action="<?php echo base_url("prices/update_job_equipment_price"); ?>">

						<input type="hidden" id="hddIdJob" name="hddIdJob" value="<?php echo $jobInfo[0]['id_job']; ?>"/>
						<input type="hidden" id="hddIdCompanyType" name="hddIdCompanyType" value="<?php echo $companyType; ?>"/>
						
						<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables">
							<thead>
								<tr>
									<th class="text-center">Equipment Type</th>
									<th class="text-center">Equipment</th>
									<th class="text-center">Unit Price Without Driver</th>
									<th class="text-center">Unit Price
									<button type="submit" class="btn btn-primary btn-xs" id="btnSubmit2" name="btnSubmit2" >
										Update <span class="glyphicon glyphicon-edit" aria-hidden="true">
									</button>
									</th>
								</tr>
							</thead>
							<tbody>							
							<?php
								foreach ($equipmentUnitPrice as $lista):
										echo "<tr>";
										echo "<td>" . $lista['type_2'] . "</td>";
										$equipment = $lista['unit_number'] . " - " . $lista['make'] . " - " . $lista['model'];
										echo "<td>" . $equipment . "</td>";
										
										$unitPrice = $lista['job_equipment_unit_price'];
										$unitPrice = $unitPrice?$unitPrice:0;

										$unitPriceWithoutDriver = $lista['job_equipment_without_driver'];
										$unitPriceWithoutDriver = $unitPriceWithoutDriver?$unitPriceWithoutDriver:0;
										
										echo "<td class='text-right'>";
							?>
							<input type="hidden" id="price" name="form[id][]" value="<?php echo $lista['id_equipment_price']; ?>"/>
							<input type="text" id="price" name="form[priceWithoutDriver][]" class="form-control" placeholder="Without Driver" value="<?php echo $unitPriceWithoutDriver; ?>" >
							<?php
										echo "</td>";
										echo "<td class='text-right'>";
							?>
							<input type="text" id="price" name="form[price][]" class="form-control" placeholder="Unit Price" value="<?php echo $unitPrice; ?>" >
							<?php
										echo "</td>";
										echo "</tr>";

								endforeach;
							?>
							</tbody>
						</table>
						
					</form>
					<?php } ?>
					<!--FIN -->
					
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Tables -->
<script>
$(document).ready(function() {
	$('#dataTables').DataTable({
		responsive: true,
		"pageLength": 100
	});
});
</script>
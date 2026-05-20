<div id="page-wrapper">
	<br>
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h4 class="list-group-item-heading">
					<i class="fa fa-gear fa-fw"></i> SETTINGS - GENERAL EQUIPMENT UNIT PRICES BY HOUR
					</h4>
				</div>
			</div>
		</div>			
	</div>
	
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<i class="fa fa-flag-o"></i> GENERAL EQUIPMENT UNIT PRICES BY HOUR - <?php echo $title; ?>
				</div>
				<div class="panel-body">
				
					<ul class="nav nav-pills">
						<li <?php if($companyType == 1){ echo "class='active'";} ?>><a href="<?php echo base_url("prices/equipmentList/1"); ?>">VCI EQUIPMENT</a>
						</li>
						<li <?php if($companyType == 2){ echo "class='active'";} ?>><a href="<?php echo base_url("prices/equipmentList/2"); ?>">RENTAL EQUIPMENT</a>
						</li>
					</ul>
					<br>

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

					<form  name="employee_type_prices" id="employee_type_prices" method="post" action="<?php echo base_url("prices/update_equipment_price"); ?>">

						<input type="hidden" id="hddIdCompanyType" name="hddIdCompanyType" value="<?php echo $companyType; ?>"/>
					
						<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables">
							<thead>
								<tr>
									<th class="text-center">Equipment Type</th>
									<th class="text-center">Equipment</th>
									<th class="text-center">VCI Cost</th>
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
								foreach ($info as $lista):
										echo "<tr>";
										echo "<td>" . $lista['type_2'] . "</td>";
										$equipment = $lista['unit_number'] . " - " . $lista['make'] . " - " . $lista['model'];
										echo "<td>" . $equipment . "</td>";
										
										$unitCost = $lista['equipment_unit_cost'];
										$unitCost = $unitCost?$unitCost:0;

										$unitPriceWithoutDriver = $lista['equipment_unit_price_without_driver'];
										$unitPriceWithoutDriver = $unitPriceWithoutDriver?$unitPriceWithoutDriver:0;

										$unitPrice = $lista['equipment_unit_price'];
										$unitPrice = $unitPrice?$unitPrice:0;
										echo "<td class='text-right'>";
							?>
							<input type="hidden" id="price" name="form[id][]" value="<?php echo $lista['id_vehicle']; ?>"/>
							<input type="text" id="price" name="form[cost][]" class="form-control" placeholder="VCI Cost" value="<?php echo $unitCost; ?>" >
							<?php
										echo "</td>";

										echo "<td class='text-right'>";
							?>
							<input type="text" id="price" name="form[priceWithoutDriver][]" class="form-control" placeholder="Without Driver" value="<?php echo $unitPriceWithoutDriver; ?>" >
							<?php
										echo "</td>";

										echo "<td class='text-right'>";
							?>
							<input type="text" id="price" name="form[price][]" class="form-control" placeholder="Unit Price" value="<?php echo $unitPrice; ?>" >
							<?php
										echo "</td>";
										
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

<!-- Tables -->
<script>
$(document).ready(function() {
	$('#dataTables').DataTable({
		responsive: true,
		"pageLength": 100
	});
});
</script>
<script type="text/javascript" src="<?php echo base_url("assets/js/validate/serviceorder/ajaxSearchEquipment.js"); ?>"></script>

<div id="page-wrapper">
	<br>
	<div class="row" id="div_main_title">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h4 class="list-group-item-heading">
						SERVICE ORDER CONTROL PANEL
					</h4>
				</div>
			</div>
		</div>
	</div>

	<div class="row" id="div_panel_main">
		<div class="col-lg-3 col-md-6 col-sm-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <i class="fa fa-bell fa-fw"></i> Equipments
					<input type="hidden" id="hddSpecificIdServiceOrder" name="hddSpecificIdServiceOrder" value="<?php echo $infoSpecificSO ? $infoSpecificSO[0]['id_service_order'] : '' ?>"/>
					<?php $idSpecificEquipment = $infoSpecificSO ? $infoSpecificSO[0]['fk_id_equipment'] : ($idEquipment ? $idEquipment : '');?>
					<input type="hidden" id="hddSpecificIdEquipment" name="hddSpecificIdEquipment" value="<?php echo $idSpecificEquipment; ?>"/>
					<input type="hidden" id="hddModuleView" name="hddModuleView" value="<?php echo $moduleView; ?>"/>
                </div>
                <div class="panel-body">
                    <div class="list-group">
						<?php
							if($infoEquipment){
								foreach ($infoEquipment as $data):
							?>
								<a class="list-group-item" onclick="loadEquipmentList( <?php echo $data['inspection_type']; ?>  , '<?php echo $data['header_inspection_type']; ?>')">
									<i class="fa fa-filter fa-fw"></i> <?php echo $data["header_inspection_type"]; ?>
									<span class="pull-right text-muted small"><em> <?php echo $data["number"]; ?></em>
									</span>
								</a>
							<?php
								endforeach;
							}
						?>
                    </div>
                </div>
            </div>

			<?php if($infoSO){ ?>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <i class="fa fa-bell fa-fw"></i> Service Order by Status - <b><?php echo date('Y'); ?></b>
                </div>
                <div class="panel-body">
                    <div class="list-group">
						<?php
							foreach ($infoSO as $data):
						?>
							<a class="list-group-item" onclick="loadSOList( '<?php echo $data['status_slug']; ?>')">
								<p class="text-<?php echo $data["status_style"] ?>"><i class="fa <?php echo $data["status_icon"] ?> fa-fw"></i><strong> <?php echo $data["status_name"]; ?></strong>
									<span class="pull-right text-muted small"><em> <?php echo $data["number"]; ?></em>
									</span>
								</p>
							</a>
						<?php
							endforeach;
						?>
                    </div>
                </div>
            </div>
			<?php } ?>

			<?php
				$userRol = session()->get("rol");
				if($userRol == ID_ROL_SUPER_ADMIN || $userRol == ID_ROL_SAFETY){ 
			?>
			<div class="list-group">
				<a class="btn btn-outline btn-primary btn-block" onclick="loadExpenses()" >
					<i class="fa fa-money"></i> Expenses - <?php echo date('Y'); ?>
				</a>
			</div>
			<?php } ?>
        </div>

		<div class="col-lg-9 col-md-6 col-sm-6">
			<div class="row" id="div_search">
				<div class="col-lg-6 col-md-12 col-sm-12">
					<div class="panel panel-violeta">
						<div class="panel-heading">
							<i class="fa fa-wrench fa-fw"></i> <strong>Search Equipment by VIN Number</strong>
						</div>
						<div class="panel-body">									
							<div class="form-group">
								<div class="col-md-8 col-sm-9 col-xs-10">
									<input type="text" id="vinNumber" name="vinNumber" class="form-control" placeholder="VIN Number" minlength="5">
								</div>						
							
								<div class="col-md-3 col-sm-2 col-xs-2">
									<button type="submit" class="btn btn-violeta" id="btnVINNumber">
										<span class="glyphicon glyphicon-search" aria-hidden="true"></span> 
									</button>
								</div>

							</div>
								
						</div>
					</div>
				</div>

				<div class="col-lg-6 col-md-12 col-sm-12">
					<div class="panel panel-violeta">
						<div class="panel-heading">
							<i class="fa fa-wrench fa-fw"></i> <strong>Search Service Order</strong>
						</div>
						<div class="panel-body">									
							<div class="form-group">
								<div class="col-md-8 col-sm-9 col-xs-10">
									<input type="text" id="soNumber" name="soNumber" class="form-control" placeholder="SO Number">
								</div>						
							
								<div class="col-md-3 col-sm-2 col-xs-2">
									<button type="submit" class="btn btn-violeta" id="btnSONumber">
										<span class="glyphicon glyphicon-search" aria-hidden="true"></span> 
									</button>
								</div>

							</div>
								
						</div>
					</div>
				</div>
			</div>

			<div class="row" id="div_info_SO_main" >
				<div class="col-lg-12 col-md-12 col-sm-12">
					<div class="panel panel-info">
						<div class="panel-heading"> 
							<i class="fa fa-briefcase"></i> <strong>Last 50 Service Orders</strong>
						</div>
						<div class="panel-body small">
							<?php 										
								if(!$information){ 
									echo '<div class="col-lg-12">
											<p class="text-danger"><span class="glyphicon glyphicon-alert" aria-hidden="true"></span> There are no records in the system.</p>
										</div>';
								} else {
							?>
							<table width="100%" class="table table-striped table-bordered table-hover" id="dataTablesMainSO">
								<thead>
									<tr>
										<th>S.O. #</th>
										<th>Priority</th>
										<th>Unit Number</th>
										<th>VIN Number</th>
										<th>Description</th>
										<th>Assigned To</th>
										<th>Request Date</th>
										<th>Status</th>
									</tr>
								</thead>
								<tbody>							
								<?php
									foreach ($information as $lista):
											echo "<tr>";
											echo "<td class='text-center'>";
											echo $lista['id_service_order'] . "<br>";
								?>
											<a class="btn btn-primary btn-xs" onclick="loadEquipmentDetail( <?php echo $lista['fk_id_equipment']; ?>, 'tab_service_order_detail', <?php echo $lista['id_service_order']; ?>)" title="View">
												<i class="fa fa-eye"></i> View
											</a>
								<?php			
											echo "</td>";
											echo "<td class='text-center'>";
								?>
											<p class="text-<?php echo $lista['priority_style']; ?>"><i class="fa <?php echo $lista['priority_icon']; ?> fa-fw"></i><?php echo $lista['priority_name']; ?> </p>
								<?php			
											echo "</td>";
											echo "<td>" . $lista['unit_description'] . "</td>";
											echo "<td>" . $lista['vin_number'] . "</td>";
											echo "<td>" . $lista['main_description'] . "</td>";
											echo "<td>" . $lista['assigned_to'] . "</td>";
											echo "<td>" . date('F j, Y - G:i:s', strtotime($lista['created_at'])) . "</td>";
											echo "<td class='text-center'>";
											echo '<p class="text-' . $lista['status_style'] . '"><i class="fa ' . $lista['status_icon'] . ' fa-fw"></i><b>' . $lista['status_name'] . '</b></p>';
											echo "</td>";
											echo "</tr>";
									endforeach;
								?>
								</tbody>
							</table>
							<?php 
								}
							?>
						</div>
					</div>
				</div>
			</div>

			<div id="div_info_list" style="display:none"></div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12" id="div_detail" style="display:none"></div>
	</div>

</div>

<div class="modal fade text-center" id="modalServiceOrder" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">    
	<div class="modal-dialog" role="document">
		<div class="modal-content" id="tablaDatosServiceOrder">
		</div>
	</div>
</div>

<div class="modal fade text-center" id="modalMaintenance" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">    
	<div class="modal-dialog" role="document">
		<div class="modal-content" id="tablaDatosMaintenace">
		</div>
	</div>
</div>

<!-- Tables -->
<script>
$(document).ready(function() {
    $('#dataTablesMainSO').DataTable({
        "order": [[0, "desc"]],
        paging: false,
        "columnDefs": [
            {
                "targets": [ 3 ],
                "visible": false,
                "searchable": true
            },
			{ "orderable": false, "targets": [4, 6] }
        ]
    });
});
</script>
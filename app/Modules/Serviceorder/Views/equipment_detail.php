<div class="panel panel-violeta">
	<div class="panel-heading"> 
		<a class="btn btn-violeta btn-xs" href=" <?php echo base_url("serviceorder"); ?> "><span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Go to Service Order Control Panel </a> 
		<a class="btn btn-violeta btn-xs" onclick="loadEquipmentList( <?php echo $vehicleInfo[0]['inspection_type']; ?>  , '<?php echo $vehicleInfo[0]['header_inspection_type']; ?>')">
			<span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Go to <?php echo $vehicleInfo[0]["header_inspection_type"]; ?> list
		</a>
		<i class="fa fa-car fa-fw"></i> <strong>EQUIPMENT DETAIL</strong> 
	</div>
	<div class="panel-body">
		<div class="col-lg-3">
			<?= view('App\Modules\Serviceorder\Views\menu_equipment') ?>
		</div>
		<div class="col-lg-9">
			<?= view('App\Modules\Serviceorder\Views\\' . $tabview) ?>
		</div>
	</div>
</div>
<script type="text/javascript" src="<?php echo base_url("assets/js/validate/inspection/ajaxSearchVehicle.js"); ?>"></script>

<div id="page-wrapper">
	<br>

	<div class="row">
		<div class="col-lg-6">
			<div class="panel panel-purpura">
				<div class="panel-heading">
					<i class="fa fa-wrench"></i> <strong>Search vehicle by VIN Number</strong>
				</div>
				<div class="panel-body">
									
					<p class='text-default'>Enter at least 5 consecutive characters of the<strong> VIN Number</strong></p>
									
					<div class="form-group">
						<div class="col-md-8 col-sm-9 col-xs-10">
							<input type="text" id="vinNumber" name="vinNumber" class="form-control" placeholder="VIN Number">
						</div>						
					
						<div class="col-md-3 col-sm-2 col-xs-2">
							 <button type="submit" class="btn btn-purpura" id="btnVINNumber">
							 	<span class="glyphicon glyphicon-search" aria-hidden="true"></span>  
							 </button>
						</div>

					</div>
						
				</div>
			</div>

			<div id="div_vehicle" style="display:none"></div>
		</div>
	</div>
	
</div>
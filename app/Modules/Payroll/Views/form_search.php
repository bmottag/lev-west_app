<script type="text/javascript" src="<?php echo base_url("assets/js/validate/payroll/ajaxSearch.js"); ?>"></script>
<div id="page-wrapper">
	<br>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
					<a class="btn btn-primary btn-xs" href=" <?php echo base_url($dashboardURL); ?> "><span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Go to Dashboard </a> 
                    <i class="fa fa-bar-chart-o fa-fw"></i>  <b>PAYROLL FORM SEARCH</b>
                </div>
                <div class="panel-body">
					<div class="alert alert-info">
						<strong>Note:</strong> 
						Select the period to search your records.
					</div>

					<form  name="form" id="form" role="form" method="post" class="form-horizontal" >

						<div class="form-group">
							<div class="col-sm-1 col-sm-offset-1" required>
								<label for="yearPeriod">Year: *</label>
								<select name="yearPeriod" id="yearPeriod" class="form-control" required>
									<option value='' >Select...</option>
									<?php
									$firstYear = 2018;
									$actualYear = Date("Y");

									for($i = $firstYear; $i <= $actualYear; $i++) {
										?>
										<option value='<?php echo $i; ?>' <?php
										if ($i == $actualYear) {
											echo 'selected="selected"';
										}
										?>><?php echo $i; ?></option>
									<?php } ?>									
								</select>
							</div>

							<div class="col-sm-2" >
								<label for="from">Contract type: *</label>
								<select name="contractType" id="contractType" class="form-control" required>
									<option value=''>Select...</option>
									<option value=2 >Employee</option>
									<option value=1 >Subcontractor</option>
								</select>
							</div>

							<div class="col-sm-3">
								<label for="from">Period: *</label>
								<select name="period" id="period" class="form-control" required >
									<option value=''>Select...</option>
									<?php for ($i = 0; $i < count($infoPeriod); $i++) { ?>
										<option value="<?php echo $infoPeriod[$i]["id_period"]; ?>"><?php echo $infoPeriod[$i]["period"]; ?></option>	
									<?php } ?>
								</select>
							</div>

							<div class="col-sm-3" id="div_employee" style="display:none">
								<label for="from">Employee <small>(This field is NOT required.)</small></label>
									<select name="employee" id="employee" class="form-control" >
									</select>
							</div>
						</div>

						<br>
						<div class="form-group">
							<div class="row" align="center">
								<div style="width80%;" align="center">
									 <button type="submit" class="btn btn-primary" id='btnSubmit' name='btnSubmit'>
									 	<span class="glyphicon glyphicon-search" aria-hidden="true"></span> Search 
									 </button>
								</div>
							</div>
						</div>
                    </form>
				</div>
			</div>
		</div>
	</div>
</div>
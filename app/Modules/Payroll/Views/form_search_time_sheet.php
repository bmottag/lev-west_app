<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<div id="page-wrapper">

	<br>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-warning">
                <div class="panel-heading">
				<a class="btn btn-warning btn-xs" href=" <?php echo base_url($dashboardURL); ?> "><span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Go to Dashboard </a> 
                    <i class="fa fa-bar-chart-o fa-fw"></i>  <b>PAYROLL FORM SEARCH</b>
                </div>
                <div class="panel-body">
					<div class="alert alert-warning">
						<strong>Note:</strong> 
						Select the period to search your records.
					</div>

					<form  name="form" id="form" role="form" method="post" class="form-horizontal" >

						<div class="form-group">
							<div class="col-sm-3 col-sm-offset-2">
								<label for="from">Employee <small>(This field is NOT required.)</small></label>
								<select name="employee" id="employee" class="form-control js-example-basic-single" >
									<option value=''>Select...</option>
									<?php for ($i = 0; $i < count($workersList); $i++) { ?>
										<option value="<?php echo $workersList[$i]["id_user"]; ?>"><?php echo $workersList[$i]["first_name"] . ' ' . $workersList[$i]["last_name"]; ?></option>	
									<?php } ?>
								</select>
							</div>
						</div>

						<div class="form-group">
<script>
	$( function() {
		var dateFormat = "mm/dd/yy",
		from = $( "#from" )
		.datepicker({
			changeMonth: true,
			numberOfMonths: 2
		})
		.on( "change", function() {
			to.datepicker( "option", "minDate", getDate( this ) );
		}),
		to = $( "#to" ).datepicker({
			changeMonth: true,
			numberOfMonths: 2
		})
		.on( "change", function() {
			from.datepicker( "option", "maxDate", getDate( this ) );
		});

		function getDate( element ) {
			var date;
			try {
				date = $.datepicker.parseDate( dateFormat, element.value );
			} catch( error ) {
				date = null;
			}

			return date;
		}
	});
</script>

							<div class="col-sm-3 col-sm-offset-2">
								<label for="from">From Date</label>
								<input type="text" id="from" name="from" class="form-control" placeholder="From" required >
							</div>
							<div class="col-sm-3 col-sm-offset-1">
								<label for="to">To Date</label>
								<input type="text" id="to" name="to" class="form-control" placeholder="To" required >
							</div>
                        </div>

						<br>
						<div class="form-group">
							<div class="row" align="center">
								<div style="width80%;" align="center">
									 <button type="submit" class="btn btn-warning" id='btnSubmit' name='btnSubmit'>
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

<script>
$(document).ready(function() {
	$('.js-example-basic-single').select2({
		width: '100%'
	});
});
</script>
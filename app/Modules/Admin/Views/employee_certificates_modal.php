<script type="text/javascript" src="<?php echo base_url("assets/js/validate/admin/employee_certificate.js"); ?>"></script>

<script>
$(document).ready(function () {
    $('#expire').change(function () {
        $('#expire option:selected').each(function () {
            var expire = $('#expire').val();

            if ( expire == 2 ) {
				$("#div_date").css("display", "none");
                $('#dateThrough').val("");
            } else {
				$("#div_date").css("display", "inline");
			}
        });
    });    
});
</script>

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="exampleModalLabel">EMPLOYEE CERTIFICATES
	<br><small>
				Add the Certificate for the Employee
	</small>
	</h4>
</div>

<div class="modal-body">
	<form  name="form" id="form" role="form" method="post" >
		<input type="hidden" id="hddidEmployee" name="hddidEmployee" value="<?php echo $idEmployee; ?>"/>
		<input type="hidden" id="hddidEmployeeCertificate" name="hddidEmployeeCertificate" value=""/>

		<div class="row">
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label for="certificate">Certificate: *</label>
					<select name="certificate" id="certificate" class="form-control" >
						<option value=''>Select...</option>
						<?php for ($i = 0; $i < count($certificateList); $i++) { ?>
							<option value="<?php echo $certificateList[$i]["id_certificate"]; ?>" ><?php echo $certificateList[$i]["certificate"] ; ?></option>	
						<?php } ?>
					</select>
				</div>
			</div>
					
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="dateThrough">Does the certificate expire?: *</label>
					<select name="expire" id="expire" class="form-control" required>
						<option value=''>Select...</option>
						<option value=1 selected>Yes</option>
						<option value=2 >No</option>
					</select>
				</div>
			</div>
		</div>

		<div class="row">
			
<script>
	$( function() {
		$( "#dateThrough" ).datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: 'yy-mm-dd'
		});
	});
</script>		
			<div class="col-sm-6" id="div_date">
				<div class="form-group text-left">
					<label class="control-label" for="dateThrough">Date Through: *</label>
					<input type="text" class="form-control" id="dateThrough" name="dateThrough" placeholder="Date Through" />
				</div>
			</div>
		</div>

		<div class="form-group">
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

		<div class="form-group">
			<div class="row" align="center">
				<div style="width:50%;" align="center">
					<button type="button" id="btnSubmit" name="btnSubmit" class="btn btn-primary" >
						Save <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true">
					</button> 
				</div>
			</div>
		</div>
		
	</form>
</div>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<div id="page-wrapper">
	<br>

	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<i class="fa fa-suitcase"></i> <b>INVOICES</b>
				</div>
				<div class="panel-body">
					<a class='btn btn-success btn-block' href='<?php echo base_url('invoices/add_invoice'); ?>'>
						<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add an Invoice
					</a>
					<br>
<?php
$retornoExito = session()->getFlashdata('retornoExito');
if ($retornoExito) {
    ?>
	<div class="row">
		<div class="col-lg-12">	
			<div class="alert alert-success ">
				<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
				<?php echo $retornoExito ?>		
			</div>
		</div>
	</div>
    <?php
}

$retornoError = session()->getFlashdata('retornoError');
if ($retornoError) {
    ?>
	<div class="row">
		<div class="col-lg-12">	
			<div class="alert alert-danger ">
				<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
				<?php echo $retornoError ?>
			</div>
		</div>
	</div>
    <?php
}
?> 
					<form  name="formSearch" id="formSearch" role="form" method="post" class="form-horizontal" action="<?php echo base_url('invoices'); ?>">
						<div class="form-group">	

<script>
	$( function() {
		$( "#date" ).datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: 'yy-mm-dd'
		});
	});
</script>

							<div class="col-sm-2">
								<label for="from">Date: </label>
								<input type="text" class="form-control" id="date" name="date" value="<?php echo $_POST?$_POST["date"]:""; ?>" placeholder="Date" />
							</div>
							<div class="col-sm-3">
								<label for="from">Job Code/Name : </label>
								<select name="idJobCode" id="idJobCode" class="form-control" >
									<option value=''>Select...</option>
									<?php for ($i = 0; $i < count($jobs); $i++) { ?>
										<option value="<?php echo $jobs[$i]["id_job"]; ?>" <?php if($_POST && $_POST["idJobCode"] == $jobs[$i]["id_job"]) { echo "selected"; }  ?>><?php echo $jobs[$i]["job_description"]; ?></option>	
									<?php } ?>
								</select>
							</div>
							<div class="col-sm-2">
								<label for="to">Status:</label>
								<select name="status" id="status" class="form-control" >
									<option value=''>Select...</option>
									<?php
									if($statusList) {
										foreach ($statusList as $status) {
									?>
										<option value="<?php echo $status["status_slug"]; ?>" <?php if($_POST && $_POST["status"] == $status["status_slug"]) { echo "selected"; }  ?> ><?php echo $status["status_name"]; ?> </option>
									<?php
										}
									}
									?>
								</select>
							</div>
							<div class="col-sm-2">
								<label for="from">Invoice # : </label>
								<input type="text" class="form-control" id="number" name="number" value="<?php echo $_POST?$_POST["number"]:""; ?>" placeholder="Invoice #" />
							</div>
							<div class="col-sm-1">
								<br>
								<button type="submit" class="btn btn-primary" id='btnSearch' name='btnSearch'><span class="glyphicon glyphicon-search" aria-hidden="true"></span> Search </button>
							</div>
						</div>
					</form>

				<?php 										
					if(!$info){ 
						echo '<div class="col-lg-12">
								<p class="text-danger"><span class="glyphicon glyphicon-alert" aria-hidden="true"></span> There are no records in the system.</p>
							</div>';
					} else {
				?>
					<table width="100%" class="table table-striped table-bordered table-hover small" id="dataTables">
						<thead>
							<tr>
								<th width='4%' class="text-center">Invoice #</th>
								<th width='4%' class="text-center">Date Issue</th>
								<th width='10%' class="text-center">Job Code</th>
								<th width='8%' class="text-center">Client</th>
								<th width='6%' class="text-center">Due Date</th>
								<th width='5%' class="text-center">Status</th>
								<th width='8%' class="text-center">Editar</th>
							</tr>
						</thead>
						<tbody>							
						<?php
							foreach ($info as $lista):
								echo "<tr>";
								echo "<td>" . $lista['number'] . "</td>";
								echo "<td>" . date('M j, Y', strtotime($lista['date_issue'])) . "</td>";
								echo "<td>" . $lista['job_description'] . "</td>";
								echo "<td>" . $lista['company_name'] . "</td>";
								echo "<td>" . date('M j, Y', strtotime($lista['due_date'])) . "</td>";
								echo "<td class='text-center'>";
								echo '<p class="text-' . $lista['status_style'] . '"><b>' . $lista['status_name'] . '</b></p>';
								echo "</td>";
								echo "<td class='text-center'>";
						?>
								<a class='btn btn-success btn-xs' href='<?php echo base_url('invoices/add_invoice/' . $lista['id_invoice']); ?>'>
									Edit <span class="glyphicon glyphicon-edit" aria-hidden="true">
								</a>
						<?php
								echo "</td>";
							endforeach;
						?>
						</tbody>
					</table>

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
		responsive: false,
		"pageLength": 100,
		"order": []
	});
});
</script>
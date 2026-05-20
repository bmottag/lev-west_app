<script>
$(function(){ 
	$(".btn-success").click(function () {	
			var oID = $(this).attr("id");
            $.ajax ({
                type: 'POST',
				url: base_url + 'admin/cargarModalBankTimeBalance',
                data: {'idEmployee': oID},
                cache: false,
                success: function (data) {
                    $('#tablaDatos').html(data);
                }
            });
	});	
});
</script>


<div id="page-wrapper">
	<br>
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h4 class="list-group-item-heading">
					<i class="fa fa-gear fa-fw"></i> Settings- ManPower Settings
					</h4>
				</div>
			</div>
		</div>
		<!-- /.col-lg-12 -->				
	</div>
	
	<!-- /.row -->
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<i class="fa fa-flag-o"></i> ManPower Banking Time
				</div>
				<div class="panel-body">
<?php
$retornoExito = $this->session->flashdata('retornoExito');
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

$retornoError = $this->session->flashdata('retornoError');
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
				<button type="button" class="btn btn-success btn-block" data-toggle="modal" data-target="#modal" id="<?php echo $idUser ?>">
						<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add Balance to Banking Time
				</button><br>
				<?php
					if($info){
				?>				
					<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables">
						<thead>
							<tr>
								<th class="text-center">ManPower Name</th>
								<th class="text-center">Paid Period</th>
								<th class="text-center">Update or Change made by</th>
								<th class="text-center">Remarks and Observations</th>
								<th class="text-center">Date & Time</th>
								<th class="text-center">Clock-In</th>
								<th class="text-center">Clok-Out</th>
								<th class="text-center">Current Balance</th>
							</tr>
						</thead>
						<tbody>							
						<?php
							foreach ($info as $lista):
								echo "<tr>";
								echo "<td class='text-center'><b>" . $lista['employee'] . "</b>";
								echo "<td class='text-center'>" . $lista['period'] . "</td>";
								echo "<td class='text-center'>" . $lista['done_by'] . "</td>";
								echo "<td>" . $lista['observation'] . "</td>";
								echo "<td class='text-center'>" . $lista['date_issue'] . "</td>";
								echo "<td class='text-right'>" . $lista['time_in'] . "</td>";
								echo "<td class='text-right'>" . $lista['time_out'] . "</td>";
								echo "<td class='text-right'><b>" . $lista['balance'] . "</b>";
								echo "</tr>";
							endforeach;
						?>
						</tbody>
					</table>
				<?php } ?>
				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<!-- /.row -->
</div>
<!-- /#page-wrapper -->
		
				
<!--INICIO Modal para adicionar HAZARDS -->
<div class="modal fade text-center" id="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">    
	<div class="modal-dialog" role="document">
		<div class="modal-content" id="tablaDatos">

		</div>
	</div>
</div>                       
<!--FIN Modal para adicionar HAZARDS -->

<!-- Tables -->
<script>
$(document).ready(function() {
	$('#dataTables').DataTable({
		responsive: true,
		"ordering": false
	});
});
</script>
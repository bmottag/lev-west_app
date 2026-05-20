<script>
$(function(){
	$(".btn-info").click(function() {
		var oID = $(this).attr("id");
		$.ajax({
			type: 'POST',
			url: base_url + 'more/cargarModalPPEInspection',
			data: {'idPPEInspection': oID},
			cache: false,
			success: function(data) { $('#tablaDatos').html(data); }
		});
	});
});
</script>

<div id="page-wrapper">
	<br>
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-info">
				<div class="panel-heading">
					<i class="fa fa-wrench"></i> <strong>PPE INSPECTION PROGRAM</strong>
				</div>
				<div class="panel-body">
					<button type="button" class="btn btn-info btn-block" data-toggle="modal" data-target="#modal" id="x">
						<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add PPE Inspection Program
					</button><br><br>

					<?php if ($information): ?>
					<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables">
						<thead>
							<tr>
								<th>#</th>
								<th>Reported by</th>
								<th>Date</th>
								<th>Observation</th>
								<th>Download</th>
								<th>Edit</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($information as $lista): ?>
							<tr>
								<td class='text-center'><?php echo esc($lista['id_ppe_inspection']); ?></td>
								<td><?php echo esc($lista['name']); ?></td>
								<td class='text-center'><?php echo esc($lista['date_ppe_inspection']); ?></td>
								<td><?php echo esc($lista['observation']); ?></td>
								<td class='text-center'>
								<?php 
									if($lista['inspector_signature']){			
								?>
										<a href='<?php echo base_url('more/generaPPEInspectionPDF/' . $lista['id_ppe_inspection'] ); ?>' target="_blank"> <img src='<?php echo base_url('images/pdf.png'); ?>' ></a>
								<?php									
									}else{ 
										echo "-";
									}
								?>
								</td>
								<td class='text-center'>
									<a class='btn btn-success btn-xs' href='<?php echo base_url('more/add_ppe_inspection/' . $lista['id_ppe_inspection']); ?>'>
										Edit <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
									</a>
								</td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade text-center" id="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content" id="tablaDatos"></div>
	</div>
</div>

<script>
$(document).ready(function() {
	$('#dataTables').DataTable({ responsive: true, ordering: false, paging: false, searching: false, info: false });
});
</script>

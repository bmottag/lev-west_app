<script>
$(function(){ 
	$(".btn-outline").click(function () {	
			var oID = $(this).attr("id");
            $.ajax ({
                type: 'POST',
				url: base_url + '/admin/cargar-modal-attachments',
                data: {'idAttachment': oID},
                cache: false,
                success: function (data) {
                    $('#tablaDatos').html(data);
                }
            });
	});	
});

/*
* Function Active/inactive attachments
*/
function activeAttachments(attachmentId, status) {
	if(window.confirm('Are you sure you want to change the status?'))
	{
		$("#loader").addClass("loader");
		$.ajax ({
			type: 'POST',
			url: base_url + 'admin/update_status',
			data: {attachmentId, status},
			cache: false,
			success: function (data)
			{
				$("#loader").removeClass("loader");
				var url = base_url + "admin/attachments/active";
				$(location).attr("href", url);
			}
		});
	}
}
</script>

<div id="page-wrapper">
	<br>
	
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<i class="fa fa-anchor fa-fw"></i> SETTINGS - ATTACHMENT LIST
				</div>
				<div class="panel-body">

					<ul class="nav nav-pills">
						<li <?php if($status == "active"){ echo "class='active'";} ?>><a href="<?php echo base_url("admin/attachments/active"); ?>">List of active Attachments</a>
						</li>
						<li <?php if($status == "inactive"){ echo "class='active'";} ?>><a href="<?php echo base_url("admin/attachments/inactive"); ?>">List of inactive Attachments</a>
						</li>
					</ul>
					<br>	

					<button type="button" class="btn btn-outline btn-primary btn-block" data-toggle="modal" data-target="#modal" id="x">
							<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add an Attachment
					</button><br>
    <?php
	$session = session();
    // Mensaje de éxito
    if ($session->getFlashdata('retornoExito')): ?>
        <div class="row">
            <div class="col-lg-12">
                <div class="alert alert-success">
                    <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                    <?= $session->getFlashdata('retornoExito') ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Mensaje de error -->
    <?php if ($session->getFlashdata('retornoError')): ?>
        <div class="row">
            <div class="col-lg-12">
                <div class="alert alert-danger">
                    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                    <?= $session->getFlashdata('retornoError') ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
				<?php
					if($info){
				?>				
					<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables">
						<thead>
							<tr>
								<th class="text-center">ID #</th>
								<th class="text-center">Attachment</th>
								<th class="text-center">Machinery</th>
								<th class="text-center">Status</th>
								<th class="text-center">Actions</th>
							</tr>
						</thead>
						<tbody>							
						<?php
							foreach ($info as $lista):
									echo "<tr>";
									echo "<td>" . $lista['attachment_number'] . "</td>";
									echo "<td>" . $lista['attachment_description'] . "</td>";
									echo "<td>" . $lista['equipments'] . "</td>";
									echo "<td class='text-center'>";
									$style = $lista['attachment_status'] == "active" ? "" : "btn-outline";
						?>				
									<a class="btn <?php echo $style; ?> btn-<?php echo $lista['status_style']; ?> btn-xs" onclick="activeAttachments( <?php echo $lista['id_attachment']; ?> ,  '<?php echo $lista['attachment_status']; ?>' )" title="Change Attachment status">
										<?php echo ucfirst($lista['attachment_status']); ?>
									</a>
						<?php
									echo "</td>";
									echo "<td class='text-center'>";
						?>
									<button type="button" class="btn btn-outline btn-primary btn-xs" data-toggle="modal" data-target="#modal" id="<?php echo $lista['id_attachment']; ?>" >
										Edit <span class="glyphicon glyphicon-edit" aria-hidden="true">
									</button>
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
		"pageLength": 100
	});
});
</script>
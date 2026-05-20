<div id="page-wrapper">
	<br>

	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<i class="fa fa-hand-o-up"></i> SETTINGS - MANUALS LINKS LIST
				</div>
				<div class="panel-body">

					<a class='btn btn-outline btn-primary btn-block' href='<?php echo base_url('enlaces/manuals_form') ?>'>
						<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add a Link 
					</a>
					<br>
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
								<th class="text-center">Link name</th>
								<th class="text-center">Link URL</th>
								<th class="text-center">Order</th>
								<th class="text-center">State</th>
								<th class="text-center">Edit</th>
							</tr>
						</thead>
						<tbody>							
						<?php
							foreach ($info as $lista):
									echo "<tr>";
									echo "<td>" . $lista['link_name'] . "</td>";
									echo "<td>" . $lista['link_url'] . "</td>";
									echo "<td>" . $lista['order'] . "</td>";
									echo "<td class='text-center'>";
									switch ($lista['link_state']) {
										case 1:
											$valor = 'Active';
											$clase = "text-success";
											break;
										case 2:
											$valor = 'Inactive';
											$clase = "text-danger";
											break;
									}
									echo '<p class="' . $clase . '"><strong>' . $valor . '</strong></p>';
									echo "</td>";
									echo "<td class='text-center'>";
						?>

										<a class='btn btn-outline btn-primary btn-xs' href='<?php echo base_url('enlaces/manuals_form/' . $lista['id_link']) ?>'>
											<span class="glyphicon glyphicon-edit" aria-hidden="true">
										</a>
						<?php
									echo "</td>";
									echo "</tr>";
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
		responsive: true,
		"pageLength": 50,
		"order": [[ 2, "asc" ]]
	});
});
</script>
<script>
	$(function() {
		$(".material").click(function() {
			var oID = $(this).attr("id");
			$.ajax({
				type: 'POST',
				url: base_url + '/admin/cargar-modal-material',
				data: {
					'idMaterial': oID
				},
				cache: false,
				success: function(data) {
					$('#tablaDatos').html(data);
				}
			});
		});
	});

	$(function() {
		$(".shop").click(function() {
			var oID = $(this).attr("id");
			$.ajax({
				type: 'POST',
				url: base_url + '/admin/loadModalShop',
				data: {
					'idMaterial': oID,
				},
				cache: false,
				success: function(data) {
					$('#tablaDatos').html(data);
				}
			});
		});
	});
</script>

<div id="page-wrapper">
	<br>

	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<i class="fa fa-tint"></i> SETTINGS - MATERIAL TYPE LIST
				</div>
				<div class="panel-body">
					<button type="button" class="btn btn-outline btn-primary btn-block material" data-toggle="modal" data-target="#modal" id="x">
						<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add a Material Type
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
					if ($info) {
					?>
						<form name="material_prices" id="material_prices" method="post" action="<?php echo base_url("prices/update_general_material_price"); ?>">

							<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables">
								<thead>
									<tr>
										<th class="text-center">Material Type</th>
										<th class="text-center">Price
											<button type="submit" class="btn btn-primary btn-xs" id="btnSubmit2" name="btnSubmit2">
												Update <span class="glyphicon glyphicon-edit" aria-hidden="true">
											</button>
										</th>
										<th class="text-center">Edit</th>
									</tr>
								</thead>
								<tbody>
									<?php
									foreach ($info as $lista) :
										echo "<tr>";
										echo "<td>" . $lista['material'] . "</td>";

										$unitPrice = $lista['material_price'];
										$unitPrice = $unitPrice ? $unitPrice : 0;
										echo "<td class='text-right'>";
									?>
										<input type="hidden" id="price" name="form[id][]" value="<?php echo $lista['id_material']; ?>" />
										$ <input type="text" id="price" name="form[price][]" class="form-control" placeholder="Price" value="<?php echo $unitPrice; ?>">
										<?php
										echo "</td>";
										echo "<td class='text-center'>";
										?>
										<button type="button" class="btn btn-outline btn-primary btn-xs material" data-toggle="modal" data-target="#modal" id="<?php echo $lista['id_material']; ?>">
											Edit <span class="glyphicon glyphicon-edit" aria-hidden="true">
										</button>
									<?php
										echo "</td>";
									endforeach;
									?>
								</tbody>
							</table>
						</form>
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
			"pageLength": 50
		});
	});
</script>
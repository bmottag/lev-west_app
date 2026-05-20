<script>
$(function(){ 
	$(".btn-outline").click(function () {	
			var oID = $(this).attr("id");
            $.ajax ({
                type: 'POST',
				url: base_url + '/serviceorder/cargarModalShopParts',
                data: {'idPartShop': oID},
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
		<div class="col-lg-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<i class="fa fa-anchor fa-fw"></i> SETTINGS - EQUIPMENT PARTS LIST
				</div>
				<div class="panel-body">

					<br>	

					<button type="button" class="btn btn-outline btn-primary btn-block" data-toggle="modal" data-target="#modal" id="x">
							<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add Equipment Part
					</button><br>

				<?php if (session()->getFlashdata('retornoExito')): ?>
					<div class="alert alert-success">
						<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
						<?= session()->getFlashdata('retornoExito') ?>
					</div>
				<?php endif; ?>

				<?php if (session()->getFlashdata('retornoError')): ?>
					<div class="alert alert-danger">
						<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
						<?= session()->getFlashdata('retornoError') ?>
					</div>
				<?php endif; ?>

				<?php
					if($info){
				?>				
					<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables">
						<thead>
							<tr>
								<th class="text-center">Description</th>
								<th class="text-center">Shop Name</th>
								<th class="text-center">Contact</th>
								<th class="text-center">Address</th>
								<th class="text-center">Mobile number</th>
								<th class="text-center">Email</th>
								<th class="text-center">Equipments</th>
								<th class="text-center">Actions</th>
							</tr>
						</thead>
						<tbody>							
						<?php
							foreach ($info as $lista):
									echo "<tr>";
									echo "<td>" . $lista['part_description'] . "</td>";
									echo "<td>" . $lista['shop_name'] . "</td>";
									echo "<td>" . $lista['shop_contact'] . "</td>";
									echo "<td>" . $lista['shop_address'] . "</td>";
									echo "<td>" . $lista['mobile_number'] . "</td>";
									echo "<td>" . $lista['shop_email'] . "</td>";
									echo "<td>" . $lista['equipments'] . "</td>";
									echo "<td class='text-center'>";
						?>
									<button type="button" class="btn btn-outline btn-primary btn-xs" data-toggle="modal" data-target="#modal" id="<?php echo $lista['id_part_shop']; ?>" >
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
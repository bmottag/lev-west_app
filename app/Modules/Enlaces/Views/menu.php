<script>
$(document).ready(function () {
	$(".btn-delete").click(function () {
			var oID = $(this).attr("id");

			//Activa icono guardando
			if(window.confirm('Are you sure you want to delete the Menu ?'))
			{
					$(".btn-delete").attr('disabled','-1');
					$.ajax ({
						type: 'POST',
						url: base_url + 'enlaces/delete_menu',
						data: {'identificador': oID},
						cache: false,
						success: function(data){

							if( data.result == "error" )
							{
								alert(data.mensaje);
								$(".btn-delete").removeAttr('disabled');
								return false;
							}

							if (data.status === "success")
							{
								$(".btn-delete").removeAttr('disabled');
								window.location.href = base_url + "/enlaces/menu";
							}
							else
							{
								alert('Error. Reload the web page.');
								$(".btn-delete").removeAttr('disabled');
							}
						},
						error: function(result) {
							alert('Error. Reload the web page.');
							$(".btn-delete").removeAttr('disabled');
						}

					});
			}
	});

	$(".btn-success").click(function () {	
			var oID = $(this).attr("id");
            $.ajax ({
                type: 'POST',
				url: base_url + 'enlaces/cargar-modal-menu',
                data: {'idMenu': oID},
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
					<i class="fa fa-cogs fa-fw"></i> MANAGE SYSTEM ACCESS
					</h4>
				</div>
			</div>
		</div>				
	</div>
	
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<i class="fa fa-link"></i> MENU
				</div>
				<div class="panel-body">
					<button type="button" class="btn btn-success btn-block" data-toggle="modal" data-target="#modal" id="x">
							<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add a Menu Link
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
								<th class="text-center">Menu name</th>
								<th class="text-center">Menu type</th>
								<th class="text-center">Menu URL</th>
								<th class="text-center">Menu icon</th>
								<th class="text-center">Order</th>
								<th class="text-center">Status</th>
								<th class="text-center">Action</th>
							</tr>
						</thead>
						<tbody>							
						<?php
							foreach ($info as $lista):
									echo "<tr>";
									echo "<td>" . $lista['menu_name'] . "</td>";
									echo "<td class='text-center'>";
									switch ($lista['menu_type']) {
										case 1:
											$valor = 'Left';
											$clase = "text-success";
											break;
										case 2:
											$valor = 'Top';
											$clase = "text-danger";
											break;
									}
									echo '<p class="' . $clase . '"><strong>' . $valor . '</strong></p>';
									echo "</td>";
									echo "<td>" . $lista['menu_url'] . "</td>";
									echo "<td class='text-center'>";
									echo '<button type="button" class="btn btn-default btn-circle"><i class="fa ' . $lista['menu_icon'] . '"></i>';
									echo "</td>";
									echo "<td class='text-center'>" . $lista['menu_order'] . "</td>";
									echo "<td class='text-center'>";
									switch ($lista['menu_state']) {
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
									<button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#modal" id="<?php echo $lista['id_menu']; ?>" >
										Edit <span class="glyphicon glyphicon-edit" aria-hidden="true">
									</button>

									<button type="button" id="<?php echo $lista['id_menu']; ?>" class='btn btn-danger btn-xs btn-delete' title="Delete">
										<i class="fa fa-trash-o"></i>
									</button>
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
		"pageLength": 50,
		"order": [[ 1, "asc" ],[ 4, "asc" ]]
	});
});
</script>
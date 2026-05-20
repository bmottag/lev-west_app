<script type="text/javascript" src="<?php echo base_url("assets/js/validate/enlaces/role_access.js?v=1.0.0"); ?>"></script>

<script>
$(function(){ 
	$(".btn-success").click(function () {	
			var oID = $(this).attr("id");
            $.ajax ({
                type: 'POST',
				url: base_url + 'enlaces/cargar-modal-role-access',
                data: {'idPermiso': oID},
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
					<i class="fa fa-puzzle-piece"></i> ROLE ACCESS
				</div>
				<div class="panel-body">
					<button type="button" class="btn btn-success btn-block" data-toggle="modal" data-target="#modal" id="x">
							<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add a Role Access
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
								<th class="text-center">Link name</th>
								<th class="text-center">Rol name</th>
								<th class="text-center">Edit/Delete</th>
								<th class="text-center">Menu Type</th>
								<th class="text-center">Menu Order</th>
								<th class="text-center">Link order</th>
							</tr>
						</thead>
						<tbody>							
						<?php
							foreach ($info as $lista):
									echo "<tr>";
									echo "<td>" . $lista['menu_name'] . "</td>";
									echo "<td>" . $lista['link_name'] . "</td>";
									echo "<td class='text-center'>";
									echo '<p class="' . $lista['estilos'] . '"><strong>' . $lista['rol_name'] . '</strong></p>';
									echo "</td>";
									
									echo "<td class='text-center'>";
						?>
									<button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#modal" id="<?php echo $lista['id_permiso']; ?>" >
										Edit <span class="glyphicon glyphicon-edit" aria-hidden="true">
									</button>
									
									<button type="button" id="<?php echo $lista['id_permiso']; ?>" class='btn btn-danger btn-xs' title="Delete">
											<i class="fa fa-trash-o"></i>
									</button>
						<?php
									echo "</td>";
									echo "<td>" . $lista['menu_type'] . "</td>";
									echo "<td>" . $lista['menu_order'] . "</td>";
									echo "<td>" . $lista['order'] . "</td>";
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
    $('#dataTables').DataTable( {
		"order": [[ 2, "asc" ],[ 4, "asc" ],[ 5, "asc" ],[ 6, "asc" ]],
		paging: false,
		"columnDefs": [
            {
                "targets": [ 4 ],
                "visible": false,
                "searchable": false
            },
            {
                "targets": [ 5 ],
                "visible": false,
                "searchable": false
            },
            {
                "targets": [ 6 ],
                "visible": false,
                "searchable": false
            }
        ]
    } );
} );
</script>
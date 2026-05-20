<script type="text/javascript" src="<?php echo base_url("assets/js/validate/programming/programming_workers.js?v=1.0.0"); ?>"></script>

<script>

function seleccionar_todo(){
   for (i=0;i<document.form.elements.length;i++)
      if(document.form.elements[i].type == "checkbox")
         document.form.elements[i].checked=1
} 


function deseleccionar_todo(){
   for (i=0;i<document.form.elements.length;i++)
      if(document.form.elements[i].type == "checkbox")
         document.form.elements[i].checked=0
} 

</script>

<div id="page-wrapper">
	<br>
	
	<!-- /.row -->
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-warning">
				<div class="panel-heading">
					<i class="fa fa-users"></i> <b>PLANNING - ADD PERSONNEL</b>
				</div>
				<div class="panel-body">

<a href="javascript:seleccionar_todo()">Check all</a> |
<a href="javascript:deseleccionar_todo()">Uncheck all</a> 
<br><br>

					<form  name="form" id="form" class="form-horizontal" method="post" >
						<input type="hidden" id="hddId" name="hddId" value="<?php echo $idProgramming; ?>"/>
								
					<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables">
						<thead>
							<tr class="info">
								<th class='text-center' width="15%">Check</th>
								<th class='text-center' width="85%">Worker</th>
							</tr>
						</thead>	
						
						<tbody>	
							<?php foreach ($workersList as $lista): ?>
							<tr>
								<td>
									<input type="checkbox" name="workers[]" value="<?php echo $lista['id_user']; ?>" style="margin:10px">
								</td>
								<td><?php echo esc($lista['first_name'] . ' ' . $lista['last_name']); ?></td>
							</tr>
							<?php endforeach; ?>
						</tbody>	
					</table>					
					
						<div class="form-group">							
							<div class="row" align="center">
								<div style="width:50%;" align="center">
									 <button type="button" id="btnSubmit" name="btnSubmit" class='btn btn-primary'>
									 	Save <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true">
									</button>
								</div>
							</div>
						</div>
						
						<div class="form-group">
							<div class="row" align="center">
								<div style="width:80%;" align="center">
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
							</div>
						</div>						
						
					</form>
					
				</div>
			</div>
		</div>
	</div>
</div>
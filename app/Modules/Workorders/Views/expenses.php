<script>
	function seleccionar_todo(){
	for (i=0;i<document.form_expenses.elements.length;i++)
		if(document.form_expenses.elements[i].type == "checkbox")
			document.form_expenses.elements[i].checked=1
	} 


	function deseleccionar_todo(){
	for (i=0;i<document.form_expenses.elements.length;i++)
		if(document.form_expenses.elements[i].type == "checkbox")
			document.form_expenses.elements[i].checked=0
	} 

	$(document).ready(function() {
		$('.js-example-basic-single').select2();
	});
</script>

<div id="page-wrapper">
	<br>

	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<a class="btn btn-gris btn-xs" href=" <?php echo base_url() . 'workorders/search/y'; ?> "><span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Go back </a>
					<i class="fa fa-money"></i> <strong>WORK ORDERS</strong>
				</div>
				<div class="panel-body">

					<ul class="nav nav-pills">
						<li><a href="<?php echo base_url('workorders/add_workorder/' . $information[0]["id_workorder"]) ?>">Edit</a>
						</li>
						<li><a href="<?php echo base_url('workorders/view_workorder/' . $information[0]["id_workorder"]) ?>">Asign rate</a>
						</li>
						<li><a href="<?php echo base_url('workorders/generaWorkTicketPDF/' . $information[0]["id_workorder"]) ?>" target="_blank">Download invoice</a>
						</li>
                        <?php
                        $userRol = session()->get("rol");
                        if (($userRol == ID_ROL_SUPER_ADMIN || $userRol == ID_ROL_ENGINEER || $userRol == ID_ROL_WORKORDER) && $information[0]['state'] != 4) {
                        ?>
                            <li class='active'><a href="<?php echo base_url('workorders/workorder_expenses/' . $information[0]["id_workorder"]) ?>">Workorder Expenses</a>
                            </li>
                        <?php } ?>
					</ul>
					<br>

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
					if ($information) {
						switch ($information[0]['state']) {
							case 0:
								$valor = 'On Field';
								$clase = "alert-danger";
								break;
							case 1:
								$valor = 'In Progress';
								$clase = "alert-warning";
								break;
							case 2:
								$valor = 'Revised';
								$clase = "alert-info";
								break;
							case 3:
								$valor = 'Send to the Client';
								$clase = "alert-success";
								break;
							case 4:
								$valor = 'Closed';
								$clase = "alert-danger";
								break;
							case 5:
								$valor = 'Accounting';
								$clase = "text-warning";
								$icono = "fa-list-alt";
								break;
						}
					?>
						<div class="row">
							<div class="col-lg-12">
								<div class="alert <?php echo $clase; ?>">
									<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
									This work order is <strong><?php echo $valor; ?></strong>
								</div>
							</div>
						</div>

					<?php } ?>

					<div class="row">
						<div class="col-lg-12">
							<div class="alert alert-info">
								<span class='fa fa-money' aria-hidden='true'></span>
								<strong>Work Order #: </strong><?php echo $information[0]["id_workorder"]; ?>
								<br><span class='fa fa-clock-o' aria-hidden='true'></span> <strong>Work Order Date: </strong><?php echo $information[0]["date"]; ?>
								<br><span class="fa fa-briefcase" aria-hidden="true"></span> <strong>Job Code/Name: </strong><?php echo $information[0]["job_description"]; ?>
								<?php if ($information[0]["notes"]) { ?>
									<br><strong>Job Code/Name - Notes: </strong><?php echo $information[0]["notes"]; ?>
								<?php } ?>
								<br><strong>Markup: </strong><?php echo $information[0]["markup"] . '%'; ?>
								<br><strong>Supervisor: </strong><?php echo $information[0]["name"]; ?>
								<br><strong>Observation: </strong><?php echo $information[0]["observation"]; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!--INICIO FORMULARIOS -->
	<?php if ($information) { ?>

		<!--INICIO WO EXPENSE -->
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-dark">
                    <div class="panel-heading">
                        <i class="fa fa-gears fa-fw"></i> <b>WO EXPENSES</b>
                    </div>
                    <div class="panel-body">
                        <?php if(!$jobDetails){ ?>
                            <div class="alert alert-danger ">
                                <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                                You need to add items to this job in order to assign expenses.
                            </div>
                        <?php }else{ ?>


                        <form  name="form_expenses" id="form_expenses" method="post" action="<?php echo base_url("workorders/save_wo_expenses"); ?>">
                            <input type="hidden" id="hddidWorkorder" name="hddidWorkorder" value="<?php echo $information[0]["id_workorder"]; ?>"/>
                            <input type="hidden" id="hddidJob" name="hddidJob" value="<?php echo $information[0]["fk_id_job"]; ?>"/>
        
                            <a href="javascript:seleccionar_todo()">Check all</a> |
                            <a href="javascript:deseleccionar_todo()">Uncheck all</a> 
                            <table class="table table-bordered table-striped table-hover table-condensed small">
                                <tr class="default">
                                    <td colspan="6">
                                        Select an Item <br>
                                        <select name="item_job_detail" id="item_job_detail" class="form-control js-example-basic-single" required>
                                            <option value=''>Select...</option>
                                            <?php for ($i = 0; $i < count($jobDetails); $i++) { ?>
                                                <option value="<?php echo $jobDetails[$i]["id_job_detail"]; ?>"><?php echo $jobDetails[$i]["chapter_name"] . " - Item: " . $jobDetails[$i]["chapter_number"] . "." . $jobDetails[$i]["item"] . " " . $jobDetails[$i]["description"]; ?></option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr class="default">
                                    <th class="text-center">Submodule</th>
                                    <th class="text-center">Description</th>
                                    <th class="text-center">Unit</th>
                                    <th class="text-center">QTY</th>
                                    <th class="text-center">Unit Price</th>
                                    <th class="text-center">Line Total</th>
                                </tr>
                                <?php
                                    // INICIO PERSONAL
                                    if($workorderPersonal)
                                    { 
                                        foreach ($workorderPersonal as $data):                                         
                                            echo '<tr>
                                                    <td>';
                                            echo '<p class="text-warning">';
                                            $check = array(
                                                'name' => 'item[]',
                                                'id' => 'item',
                                                'value' => 'personal__' . $data['id_workorder_personal'] . '__' . $data['value'],
                                                'checked' => false,
                                                'style' => 'margin:10px'
                                            );
                                            echo form_checkbox($check);
                                            echo '<strong>Personal</strong></p>';								
                                            echo '</td>
                                                    <td>' . $data['employee_type'] . ' - ' . $data['description'] . ' by ' . $data['name'] . '</td>
                                                    <td class="text-center">Hours</td>
                                                    <td class="text-center">' . $data['hours'] . '</td>
                                                    <td class="text-right">$ ' . number_format($data['rate'], 2) . '</td>
                                                    <td class="text-right">$ ' . number_format($data['value'], 2) . '</td>';
                                            echo '</tr>';
                                        endforeach;
                                    }

                                    // INICIO MATERIAL
                                    if($workorderMaterials)
                                    { 
                                        foreach ($workorderMaterials as $data):
                                            $description = $data['description'] . ' - ' . $data['material'];
                                            if($data['markup'] > 0){
                                                $description = $description . ' - Plus M.U.';
                                            }

                                            echo '<tr>
                                                    <td>';
                                            echo '<p class="text-success">';
                                            $check = array(
                                                'name' => 'item[]',
                                                'id' => 'item',
                                                'value' => 'materials__' . $data['id_workorder_materials'] . '__' . $data['value'],
                                                'checked' => false,
                                                'style' => 'margin:10px'
                                            );
                                            echo form_checkbox($check);
                                            echo '<strong>Materials</strong></p>';								
                                            echo '</td>
                                                    <td>' . $description . '</td>
                                                    <td align="center">' . $data['unit'] . '</td>
                                                    <td align="center">' . $data['quantity'] . '</td>
                                                    <td align="right">$ ' . number_format($data['rate'], 2) . '</td>
                                                    <td align="right">$ ' . number_format($data['value'], 2) . '</td>';
                                            echo '</tr>';
                                        endforeach;
                                    }

                                    // INICIO RECEIPT
                                    if($workorderReceipt)
                                    { 
                                        foreach ($workorderReceipt as $data):
                                            $description = $data['description'] . ' - ' . $data['place'];
                                            if($data['markup'] > 0){
                                                $description = $description . ' - Plus M.U.';
                                            }

                                            echo '<tr>
                                                    <td>';
                                            echo '<p class="text-violeta">';
                                            $check = array(
                                                'name' => 'item[]',
                                                'id' => 'item',
                                                'value' => 'receipt__' . $data['id_workorder_receipt'] . '__' . $data['value'],
                                                'checked' => false,
                                                'style' => 'margin:10px'
                                            );
                                            echo form_checkbox($check);
                                            echo '<strong>Receipt</strong></p>';								
                                            echo '</td>
                                                    <td>' . $description . '</td>
                                                    <td align="center"> Receipt </td>
                                                    <td align="center"> 1 </td>
                                                    <td align="right">$ ' . number_format($data['value'], 2) . '</td>
                                                    <td align="right">$ ' . number_format($data['value'], 2) . '</td>';
                                            echo '</tr>';
                                        endforeach;
                                    }

                                    // INICIO EQUIPMENT
                                    if($workorderEquipment)
                                    { 
                                        foreach ($workorderEquipment as $data):
                                            echo '<tr>
                                                    <td>';
                                            echo '<p class="text-info">';
                                            $check = array(
                                                'name' => 'item[]',
                                                'id' => 'item',
                                                'value' => 'equipment__' . $data['id_workorder_equipment'] . '__' . $data['value'],
                                                'checked' => false,
                                                'style' => 'margin:10px'
                                            );
                                            echo form_checkbox($check);
                                            echo '<strong>Equipment</strong></p>';								
                                            echo '</td>
                                                    <td>';
                                                    
                                                    if($data['fk_id_attachment'] != "" && $data['fk_id_attachment'] != 0){
                                                        echo '<strong>ATTACHMENT: </strong>' . $data["attachment_number"] . " - " . $data["attachment_description"] . ' ';
                                                    }

                                            //si es tipo miscellaneous -> 8, entonces la description es diferente
                                            if($data['fk_id_type_2'] == 8){
                                                $equipment = $data['miscellaneous'] . " - " . $data['other'];
                                                $description = preg_replace('([^A-Za-z0-9 ])', ' ', $data['description']);
                                            }else{
                                                $equipment = "<strong>Unit #: </strong>" .$data['unit_number'] . " <strong>Make: </strong>" . $data['make'] . " <strong>Model: </strong>" . $data['model'];
                                                $description = $data['v_description'] . " - " . preg_replace('([^A-Za-z0-9 ])', ' ', $data['description']);
                                            }
                                            
                                            echo  $equipment . ' <strong>Description: </strong>' . $description . ', operated by ' . $data['operatedby'];
                                            echo '</td>                                                        
                                                    <td align="center">Hours</td>
                                                    <td align="center">' . $data['hours'] . '</td>
                                                    <td align="right">$ ' . number_format($data['rate'], 2) . '</td>
                                                    <td align="right">$ ' . number_format($data['value'], 2) . '</td>';
                                            echo '</tr>';
                                        endforeach;
                                    }

                                    // INICIO SUBCONTRATISTAS OCASIONALES
                                    if($workorderOcasional)
                                    { 
                                        foreach ($workorderOcasional as $data):
                                            $description = $data['description'];
                                            if($data['markup'] > 0){
                                                $description = $description . ' - Plus M.U.';
                                            }

                                            echo '<tr>
                                                <td>';
                                            echo '<p class="text-primary">';
                                            $check = array(
                                                'name' => 'item[]',
                                                'id' => 'item',
                                                'value' => 'ocasional__' . $data['id_workorder_ocasional'] . '__' . $data['value'],
                                                'checked' => false,
                                                'style' => 'margin:10px'
                                            );
                                            echo form_checkbox($check);
                                            echo '<strong>Ocasional</strong></p>';								
                                            echo '</td>
                                                    <td>' . $description . '</td>
                                                    <td align="center">' . $data['unit'] . '</td>
                                                    <td align="center">' . $data['hours'] . '</td>
                                                    <td align="right">$ ' . number_format($data['rate'], 2) . '</td>
                                                    <td align="right">$ ' . number_format($data['value'], 2) . '</td>';
                                            echo '</tr>';
                                        endforeach;
                                    }

                                ?>
                                <tr class="default">
                                    <th colspan="6">
                                        <button type="submit" class="btn btn-primary btn-xs" id="btnSubmit2" name="btnSubmit2" >
                                            Save Expenses <span class="glyphicon glyphicon-edit" aria-hidden="true">
                                        </button>
                                    </th>
                                </tr>
                            </table>
                        </form>
                        <?php } ?>

                        <?php 
                            if($workorderExpenses){
                        ?>
                            <hr>
                            <h2>Expenses</h2>
                            <table width="100%" class="table table-hover dataTable no-footer small" id="dataTables">
                                <thead>
                                    <tr>
                                        <th class="text-center">Submodule</th>
                                        <th>Item</th>
                                        <th>Work Done</th>
                                        <th class="text-right">Expense Value</th>
                                    </tr>
                                </thead>
                                <tbody>					
                                <?php
                                    foreach ($workorderExpenses as $data):
                                        echo "<tr>";
                                        echo "<td class='text-center'>" . ucfirst($data['submodule']) . "</td>";
                                        echo "<td>";
                                        echo $data["chapter_name"] . " - Item: " . $data["chapter_number"] . "." . $data["item"] . " " . $data["description"];
                                        echo "</td>";
                                        echo "<td>" . $data['observation'] . "</td>";
                                        echo "<td class='text-right'>$ " . number_format($data['expense_value'],2) . "</td>";
                                        ?>
                                            <td class="text-center">
                                                <a class='btn btn-danger btn-xs' href='<?php echo base_url('workorders/deleteRecordExpenses/' . $data['submodule'] . '/' . $data['id_workorder_expense'] . '/' . $data['fk_id_submodule'] . '/' . $data['fk_id_workorder']) ?>' id="btn-delete">
                                                    <i class="fa fa-trash-o"></i>
                                                </a>      
                                            </td>
                                        <?php
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
		<!--FIN WO EXPENSE -->
	<?php } ?>

</div>
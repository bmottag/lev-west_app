<script type="text/javascript" src="<?php echo base_url("assets/js/validate/claims/claims.js?v=1.0.0"); ?>"></script>
<script>
$(function(){
	$(".btn-success").click(function () {
		var oID = $(this).attr("id");
        $.ajax ({
            type: 'POST',
			url: base_url + 'claims/cargarModalClaimState',
			data: {'idClaim': oID},
            cache: false,
            success: function (data) {
                $('#tablaDatos').html(data);
            }
        });
	});

	const formatCurrency = value => {
		const num = parseFloat(value);
		if (isNaN(num)) return '';
		return new Intl.NumberFormat('en-US', {
			style: 'currency',
			currency: 'USD',
			minimumFractionDigits: 2
		}).format(num);
	};

	$(document).on("click", "#confirmSubmit", function () {
		const formData = $("#form_claims").serialize();

		$.ajax({
			type: "POST",
			url: base_url + "claims/update_claim",
			data: formData,
			success: function (response) {
				window.location.href = base_url + "claims/claim_history/" + $("#hddIdClaim").val();
			},
			error: function () {
				alert("Error al guardar la información");
			}
		});
	});

	$("#openConfirmModal").click(function () {
		const formData = $("#form_claims").serializeArray();
		let dataPorId = {};
		formData.forEach(item => {
			const match = item.name.match(/^records\[(\d+)\]\[(.+)\]$/);
			if (match) {
				const id = match[1];
				const field = match[2];
				if (!dataPorId[id]) {
					dataPorId[id] = {};
				}
				dataPorId[id][field] = item.value;
			}
		});

		let tieneDatos = false;
		let rowsHtml = "";

		for (let id in dataPorId) {
			const r = dataPorId[id];
			const quantity = r['quantity'];
			const cost = r['cost'];

			if (quantity || cost) {
				tieneDatos = true;
				rowsHtml += `
					<tr>
						<td>${r['chapter_number']}.${r['item']}</td>
						<td class="text-left">${r['description']}</td>
						<td class="text-right">${quantity}</td>
						<td class="text-right">${formatCurrency(cost)}</td>
					</tr>
				`;
			}
		}

		let contentHtml = tieneDatos ? `
			<div class="modal-header">
				<h4 class="modal-title">Confirm Affected Records</h4>
			</div>
			<div class="modal-body">
				<table class="table table-bordered table-striped table-hover small">
					<thead>
						<tr class="info">
							<th>Item</th>
							<th>Description</th>
							<th class="text-right">Qty</th>
							<th class="text-right">Cost</th>
						</tr>
					</thead>
					<tbody>
						${rowsHtml}
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-primary" id="confirmSubmit">Confirm</button>
			</div>
		` : `
			<div class="modal-header">
				<h4 class="modal-title">Sin datos válidos</h4>
			</div>
			<div class="modal-body">
				<p>No hay datos con quantity o cost para guardar.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
			</div>
		`;

		$("#infoConfirm").html(contentHtml);
		$("#modalConfirm").modal("show");
	});


});
</script>

<div id="page-wrapper">
	<br>
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<a class="btn btn-primary btn-xs" href="<?php echo base_url().'claims/' . $claimsInfo[0]['fk_id_job']; ?> ">
						<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Go back
					</a>
					<i class="fa fa-money"></i> <strong>CLAIM DETAILS & ASSIGNED APU</strong>
				</div>
				<div class="panel-body">

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

					<ul class="nav nav-pills">
						<li class='active'><a href="<?php echo base_url("claims/upload_apu/" . $claimsInfo[0]['id_claim']); ?>">Current Claim Form</a>
						</li>
						<li><a href="<?php echo base_url("claims/claim_history/" . $claimsInfo[0]['id_claim']); ?>">Claim History</a>
						</li>
					</ul>
					<br>
					<div class="row">
						<div class="col-md-6">
							<div class="panel panel-info">
								<div class="panel-heading">
									<i class="fa fa-bomb"></i> <strong>CLAIM INFORMATION</strong>
								</div>
								<div class="panel-body">
									<div class="alert alert-info">
										<strong>Claim Number: </strong><?php echo $claimsInfo?$claimsInfo[0]["claim_number"]:""; ?>
										<br><strong>Job Code/Name: </strong><br><?php echo $claimsInfo?$claimsInfo[0]["job_description"]:""; ?>
										<br><strong>Date Issue: </strong><br><?php echo $claimsInfo?$claimsInfo[0]["date_issue_claim"]:""; ?>
										<br><strong>Observation: </strong><br><?php echo $claimsInfo?$claimsInfo[0]["observation_claim"]:""; ?>
										<br>
										<a href='<?php echo base_url('claims/generaProgressreportXLS/' . $claimsInfo[0]['fk_id_job']); ?>' target="_blank"> 
											<strong>Download Project Progress Report: </strong><img src='<?php echo base_url('images/xls.png'); ?>'>
										</a>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-6">
							<div class="chat-panel panel panel-success">
								<div class="panel-heading">
									<button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#modal" id="<?php echo $claimsInfo[0]['id_claim']; ?>" >
										<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add Info
									</button>
									<i class="fa fa-comments fa-fw"></i> Status History
								</div>
								<div class="panel-body">
									<ul class="chat">
										<?php 
											if($claimsHistory)
											{
												foreach ($claimsHistory as $data):		
													switch ($data['state_claim']) {
														case 1:
															$valor = 'New Claim';
															$clase = "text-violeta";
															$icono = "fa-flag";
															break;
														case 2:
															$valor = 'Send to client';
															$clase = "text-success";
															$icono = "fa-share";
															break;
														case 3:
															$valor = 'Partial Payment';
															$clase = "text-primary";
															$icono = "fa-star-half-empty";
															break;
														case 4:
															$valor = 'Hold Back';
															$clase = "text-warning";
															$icono = "fa-bullhorn";
															break;
														case 5:
															$valor = 'Short Payment';
															$clase = "text-warning";
															$icono = "fa-thumbs-o-down";
															break;
														case 6:
															$valor = 'Final Payment';
															$clase = "text-danger";
															$icono = "fa-bomb";
															break;
													}
										?>
										<li class="right clearfix">
											<span class="chat-img pull-right">
												<small class="pull-right text-muted">
													<i class="fa fa-clock-o fa-fw"></i> <?php echo $data['date_issue_claim_state']; ?>
												</small>
											</span>
											<div class="chat-body clearfix">
												<div class="header">
													<span class="glyphicon glyphicon-user" aria-hidden="true"></span>
													<strong class="primary-font"><?php echo $data['first_name']; ?></strong>
												</div>
												<p><?php echo $data['message_claim']; ?></p>
												<p class="<?php echo $clase; ?>">
													<strong><i class="fa <?php echo $icono; ?> fa-fw"></i><?php echo $valor; ?></strong>
												</p>
											</div>
										</li>
										<?php
												endforeach;
											}
										?>
									</ul>
								</div>
							</div>
						</div>
					</div> <!-- Fin de fila superior -->

					<a href="<?php echo base_url("claims/add_apu/" . $claimsInfo[0]["fk_id_job"] . "/" . $claimsInfo[0]["id_claim"]); ?>" class="btn btn-primary btn-block"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add LIC to the Claim</a>

					<hr>
					<form id="form_claims" method="post" action="<?php echo base_url("claims/update_claim"); ?>">
						<input type="hidden" id="hddIdClaim" name="hddIdClaim" value="<?php echo $claimsInfo[0]['id_claim']; ?>" />
						<?php
						if($chapterList){
							foreach ($chapterList as $lista):
								$jobDetails = $jobDetailsByChapter[$lista['chapter_number']] ?? false;

								if($jobDetails){
						?>
								<div class="panel-body">
									<div class="panel-group" id="accordion">	
										<h2><?php echo $lista['chapter_name']; ?></h2>
										<?php
											foreach ($jobDetails as $data):
												$class = "default";
										?>
											<input type="hidden" name="records[<?php echo $data['id_job_detail']; ?>][id_job_detail]" value="<?php echo $data['id_job_detail']; ?>" />
											<input type="hidden" name="records[<?php echo $data['id_job_detail']; ?>][unit_price]" value="<?php echo $data['unit_price']; ?>" />

											<input type="hidden" name="records[<?php echo $data['id_job_detail']; ?>][chapter_number]" value="<?php echo $data['chapter_number']; ?>" >
											<input type="hidden" name="records[<?php echo $data['id_job_detail']; ?>][item]" value="<?php echo $data['item']; ?>" >
											<input type="hidden" name="records[<?php echo $data['id_job_detail']; ?>][description]" value="<?php echo $data['description']; ?>" >

											<div class="panel panel-<?php echo $class ?>" >
												<div class="panel-heading">
													<h4 class="panel-title">
														<table width="100%" class="table table-striped table-bordered table-hover small" id="dataTables">
														<?php
															echo "<tr class='" . $class . "'>";
															echo "<td width='4%' class='text-center'><p class='text-" . $class . "'><b>Item</b><br>" . $data['chapter_number'] . "." . $data['item'] . "</p></td>";
															echo "<td width='40%'><p class='text-" . $class . "'><b>Description</b><br>" . $data['description'] . "</p></td>";
															echo "<td width='4%' class='text-center'><p class='text-" . $class . "'><b>Unit</b><br>" . $data['unit'] . "</p></td>";
															echo "<td width='4%' class='text-center'><p class='text-" . $class . "'><b>Qty</b><br>" . $data['quantity'] . "</p></td>";
															echo "<td width='8%' class='text-right'><p class='text-" . $class . "'><b>Unit Price</b><br>$ " . number_format($data['unit_price'],2) . "</p></td>";
															echo "<td width='10%' class='text-right'><p class='text-" . $class . "'><b>Extended Amount</b><br>$ " . number_format($data['extended_amount'],2) . "</p></td>";
														?>
														<td class="text-right">
															<b>Qty Claim <?php echo $claimsInfo?$claimsInfo[0]["claim_number"]:"";?></b>
															<input type="text" name="records[<?php echo $data['id_job_detail']; ?>][quantity]" class="form-control" value="<?php echo $data['quantity_claim']; ?>" placeholder="Quantity" >
														</td>

														<td class="text-right">
															<b>Cost Claim <?php echo $claimsInfo?$claimsInfo[0]["claim_number"]:"";?></b>
															<input type="text" name="records[<?php echo $data['id_job_detail']; ?>][cost]" class="form-control" value="<?php echo $data['cost']; ?>" placeholder="Cost" >
														</td>
														<?php
															echo "</tr>";
														?>
														</table>
													</h4>
												</div>
											</div>
										<?php
											endforeach;
										?>
									</div>
								</div>
						<?php 
								}
							endforeach;
							}
						?>

						<button type="button" class="btn btn-primary" id="openConfirmModal">
							Save Information <span class="glyphicon glyphicon-floppy-disk"></span>
						</button>


					</form>

				</div>
			</div>
		</div>
	</div>	
</div>

<!--INICIO Modal para adicionar ESTADO -->
<div class="modal fade text-center" id="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">    
	<div class="modal-dialog" role="document">
		<div class="modal-content" id="tablaDatos">

		</div>
	</div>
</div>                
<!--FIN Modal para adicionar ESTADO -->


<div class="modal fade text-center" id="modalConfirm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">    
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content" id="infoConfirm">

		</div>
	</div>
</div>
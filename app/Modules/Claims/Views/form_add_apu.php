<script type="text/javascript" src="<?php echo base_url("assets/js/validate/claims/add_apu.js?v=1.0.0"); ?>"></script>

<div id="page-wrapper">
	<br>
	
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<a class="btn btn-primary btn-xs" href=" <?php echo base_url().'claims/upload_apu/' . $idClaim; ?> "><span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Go back </a>
					<i class="fa fa-money"></i> 
					<strong>LIC<br>
					Job Code/Name:</strong> <?php echo $jobInfo[0]['job_description']; ?>
				</div>
				<div class="panel-body">
					<div class="alert alert-danger">
						<strong>Select </strong> all LIC to be assigned.
					</div>
					<form  name="form" id="form" class="form-horizontal" method="post" >
						<input type="hidden" id="hddId" name="hddId" value="<?php echo $idClaim; ?>"/>

						<?php
						if($chapterList){
							foreach ($chapterList as $lista):
								$jobDetails = $jobDetailsByChapter[$lista['chapter_number']] ?? false;

								if($jobDetails){
						?>
								<div class="panel-body">
									<h2><?php echo $lista['chapter_name']; ?></h2>

									<table width="100%" class="table table-striped table-bordered table-hover small" id="dataTables">
										<thead>
											<tr>
												<th width='5%' class="text-center">Check</th>
												<th width='5%'>Item</th>
												<th width='40%' class="text-center">Description</th>
												<th width='5%' class="text-center">Unit</th>
												<th width='5%' class="text-center">Qty</th>
												<th width='10%' class="text-center">Unit Price</th>
												<th width='20%' class="text-center">Extended Amount</th>
											</tr>
										</thead>
										<tbody>
											<?php
												foreach ($jobDetails as $data):
													$found = $data['found'];

													echo "<tr>";
													echo '<td class="text-center">';
													$check = array(
														'name' => 'apu[]',
														'id' => 'apu',
														'value' => $data['id_job_detail'],
														'checked' => $found,
														'style' => 'margin:10px'
													);						
													echo form_checkbox($check);
													echo '</td>';
													echo "<td class='text-center'>" . $data['chapter_number'] . "." . $data['item'] . "</td>";
													echo "<td>" . $data['description'] . "</td>";
													echo "<td class='text-center'>" . $data['unit'] . "</td>";
													echo "<td class='text-center'>" . $data['quantity'] . "</td>";
													echo "<td class='text-right'>$ " . number_format($data['unit_price'],2) . "</td>";
													echo "<td class='text-right'>$ " . number_format($data['extended_amount'],2) . "</td>";
													echo "</tr>";
												endforeach;
											?>
										</tbody>
									</table>
								</div>
						<?php 
								}
							endforeach;
							}
						?>
								
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

						<div class="form-group">
							<div class="row" align="center">
								<div style="width:50%;" align="center">
									<button type="button" id="btnSubmit" name="btnSubmit" class="btn btn-primary" >
										Save <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true">
									</button> 
								</div>
							</div>
						</div>
						
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
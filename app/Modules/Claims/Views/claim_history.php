<script type="text/javascript" src="<?php echo base_url("assets/js/validate/claims/claims.js?v=2"); ?>"></script>

<div id="page-wrapper">
	<br>
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<a class="btn btn-primary btn-xs" href="<?php echo base_url().'claims/' . $claimsInfo[0]['fk_id_job']; ?> ">
						<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Go back
					</a>
					<i class="fa fa-money"></i> <strong>CLAIM HISTORY</strong>
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
						<li ><a href="<?php echo base_url("claims/upload_apu/" . $claimsInfo[0]['id_claim']); ?>">Current Claim Form</a>
						</li>
						<li class='active'><a href="<?php echo base_url("claims/claim_history/" . $claimsInfo[0]['id_claim']); ?>">Claim History</a>
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
					</div> 
					<hr>

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
												<th width='4%'>Item</th>
												<th width='30%' class="text-center">Description</th>
												<th width='4%' class="text-center">Unit</th>
												<th width='4%' class="text-center">Qty</th>
												<th width='8%' class="text-center">Unit Price</th>
												<th width='18%' class="text-center">Extended Amount</th>

												<?php 
													if (isset($allClaims) && $allClaims) {
														foreach ($allClaims as $claim) : 
												?>
														<th width='7%' class='text-center'><?php echo "Qty Claim " . $claim['claim_number']; ?></th>
														<th width='7%' class='text-center'><?php echo "Cost Claim " . $claim['claim_number']; ?></th>
												<?php 
														endforeach; 
													}
												?>
											</tr>
										</thead>
										<tbody>
											<?php
												foreach ($jobDetails as $data):

													// Initialize total claimed cost for the current job deta
													$totalClaimedCost = 0;

													// First pass: calculate totalClaimedCost
													if (isset($allClaims) && $allClaims) {
														foreach ($allClaims as $claim) {
															$claimInfo = $claimDetailMap[$claim['id_claim']][$data['id_job_detail']] ?? null;

															if (isset($claimInfo['cost'])) {
																$totalClaimedCost += (float)$claimInfo['cost'];
															}
														}
													}

													// Determine row class
													$extendedAmount = (float)$data['extended_amount'];
													$rowClass = '';
													if($extendedAmount > 0){
														if ($totalClaimedCost > $extendedAmount) {
															$rowClass = 'text-danger';
														} elseif ($totalClaimedCost >= 0.8 * $extendedAmount) {
															$rowClass = 'text-primary';
														}
													}


													echo "<tr class='$rowClass'>";
													echo "<td class='text-center'>" . $data['chapter_number'] . "." . $data['item'] . "</td>";
													echo "<td>" . $data['description'] . "</td>";
													echo "<td class='text-center'>" . $data['unit'] . "</td>";
													echo "<td class='text-center'>" . $data['quantity'] . "</td>";
													echo "<td class='text-right'>$ " . number_format($data['unit_price'],2) . "</td>";
													echo "<td class='text-right'>$ " . number_format($data['extended_amount'],2) . "</td>";
													if (isset($allClaims) && $allClaims) {
														foreach ($allClaims as $claim) {
															$claimInfo = $claimDetailMap[$claim['id_claim']][$data['id_job_detail']] ?? null;

															$qty  = $claimInfo['quantity_claim'] ?? '';
															$cost = $claimInfo['cost'] ?? '';

															echo "<td class='text-center'>{$qty}</td>";
															echo "<td class='text-right'>$ " . number_format((float)$cost,2) . "</td>";
														}
													}
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

				</div>
			</div>
		</div>
	</div>	
</div>

<script>
$(document).ready(function() {
	$('#dataTables').DataTable({
		responsive: false,
		"ordering": false,
		paging: false,
		"searching": false,
		"info": false
	});
});
</script>
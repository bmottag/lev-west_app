<div id="page-wrapper">
	<br>
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-warning">
				<div class="panel-heading">
					<h4 class="list-group-item-heading">
						<a class="btn btn-warning btn-xs" href=" <?php echo base_url($dashboardURL); ?> "><span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Go to Dashboard </a> 
						<i class="fa fa-car fa-fw"></i> Trailers Inspections
					</h4>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<i class="fa fa-car"></i> No inspection for two months or more
				</div>
				<div class="panel-body">
					<?php foreach ($trailer_not_inspect as $not) :
						echo $not['description'] . "<br>";
					endforeach; ?>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<i class="fa fa-car"></i> Trailers
				</div>
				<div class="panel-body">
					<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables">
						<thead>
							<tr>
								<th class="text-center">Trailer</th>
								<th class="text-center">Date Issue</th>
								<th class="text-center">Lights</th>
								<th class="text-center">Tires</th>
								<th class="text-center">Clean</th>
								<th class="text-center">Slings</th>
								<th class="text-center">Chains</th>
								<th class="text-center">Ratchet</th>
								<th class="text-center">Comments</th>
							</tr>
						</thead>
						<tbody>
							<?php 
								if($trailer_inspect):
									foreach ($trailer_inspect as $trailer) :
										echo "<tr>";
										echo "<td class='text-center'>" . $trailer["description"] . "</td>";
										echo "<td class='text-center'>" . $trailer["date_issue"] . "</td>";
										echo "<td class='text-center'>";
										if ($trailer["trailer_lights"] == 2) {
											echo "Fail";
										} else if ($trailer["trailer_lights"] == 1) {
											echo "Pass";
										} else {
											echo "N/A";
										}
										echo "</td>";
										echo "<td class='text-center'>";
										if ($trailer["trailer_tires"] == 2) {
											echo "Fail";
										} else if ($trailer["trailer_tires"] == 1) {
											echo "Pass";
										} else {
											echo "N/A";
										}
										echo "</td>";
										echo "<td class='text-center'>";
										if ($trailer["trailer_slings"] == 2) {
											echo "Fail";
										} else if ($trailer["trailer_slings"] == 1) {
											echo "Pass";
										} else {
											echo "N/A";
										}
										echo "</td>";
										echo "<td class='text-center'>";
										if ($trailer["trailer_clean"] == 2) {
											echo "Fail";
										} else if ($trailer["trailer_clean"] == 1) {
											echo "Pass";
										} else {
											echo "N/A";
										}
										echo "</td>";
										echo "<td class='text-center'>";
										if ($trailer["trailer_chains"] == 2) {
											echo "Fail";
										} else if ($trailer["trailer_chains"] == 1) {
											echo "Pass";
										} else {
											echo "N/A";
										}
										echo "</td>";
										echo "<td class='text-center'>";
										if ($trailer["trailer_ratchet"] == 2) {
											echo "Fail";
										} else if ($trailer["trailer_ratchet"] == 1) {
											echo "Pass";
										} else {
											echo "N/A";
										}
										echo "</td>";
										echo "<td class='text-center'>" . $trailer["trailer_comments"] . "</td>";
										echo "</tr>";
									endforeach; 
								endif; 
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
		$('#dataTables').DataTable({
			responsive: true,
			"pageLength": 25,
			order: [
				[1, 'desc']
			]
		});
	});
</script>
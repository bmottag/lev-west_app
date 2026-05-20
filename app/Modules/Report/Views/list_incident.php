
        <div id="page-wrapper">

			<br>
			<div class="row">
				<div class="col-md-12">
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h4 class="list-group-item-heading">
								<i class="fa fa-bar-chart-o fa-fw"></i> REPORT CENTER
							</h4>
						</div>
					</div>
				</div>
				<!-- /.col-lg-12 -->				
			</div>

            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
							<a class="btn btn-success" href=" <?php echo base_url().'report/searchByDateRange/incident'; ?> "><span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Go back </a> 
							<i class="fa fa-ambulance fa-fw"></i> <strong>INCIDENCES</strong> - INCIDENT/ACCIDENT REPORT
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
						<div class="alert alert-info">
							<strong>From Date: </strong><?php echo $from; ?> 
							<strong>To Date: </strong><?php echo $to; ?> 
						</div>
						<?php
							if(!$info){
						?>
                            <div class="alert alert-danger">
                                No data was found matching your criteria. 
                            </div>
						<?php
							}else{
						?>
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables">
                                <thead>
                                    <tr>
										<th>#</th>
										<th>Job Code/Name</th>
										<th>Reported by</th>
										<th>Incident type</th>
										<th>Incident date</th>
										<th>What happened?</th>
										<th>Download</th>
                                    </tr>
                                </thead>
                                <tbody>							
								<?php
									foreach ($info as $lista):
										echo "<tr>";
										echo "<td class='text-center'>" . $lista['id_incident'] . "</td>";
										echo "<td>" . $lista['job_description'] . "</td>";
										echo "<td>" . $lista['name'] . "</td>";
										echo "<td>" . $lista['incident_type'] . "</td>";
										echo "<td class='text-center'>" . $lista['date_incident'] . "</td>";
										echo "<td>" . $lista['what_happened'] . "</td>";
										echo "<td class='text-center'>";
							?>
	<a href='<?php echo base_url('incidences/generaPDF/' . $lista['id_incident'] . '/' . 2 ); ?>' target="_blank">PDF <img src='<?php echo base_url('images/pdf.png'); ?>' ></a>	
							<?php
										echo "</td>";
										echo "</tr>";
									endforeach;
								?>
                                </tbody>
                            </table>
						<?php }	?>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
			

        </div>
        <!-- /#page-wrapper -->

    <!-- Tables -->
    <script>
    $(document).ready(function() {
        $('#dataTables').DataTable({
            responsive: true
        });
    });
    </script>
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
							<a class="btn btn-success btn-xs" href=" <?php echo base_url().'report/searchByDateRange/dailyInspection'; ?> "><span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Go back </a> 
                            <i class="fa fa-search fa-fw"></i> MAINTENANCE PROGRAM
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
						
					<div class="row">							
						<div class="col-lg-6">
							<div class="alert alert-info">
								<strong>From Date: </strong><?php echo $from; ?> 
								<strong>To Date: </strong><?php echo $to; ?> 
	<?php if($info){ ?>
								<br><strong>Download to: </strong>
								
	<a href='<?php echo base_url('report/generaInsectionDailyPDF/' . $employee . '/' . $vehicleId . '/' . $trailerId . '/' . $from . '/' . $to ); ?>' target="_blank">PDF <img src='<?php echo base_url('images/pdf.png'); ?>' ></a>	
					 
	<?php } ?>
							</div>
						</div>
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
                                        <th class='text-center'>Date</th>
										<th class='text-center'>Type</th>
										<th class='text-center'>Description</th>
										<th class='text-center'>Done By</th>
										<th class='text-center'>Stock Description</th>
										<th class='text-center'>Next Hours Maintenance</th>
										<th class='text-center'>Next Date Maintenance</th>
                                    </tr>
                                </thead>
                                <tbody>							
								<?php
									$total = 0;
									foreach ($info as $lista):
											echo "<tr>";
											echo "<td class='text-center'>" . $lista['date_maintenance'] . "</td>";
											echo "<td>" . $lista['maintenance_description'] . "</td>";
											echo "<td>" . $lista['maintenance_type'] . "</td>";
											echo "<td>" . $lista['done_by'] . "</td>";
											echo "<td>" . $lista['stock_description'] . "</td>";
											echo "<td>" . $lista['next_hours_maintenance'] . "</td>";
											echo "<td>" . $lista['next_date_maintenance'] . "</td>";							
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
            responsive: true,
			"pageLength": 100
        });
    });
    </script>

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
							<a class="btn btn-success" href=" <?php echo base_url().'report/searchByDateRange/workorder'; ?> "><span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Go back </a> 
                            <i class="fa fa-money fa-fw"></i> WORK ORDER REPORT
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
						<div class="alert alert-info">
							<strong>From Date: </strong><?php echo $from; ?> 
							<strong>To Date: </strong><?php echo $to; ?>
<?php if($info){ ?>
							<br><strong>Download to: </strong>
							
<a href='<?php echo base_url('report/generaWorkOrderXLS/' . $jobId. '/' . $from . '/' . $to ); ?>'>Excel <img src='<?php echo base_url('images/xls.png'); ?>' ></a>	
				 
<?php } ?>							
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
                                        <th>Work Order #</th>
										<th>Supervisor</th>
										<th>Date of Issue</th>
										<th>Date Work Order</th>
										<th>Job Code/Name</th>
										<th>Observation</th>
                                    </tr>
                                </thead>
                                <tbody>							
								<?php
									$total = 0;
									foreach ($info as $lista):
											echo "<tr>";
											echo "<td class='text-center'>" . $lista['id_workorder'] . "</td>";
											echo "<td>" . $lista['name'] . "</td>";
											echo "<td class='text-center'>" . $lista['date_issue'] . "</td>";
											echo "<td class='text-center'>" . $lista['date'] . "</td>";
											echo "<td >" . $lista['job_description'] . "</td>";
											echo "<td >" . $lista['observation'] . "</td>";
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

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
							<a class="btn btn-success" href=" <?php echo base_url().'report/searchByDateRange/safety'; ?> "><span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Go back </a> 
                            <i class="fa fa-life-saver fa-fw"></i> SAFETY REPORT
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
						<div class="alert alert-info">
							<strong>From Date: </strong><?php echo $from; ?> 
							<strong>To Date: </strong><?php echo $to; ?> 
							
<?php if($info){ ?>
							<br><strong>Download to: </strong>
							
<a href='<?php echo base_url('report/generaSafetyPDF/' . $jobId. '/' . $from . '/' . $to ); ?>' target="_blank">PDF <img src='<?php echo base_url('images/pdf.png'); ?>' ></a>	
				 
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
                                        <th>Date of Issue</th>
										<th>Work To Be Done</th>
										<th>Job Code/Name</th>
										<th>Muster Point</th>
										<th>Meeting conducted by</th>
										<th>PPE</th>
                                    </tr>
                                </thead>
                                <tbody>							
								<?php
									foreach ($info as $lista):
											echo "<tr>";
											echo "<td class='text-center'>" . $lista['date'] . "</td>";
											echo "<td>" . $lista['work'] . "</td>";
											echo "<td >" . $lista['job_description'] . "</td>";
											echo "<td >" . $lista['muster_point'] . "</td>";
											echo "<td ><strong>" . $lista['name'] . "</strong></td>";
											echo "<td class='text-center'>";
								?>
								<input type="checkbox" id="ppe" name="ppe" <?php if($lista["ppe"] == 1) { echo "checked"; }  ?> disabled />
								<?php
											echo"</strong></td>";
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
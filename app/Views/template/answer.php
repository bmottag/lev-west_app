


        <div id="page-wrapper">

			<br>
			<div class="row">
				<div class="col-md-12">
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h4 class="list-group-item-heading">
								<?php echo $titulo; ?>
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
							<?php if($linkBack){ ?>
							<a class="btn btn-success" href=" <?php echo base_url($linkBack); ?> "><span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Go back </a> 
							<?php } ?>
                            <?php echo $titulo; ?>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">
								

	<div class="row" align="center">
		<div style="width:50%;" align="center">
			<div class="alert <?php echo $clase;?>"> <span class="glyphicon glyphicon-ok">&nbsp;</span>
				<?php echo $msj; ?>
			</div>
		</div>
	</div>	
								

                                </div>
                                <!-- /.col-lg-6 (nested) -->
                                <div class="col-lg-6">
								</div>
                                <!-- /.col-lg-6 (nested) -->
                            </div>
                            <!-- /.row (nested) -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->

